<?php
namespace api\components\handler;

use Yii;
use common\components\handler\Handler;
use common\models\parts\trade\recharge\RechargeApply;
use common\models\parts\trade\recharge\CustomerApply;
use common\models\parts\trade\recharge\AdministratorApply;
use custom\models\parts\trade\Wallet;

class RechargeHandler extends Handler{

    public static function recharge(RechargeApply $apply, $notifyLogId){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($apply->userType == $apply::USER_TYPE_CUSTOMER){
                $result = self::customerRecharge($apply->detail, $notifyLogId);
            }elseif($apply->userType == $apply::USER_TYPE_SUPPLIER){
                $result = false;
            }elseif($apply->userType == $apply::USER_TYPE_ADMINISTRATOR){
                $result = self::administratorRecharge($apply->detail, $notifyLogId);
            }else{
                $result = false;
            }
            if($result){
                if(!$apply->setRecharged())throw new \Exception;
            }else{
                throw new \Exception;
            }
            $transaction->commit();
            return $result;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected static function customerRecharge(CustomerApply $apply, $notifyLogId){
        if(!$apply->setNotifyId($notifyLogId))return false;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $wallet = new Wallet([
                'userId' => $apply->userId,
                'receiveType' => Wallet::RECEIVE_RECHARGE,
            ]);
            $result = $wallet->receive($apply);
            $transaction->commit();
            return $result;
        }catch(\Exception $e){
            $transaction->rollBack();
        }
    }

    protected static function administratorRecharge(AdministratorApply $apply, $notifyLogId){
        if(!$apply->setNotifyId($notifyLogId))return false;
        $wallet = new \admin\models\parts\trade\Wallet([
            'receiveType' => Wallet::RECEIVE_PARTNER_RECHARGE,
        ]);
        return $wallet->receive($apply);
    }
}
