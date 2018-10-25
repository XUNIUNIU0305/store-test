<?php
namespace custom\components\handler;

use Yii;
use common\components\handler\RechargeApplyHandlerAbstract;
use common\ActiveRecord\CustomUserRechargeApplyAR;
use common\models\RapidQuery;
use custom\models\parts\trade\RechargeMethod;
use custom\models\parts\trade\Trade;
use common\models\parts\trade\recharge\RechargeApply;
use common\models\parts\trade\RechargeMethodAbstract;

class CustomRechargeApplyHandler extends RechargeApplyHandlerAbstract{

    public static function create(float $rmb, RechargeMethodAbstract $rechargeMethod, Trade $trade = null, $return = 'throw'){
        if($rmb <= 0)return self::errCallback($return, 'P_float');
        if(!$rechargeMethod->currentRechargeMethod)return self::errCallback($return, 'unavailable recharge method');
        if(Yii::$app->user->isGuest)return self::errCallback($return, 'guest');
        $tradeId = is_null($trade) ? 0 : $trade->id;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!(new RapidQuery(new CustomUserRechargeApplyAR))->insert([
                'custom_user_id' => Yii::$app->user->id,
                'custom_user_trade_id' => $tradeId,
                'recharge_method' => $rechargeMethod->currentRechargeMethod,
                'recharge_amount' => $rmb,
            ]))throw new \Exception;
            if(!$rechargeApplyId = self::createRechargeApply(RechargeApply::USER_TYPE_CUSTOMER, Yii::$app->db->lastInsertId))throw new \Exception;
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            return self::errCallback($return, 'mysql');
        }
        return new RechargeApply(['id' => $rechargeApplyId]);
    }
}
