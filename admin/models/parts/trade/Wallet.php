<?php
namespace admin\models\parts\trade;

use common\ActiveRecord\AdminPayMembraneOrderAR;
use common\ActiveRecord\AdminPayPrizeAR;
use common\models\lottery\ChancePrize;
use common\models\parts\MembraneOrder;
use Yii;
use common\models\parts\trade\WalletAbstract;
use common\ActiveRecord\AdminWalletAR;
use common\models\RapidQuery;
use common\ActiveRecord\AdminStatementAR;
use common\ActiveRecord\AdminReceiveLogAR;
use common\ActiveRecord\AdminRechargeLogAR;
use common\ActiveRecord\AdminPayLogAR;
use common\ActiveRecord\AdminPayOrderAR;
use common\ActiveRecord\AdminPayGroupbuyAR;
use common\models\parts\Order;
use common\models\parts\trade\recharge\AdministratorApply;
use common\models\parts\partner\Authorization;
use common\models\temp\Groupbuy;
use common\ActiveRecord\AdminPayAuthorizationAR;
use yii\db\Exception;
use admin\modules\fund\models\parts\DepositAndDrawTicket;
use common\models\parts\gpubs\GpubsGroupDetail;

class Wallet extends WalletAbstract{

    const WALLET_MAIN = 1;
    const WALLET_PROFIT = 2;
    const WALLET_NON_TRANSACTION_DEPOSIT_AND_DRAW = 3;

    public $id = self::WALLET_MAIN;

    protected $rmbBefore;
    protected $rmbAfter;

    protected function getActiveRecord(){
        return new AdminWalletAR;
    }

    public function getRMB(){
        return (float)$this->AR->rmb;
    }

    public function pay($something, WalletAbstract $receiver = null){
        if(is_null($receiver))return false;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $this->rmbBefore = $this->RMB;
            if($something instanceof Order){
                if(!$this->payOrder($something))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$payLogId = $this->recordPayOrder($something))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->totalFee))throw new \Exception;
                if(!$receiver->receive([
                    'rmb' => $something->totalFee,
                    'logId' => $payLogId,
                ]))throw new \Exception;
            }elseif($something instanceof Trade){
                $this->payTrade($something);
            }elseif($something instanceof Authorization) {
                if (!$this->payAuthorization($something)) throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if (!$payLogId = $this->recordPayAuthorization($something)) throw new \Exception;
                if (!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->awardRmb)) throw new \Exception;
                if (!$receiver->receive([
                    'rmb' => $something->awardRmb,
                    'logId' => $payLogId,
                ])) throw new \Exception;
            }elseif($something instanceof MembraneOrder) {
                //膜订单
                if (!$this->decreaseRMB($something->getTotalFee())) throw new \Exception('扣款失败');
                $this->rmbAfter = $this->RMB;
                if (!$payLogId = $this->recordPayMembraneOrder($something)) throw new \Exception('记录流程失败');
                if (!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->getTotalFee())) throw new Exception('记录支付流水失败');
                if (!$receiver->receive([
                    'rmb' => $something->getTotalFee(),
                    'logId' => $payLogId
                ])) throw new \Exception('退回至用户余额失败');
            }elseif($something instanceof ChancePrize){
                //代金券充值
                if (!$this->decreaseRMB($something->price)) throw new \Exception('扣款失败');
                $this->rmbAfter = $this->RMB;
                if (!$payLogId = $this->recordPayPrize($something)) throw new \Exception('记录流程失败');
                if (!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->getPrice())) throw new Exception('记录支付流水失败');
                if (!$receiver->receive([
                    'rmb' => $something->getPrice(),
                    'logId' => $payLogId
                ])) throw new \Exception('支付奖品代金券失败');
            }elseif($something instanceof Groupbuy){
                //团购返现
                if (!$this->decreaseRMB($something->price)) throw new \Exception('扣款失败');
                $this->rmbAfter = $this->RMB;
                if (!$payLogId = $this->recordGroupbuy($something)) throw new \Exception('记录流程失败');
                if (!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->getPrice())) throw new Exception('记录支付流水失败');
                if (!$receiver->receive([
                    'rmb' => $something->getPrice(),
                    'logId' => $payLogId
                ])) throw new \Exception('团购返现失败');
            }elseif($something instanceof DepositAndDrawTicket){
                if(!$this->decreaseRMB($something->amount))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$payLogId = $this->recordPayNonTransaction($something))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->amount))throw new \Exception;
                if(!$receiver->receive([
                    'rmb' => (float)$something->amount,
                    'logId' => (int)$payLogId,
                ]))throw new \Exception;
            }elseif($something instanceof GpubsGroupDetail){
                if(!$this->decreaseRMB($something->total_fee))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$payLogId = $this->recordPayGpubsOrder($something))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->total_fee))throw new \Exception;
                if(!$receiver->receive([
                    'rmb' => (float)$something->total_fee,
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

    public function receive($something){
        if(!$this->canReceive($this->receiveType))return false;
        if($something instanceof AdministratorApply){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $this->rmbBefore = $this->RMB;
                $this->recharge($something);
                $this->rmbAfter = $this->RMB;
                if(!$rechargeLogId = $this->recordRecharge($something))throw new \Exception;
                if($something->tradeId){
                    $trade = new Trade(['id' => $something->tradeId]);
                    $trade->setRechargeId($rechargeLogId);
                }
                if(!$receiveLogId = $this->recordReceive($this->receiveType, $rechargeLogId, $something->rechargeAmount))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_RECEIVE, $receiveLogId, $something->rechargeAmount))throw new \Exception;
                $transaction->commit();
                return true;
            }catch(\Exception $e){
                $transaction->rollBack();
                return false;
            }
        }elseif(is_array($something)){
            $rmb = null;
            $logId = null;
            extract($something, EXTR_IF_EXISTS);
            if(!is_float($rmb) || !is_int($logId))return false;
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $this->rmbBefore = $this->RMB;
                if(!$this->increaseRMB($rmb))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$receiveLogId = $this->recordReceive($this->receiveType, $logId, $rmb))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_RECEIVE, $receiveLogId, $rmb))throw new \Exception;
                $transaction->commit();
                return true;
            }catch(\Exception $e){
                $transaction->rollBack();
                return false;
            }
        }else{
            return false;
        }
    }

    public function canReceive($receiveType){
        return in_array($receiveType, self::getReceiveTypes());
    }

    public static function getReceiveTypes(){
        return [
            self::RECEIVE_UNDEFINED,
            self::RECEIVE_CUSTOM_CONSUMPTION,
            self::RECEIVE_PARTNER_RECHARGE,
            self::RECEIVE_BUSINESS_DRAW,
            self::RECEIVE_NON_TRANSACTION,
        ];
    }

    protected function recharge(AdministratorApply $apply, $return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$this->increaseRMB($apply->rechargeAmount))throw new \Exception;
            if(!$apply->setRecharged())throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    protected function recordRecharge(AdministratorApply $apply){
        if(!$apply->notifyId)return false;
        return Yii::$app->RQ->AR(new AdminRechargeLogAR)->insert([
            'admin_recharge_apply_id' => $apply->id,
            'corresponding_notify_id' => $apply->notifyId,
            'recharge_method' => $apply->rechargeMethod,
            'recharge_amount' => $apply->rechargeAmount,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
            'recharge_datetime' => Yii::$app->time->fullDate,
            'recharge_unixtime' => Yii::$app->time->unixTime,
        ], false);
    }

    protected function payOrder(Order $order){
        return $this->decreaseRMB($order->totalFee);
    }

    protected function payTrade(Trade $trade){
        return $trade->setPaid();
    }

    protected function payAuthorization(Authorization $authorization){
        return $this->decreaseRMB($authorization->awardRmb);
    }

    protected function recordPayOrder(Order $order){
        if($order->status == $order::STATUS_CANCELED){
            $payType = self::PAY_ORDER_CANCELED;
        }elseif($order->status == $order::STATUS_CONFIRMED){
            $payType = self::PAY_ORDER_CONFIRMED;
        }else{
            $payType = self::PAY_UNDEFINED;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR(new AdminPayLogAR)->insert([
                'admin_wallet_id' => $this->id,
                'pay_type' => $payType,
                'pay_amount' => $order->totalFee,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
            ]);
            $logId = Yii::$app->db->lastInsertId;
            Yii::$app->RQ->AR(new AdminPayOrderAR)->insert([
                'admin_pay_log_id' => $logId,
                'order_id' => $order->id,
            ]);
            $transaction->commit();
            return (int)$logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordPayGpubsOrder(GpubsGroupDetail $detail){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR(new AdminPayLogAR)->insert([
                'admin_wallet_id' => $this->id,
                'pay_type' => self::PAY_GPUBS_ORDER,
                'pay_amount' => $detail->total_fee,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
            ]);
            $logId = Yii::$app->db->lastInsertId;
            Yii::$app->RQ->AR(new \common\ActiveRecord\AdminPayGpubsOrderAR)->insert([
                'admin_pay_log_id' => $logId,
                'gpubs_group_detail_id' => $detail->id,
            ]);
            $transaction->commit();
            return (int)$logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordPayNonTransaction(DepositAndDrawTicket $ticket){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR(new AdminPayLogAR)->insert([
                'admin_wallet_id' => $this->id,
                'pay_type' => self::PAY_NON_TRANSACTION,
                'pay_amount' => $ticket->amount,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
            ]);
            $logId = Yii::$app->db->lastInsertId;
            Yii::$app->RQ->AR(new \common\ActiveRecord\AdminPayNonTransactionAR)->insert([
                'admin_pay_log_id' => $logId,
                'non_transaction_deposit_and_draw_id' => $ticket->id,
            ]);
            $transaction->commit();
            return (int)$logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 支付膜订单记录
     * @param MembraneOrder $order
     * @return bool|mixed
     */
    protected function recordPayMembraneOrder(MembraneOrder $order)
    {
        if($order->getStatus() === MembraneOrder::STATUS_CANCELED){
            $payType = self::PAY_MEMBRANE_ORDER_CANCELED;
        } elseif ($order->getStatus() === MembraneOrder::STATUS_FINISHED){
            $payType = self::PAY_MEMBRANE_ORDER_FINISH;
        } else {
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $entity = new AdminPayLogAR([
                'admin_wallet_id' => $this->id,
                'pay_type' => $payType,
                'pay_amount' => $order->getTotalFee(),
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter
            ]);
            $entity->insert(false);
            $log = new AdminPayMembraneOrderAR([
                'admin_pay_log_id' => $entity->id,
                'membrane_order_id' => $order->id
            ]);
            $log->insert(false);
            $transaction->commit();
            return $entity->id;
        } catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 记录支付到代金券奖品
     * @param ChancePrize $prize
     * @return bool|mixed
     */
    protected function recordPayPrize(ChancePrize $prize)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $log = new AdminPayLogAR([
                'admin_wallet_id' => $this->id,
                'pay_type' => self::PAY_PRIZE,
                'pay_amount' => $prize->getPrice(),
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter
            ]);
            $log->insert(false);
            $pay = new AdminPayPrizeAR([
                'admin_pay_log_id' => $log->id,
                'prize_id' => $prize->id
            ]);
            $pay->insert(false);
            $transaction->commit();
            return $log->id;
        } catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
    
    /**
     * 记录团购返现
     * @param ChancePrize $prize
     * @return bool|mixed
     */
    protected function recordGroupbuy(Groupbuy $groupbuy)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $log = new AdminPayLogAR([
                'admin_wallet_id' => $this->id,
                'pay_type' => self::PAY_GROUPBUY,
                'pay_amount' => $groupbuy->getPrice(),
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter
            ]);
            $log->insert(false);
            $pay = new AdminPayGroupbuyAR([
                'admin_pay_log_id' => $log->id,
                'activity_groupbuy_order_id' => $groupbuy->id
            ]);
            $pay->insert(false);
            $transaction->commit();
            return $log->id;
        } catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordPayAuthorization(Authorization $authorization){
        $payType = self::PAY_PARTNER_AWARD;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $logId = Yii::$app->RQ->AR(new AdminPayLogAR)->insert([
                'admin_wallet_id' => $this->id,
                'pay_type' => $payType,
                'pay_amount' => $authorization->awardRmb,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
            ]);
            Yii::$app->RQ->AR(new AdminPayAuthorizationAR)->insert([
                'admin_pay_log_id' => $logId,
                'custom_user_authorization_id' => $authorization->id,
            ]);
            $transaction->commit();
            return (int)$logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordReceive($type, int $logId, float $rmb){
        if(!$this->canReceive($type))return false;
        if((new RapidQuery(new AdminReceiveLogAR))->insert([
            'admin_wallet_id' => $this->id,
            'receive_type' => $type,
            'corresponding_log_id' => $logId,
            'receive_amount' => $rmb,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ])){
            return (int)Yii::$app->db->lastInsertId;
        }else{
            return false;
        }
    }

    protected function recordStatement($type, int $logId, float $rmb){
        if(!in_array($type, Statement::getTypes()))return false;
        return (new RapidQuery(new AdminStatementAR))->insert([
            'admin_wallet_id' => $this->id,
            'alteration_type' => $type,
            'corresponding_log_id' => $logId,
            'alteration_amount' => $rmb,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ]);
    }
}
