<?php
namespace business\models\parts\trade;

use Yii;
use common\ActiveRecord\BusinessUserWalletAR;
use common\models\parts\trade\WalletAbstract;
use common\ActiveRecord\BusinessUserReceiveLogAR;
use common\ActiveRecord\BusinessUserStatementAR;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;
use common\ActiveRecord\BusinessUserFreezeLogAR;
use common\ActiveRecord\BusinessUserFreezeDrawAR;
use common\ActiveRecord\BusinessUserThawLogAR;
use common\ActiveRecord\BusinessUserThawDrawAR;
use common\ActiveRecord\BusinessUserPayLogAR;
use common\ActiveRecord\BusinessUserPayDrawAR;
use admin\modules\fund\models\parts\DepositAndDrawTicket;

class Wallet extends WalletAbstract{

    public $userId;

    protected $rmbBefore;
    protected $rmbAfter;

    protected function getActiveRecord(){
        return new BusinessUserWalletAR;
    }

    public function init(){
        if(is_null($this->id) && $this->userId){
            try{
                $this->id = BusinessUserWalletAR::findOne(['business_user_id' => $this->userId])->id;
            }catch(\Exception $e){}
        }
        parent::init();
    }

    public function getRMB(){
        return (float)$this->AR->rmb;
    }

    public function getFrozenRMB(){
        return (float)$this->AR->frozen_rmb;
    }

    public function getUserId(){
        return $this->AR->business_user_id;
    }

    public static function getReceiveTypes(){
        return [
            self::RECEIVE_PARTNER_AWARD,
            self::RECEIVE_MEMBRANE_ORDER_FINISH,
            self::RECEIVE_NON_TRANSACTION,
        ];
    }

    public function canReceive($receiveType){
        return in_array($receiveType, self::getReceiveTypes());
    }

    public function pay($something, WalletAbstract $receiver = null){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $this->rmbBefore = $this->RMB;
            if($something instanceof DrawTicket){
                if($something->status != DrawTicket::STATUS_PASS)throw new \Exception;
                $receiver = new \admin\models\parts\trade\Wallet([
                    'id' => 2,
                    'receiveType' => self::RECEIVE_BUSINESS_DRAW,
                ]);
                if(!$this->decreaseRMB($something->rmb))throw new \Exception;
                $this->rmbAfter = $this->RMB;
                if(!$drawLogId = $this->recordDraw($something))throw new \Exception;
                if(!$this->recordStatement(Statement::TYPE_PAY, $drawLogId, (float)$something->rmb))throw new \Exception;
                if(!$receiver->receive([
                    'rmb' => (float)$something->rmb,
                    'logId' => (int)$drawLogId,
                ]))throw new \Exception;
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

    public function receive($something){
        if(!$this->canReceive($this->receiveType))return false;
        if(is_array($something)){
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

    public function freeze($something, $return = 'throw'){
        if($something instanceof DrawTicket){
            if($this->rmb < $something->rmb)return Yii::$app->EC->callback($return, 'not enough money');
            if(BusinessUserFreezeDrawAR::findOne(['user_draw_id' => $something->id]))return Yii::$app->EC->callback($return, 'this draw ticket had been frozen already');
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $this->rmbBefore = $this->rmb;
                if(!$this->decreaseRMB((float)$something->rmb))throw new \Exception;
                $this->rmbAfter = $this->rmb;
                $this->increaseFrozenRMB((float)$something->rmb);
                $freezeLogId = $this->recordFreezeDraw($something);
                if(!$this->recordStatement(Statement::TYPE_FREEZE, $freezeLogId, (float)$something->rmb))throw new \Exception;
                $transaction->commit();
                return true;
            }catch(\Exception $e){
                $transaction->rollBack();
                return Yii::$app->EC->callback($return, $e);
            }
        }else{
            return Yii::$app->EC->callback($return, 'unknown situation');
        }
    }

    public function thaw($something, $return = 'throw'){
        if($something instanceof DrawTicket){
            if($this->frozenRMB < $something->rmb)return Yii::$app->EC->callback($return, 'not enough frozen money');
            if(!BusinessUserFreezeDrawAR::findOne(['user_draw_id' => $something->id]))return Yii::$app->EC->callback($return, 'this draw ticket has not frozen money');
            if(BusinessUserThawDrawAR::findOne(['user_draw_id' => $something->id]))return Yii::$app->EC->callback($return, 'this draw ticket had been thawed');
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $this->rmbBefore = $this->rmb;
                if(!$this->increaseRMB((float)$something->rmb))throw new \Exception;
                $this->rmbAfter = $this->rmb;
                $this->decreaseFrozenRMB((float)$something->rmb);
                $thawLogId = $this->recordThawDraw($something);
                if(!$this->recordStatement(Statement::TYPE_THAW, $thawLogId, (float)$something->rmb))throw new \Exception;
                $transaction->commit();
                return true;
            }catch(\Exception $e){
                $transaction->rollBack();
                return Yii::$app->EC->callback($return, $e);
            }
        }else{
            return Yii::$app->EC->callback($return, 'unknown situation');
        }
    }

    protected function recordDraw(DrawTicket $drawTicket){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $logId = Yii::$app->RQ->AR(new BusinessUserPayLogAR)->insert([
                'business_user_id' => $drawTicket->userAccount->id,
                'pay_type' => self::PAY_BUSINESS_DRAW,
                'pay_amount' => $drawTicket->rmb,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
                'pay_datetime' => date('Y-m-d H:i:s'),
                'pay_unixtime' => time(),
            ]);
            Yii::$app->RQ->AR(new BusinessUserPayDrawAR)->insert([
                'business_user_pay_log_id' => $logId,
                'user_draw_id' => $drawTicket->id,
            ]);
            $transaction->commit();
            return $logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordPayNonTransaction(DepositAndDrawTicket $ticket){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $logId = Yii::$app->RQ->AR(new BusinessUserPayLogAR)->insert([
                'business_user_id' => $ticket->targetUserId,
                'pay_type' => self::PAY_NON_TRANSACTION,
                'pay_amount' => $ticket->amount,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
                'pay_datetime' => date('Y-m-d H:i:s'),
                'pay_unixtime' => time(),
            ]);
            Yii::$app->RQ->AR(new \common\ActiveRecord\BusinessUserPayNonTransactionAR)->insert([
                'business_user_pay_log_id' => $logId,
                'non_transaction_deposit_and_draw_id' => $ticket->id,
            ]);
            $transaction->commit();
            return $logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function recordThawDraw(DrawTicket $drawTicket, $return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $logId = Yii::$app->RQ->AR(new BusinessUserThawLogAR)->insert([
                'business_user_id' => $drawTicket->userId,
                'thaw_type' => self::THAW_BUSINESS_DRAW,
                'thaw_amount' => $drawTicket->rmb,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
                'thaw_datetime' => date('Y-m-d H:i:s'),
                'thaw_unixtime' => time(),
            ]);
            Yii::$app->RQ->AR(new BusinessUserThawDrawAR)->insert([
                'business_user_thaw_log_id' => $logId,
                'user_draw_id' => $drawTicket->id,
            ]);
            $transaction->commit();
            return $logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    protected function recordFreezeDraw(DrawTicket $drawTicket, $return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $logId = Yii::$app->RQ->AR(new BusinessUserFreezeLogAR)->insert([
                'business_user_id' => $drawTicket->userId,
                'freeze_type' => self::FREEZE_BUSINESS_DRAW,
                'freeze_amount' => $drawTicket->rmb,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
                'freeze_datetime' => date('Y-m-d H:i:s'),
                'freeze_unixtime' => time(),
            ]);
            Yii::$app->RQ->AR(new BusinessUserFreezeDrawAR)->insert([
                'business_user_freeze_log_id' => $logId,
                'user_draw_id' => $drawTicket->id,
            ]);
            $transaction->commit();
            return $logId;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    protected function increaseFrozenRMB(float $rmb, $return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'frozen_rmb' => $this->AR->frozen_rmb + $rmb,
        ], $return) ? true : false;
    }

    protected function decreaseFrozenRMB(float $rmb, $return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'frozen_rmb' => $this->AR->frozen_rmb - $rmb,
        ], $return) ? true : false;
    }

    protected function recordReceive($type, int $logId, float $rmb){
        if(!$this->canReceive($type))return false;
        try{
            return Yii::$app->RQ->AR(new BusinessUserReceiveLogAR)->insert([
                'business_user_id' => $this->getUserId(),
                'receive_type' => $type,
                'corresponding_log_id' => $logId,
                'receive_amount' => $rmb,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
                'receive_datetime' => Yii::$app->time->fullDate,
                'receive_unixtime' => Yii::$app->time->unixTime,
            ]);
        }catch(\Exception $e){
            return false;
        }
    }

    protected function recordStatement($type, int $logId, float $rmb){
        if(!in_array($type, Statement::getTypes()))return false;
        try{
            return Yii::$app->RQ->AR(new BusinessUserStatementAR)->insert([
                'business_user_id' => $this->getUserId(),
                'alteration_type' => $type,
                'corresponding_log_id' => $logId,
                'alteration_amount' => $rmb,
                'rmb_before' => $this->rmbBefore,
                'rmb_after' => $this->rmbAfter,
                'alteration_datetime' => Yii::$app->time->fullDate,
                'alteration_unixtime' => Yii::$app->time->unixTime,
            ]);
        }catch(\Exception $e){
            return false;
        }
    }
}
