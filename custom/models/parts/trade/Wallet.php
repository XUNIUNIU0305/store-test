<?php
namespace custom\models\parts\trade;


use common\components\amqp\Message;
use console\models\AmqpTask\CouponAmqpAutoSendTask;
use Yii;
use common\models\parts\trade\WalletAbstract;
use common\ActiveRecord\CustomUserRechargeLogAR;
use common\ActiveRecord\CustomUserConsumptionLogAR;
use common\ActiveRecord\CustomUserStatementAR;
use common\ActiveRecord\CustomUserWalletAR;
use common\ActiveRecord\CustomUserReceiveLogAR;
use common\ActiveRecord\CustomUserPayLogAR;
use common\ActiveRecord\CustomUserPayTradeAR;
use common\models\parts\trade\recharge\CustomerApply;
use common\models\RapidQuery;
use admin\modules\fund\models\parts\DepositAndDrawTicket;


class Wallet extends WalletAbstract{

    /**
     * 用户ID
     *
     * 如果未设置$id，则根据用户ID赋值$id
     */
    public $userId;

    protected $rmbBefore;
    protected $rmbAfter;

    public function init(){
        if(is_null($this->id) && $this->userId){
            try{
                $this->id = CustomUserWalletAR::findOne(['custom_user_id' => $this->userId])->id;
            }catch(\Exception $e){}
        }
        parent::init();
    }

    protected function getActiveRecord(){
        return new CustomUserWalletAR;
    }

    /**
     * 获取钱包内余额
     *
     * @return float
     */
    public function getRMB(){
        return (float)$this->AR->rmb;
    }

    /**
     * inhert 支付
     */
    public function pay($something, WalletAbstract $receiver = null){
        $receiver = $receiver ?? $this->getReceiver();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $this->rmbBefore = $this->RMB;
            if($something instanceof Trade){
                if(!$this->payTrade($something))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$payLogId = $this->recordTradeConsumption($something))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->totalFee))throw new \Exception;
                if(!$receiver->receive([
                    'rmb' => $something->totalFee,
                    'logId' => $payLogId,
                ]))throw new \Exception;

                //MOD:Jiangyi:date:2017/05/03 desc:创建消息队列,用于发送优惠券
                $this->createCouponAutoSendTask($something);
            }elseif($something instanceof DepositAndDrawTicket){
                if(!$this->decreaseRMB($something->amount))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$payLogId = $this->recordPayNonTransaction($something))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->amount))throw new \Exception;
                if(!$receiver->receive([
                    'rmb' => (float)$something->amount,
                    'logId' => (int)$payLogId,
                ]))throw new \Exception;
            }else{
                throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    //MOD:Jiangyi:date:2017/05/03 desc:创建消息队列,用于发送优惠券
    private  function createCouponAutoSendTask(Trade $trade){
        $task=new CouponAmqpAutoSendTask(['trade_id'=>$trade->id]);
        $message=new Message($task);
        Yii::$app->amqp->publish($message);
        return true;
    }


    public function receive($something){
        if(!in_array($this->receiveType, self::getReceiveTypes()))return false;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $this->rmbBefore = $this->RMB;
            if($something instanceof CustomerApply){
                if(!$this->recharge($something))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$rechargeLogId = $this->recordRecharge($something))throw new \Exception;
                if($something->tradeId){
                    $trade = new Trade(['id' => $something->tradeId]);
                    if(!$trade->setRechargeId($rechargeLogId))throw new \Exception;
                }
                if(!$receiveLogId = $this->recordReceive($rechargeLogId, $something->rechargeAmount))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_RECEIVE, $receiveLogId, $something->rechargeAmount))throw new \Exception;
            }elseif(is_array($something)){
                $rmb = null;
                $logId = null;
                extract($something, EXTR_IF_EXISTS);
                if(!is_float($rmb) || !is_int($logId))return false;
                if(!$this->increaseRMB($rmb))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$receiveLogId = $this->recordReceive($logId, $rmb))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_RECEIVE, $receiveLogId, $rmb))throw new \Exception;
            }else{
                return false;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    public function getUserId(){
        return $this->AR->custom_user_id;
    }

    public static function getReceiveTypes(){
        return [
            self::RECEIVE_RECHARGE,
            self::RECEIVE_ORDER_CANCELED,
            self::RECEIVE_SUPPLY_REFUND,
            self::RECEIVE_PARTNER_AWARD,
            self::RECEIVE_MEMBRANE_ORDER_CANCELED,
            self::RECEIVE_VOUCHER,
            self::RECEIVE_GROUPBUY,
            self::RECEIVE_NON_TRANSACTION,
            self::RECEIVE_GPUBS_ORDER,
        ];
    }

    protected function recharge(CustomerApply $apply){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$this->increaseRMB($apply->rechargeAmount))throw new \Exception;
            if(!$apply->setRecharged())throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordRecharge(CustomerApply $apply){
        if(!$apply->notifyId)return false;
        $result = (new RapidQuery(new CustomUserRechargeLogAR))->insert([
            'custom_user_id' => $apply->userId,
            'custom_user_recharge_apply_id' => $apply->id,
            'corresponding_notify_id' => $apply->notifyId,
            'recharge_method' => $apply->rechargeMethod,
            'recharge_amount' => $apply->rechargeAmount,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ]);
        return $result ? (int)Yii::$app->db->lastInsertId : false;
    }

    protected function recordReceive(int $logId, float $receiveAmount){
        if($logId <= 0 || $receiveAmount <= 0)return false;
        $result = Yii::$app->RQ->AR(new CustomUserReceiveLogAR)->insert([
            'custom_user_id' => $this->userId,
            'receive_type' => $this->receiveType,
            'corresponding_log_id' => $logId,
            'receive_amount' => $receiveAmount,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ]);
        return $result ? Yii::$app->db->lastInsertId : false;
    }

    protected function recordPay(int $payType, float $payAmount){
        if($payType < 0 || $payAmount <= 0)return false;
        $result = Yii::$app->RQ->AR(new CustomUserPayLogAR)->insert([
            'custom_user_id' => $this->userId,
            'pay_type' => $payType,
            'pay_amount' => $payAmount,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ]);
        return $result ? (int)Yii::$app->db->lastInsertId : false;
    }

    protected function getReceiver(){
        return new \admin\models\parts\trade\Wallet([
            'receiveType' => Wallet::RECEIVE_CUSTOM_CONSUMPTION,
        ]);
    }

    protected function payTrade(Trade $trade){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$this->decreaseRMB($trade->totalFee))throw new \Exception;
            if(!$trade->setPaid())throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordPayNonTransaction(DepositAndDrawTicket $ticket){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$payLogId = $this->recordPay(self::PAY_NON_TRANSACTION, $ticket->amount))throw new \Exception;
            Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserPayNonTransactionAR)->insert([
                'custom_user_pay_log_id' => $payLogId,
                'non_transaction_deposit_and_draw_id' => $ticket->id,
            ]);
            $transaction->commit();
            return $payLogId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordTradeConsumption(Trade $trade){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$payLogId = $this->recordPay(self::PAY_TRADE, $trade->totalFee))throw new \Exception;
            Yii::$app->RQ->AR(new CustomUserPayTradeAR)->insert([
                'custom_user_pay_log_id' => $payLogId,
                'custom_user_trade_id' => $trade->id,
            ]);
            $transaction->commit();
            return $payLogId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordStatement($type, int $logId, float $rmb){
        if(!in_array($type, Statement::getTypes()))return false;
        return (new RapidQuery(new CustomUserStatementAR))->insert([
            'custom_user_id' => $this->userId,
            'alteration_type' => $type,
            'corresponding_log_id' => $logId,
            'alteration_amount' => $rmb,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ]);
    }
}
