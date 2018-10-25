<?php
namespace admin\components\handler;

use Yii;
use admin\models\parts\trade\Trade;
use common\ActiveRecord\AdminRechargeApplyAR;
use common\models\parts\trade\recharge\RechargeApply;
use common\models\parts\trade\RechargeMethodAbstract;
use common\components\handler\RechargeApplyHandlerAbstract;

class AdminRechargeApplyHandler extends RechargeApplyHandlerAbstract{

    public static function create(float $rmb, RechargeMethodAbstract $rechargeMethod, Trade $trade = null, $return = 'throw'){
        if($rmb <= 0)return Yii::$app->EC->callback($return, 'P_float');
        if(!$rechargeMethod->currentRechargeMethod)return Yii::$app->EC->callback($return, 'unavailable recharge method');
        $tradeId = is_null($trade) ? 0 : $trade->id;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $adminRechargeApplyId = Yii::$app->RQ->AR(new AdminRechargeApplyAR)->insert([
                'admin_trade_id' => $tradeId,
                'recharge_method' => $rechargeMethod->currentRechargeMethod,
                'recharge_amount' => $rmb,
                'apply_datetime' => Yii::$app->time->fullDate,
                'apply_unixtime' => Yii::$app->time->unixTime,
            ]);
            $rechargeId = self::createRechargeApply(RechargeApply::USER_TYPE_ADMINISTRATOR, $adminRechargeApplyId);
            if(!$rechargeId)throw new \Exception;
            $transaction->commit();
            return new RechargeApply(['id' => $rechargeId]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }
}
