<?php
namespace admin\components\handler;

use Yii;
use admin\models\parts\trade\Trade;
use common\components\handler\Handler;
use common\ActiveRecord\AdminTradeAR;
use common\ActiveRecord\AdminTradePartnerAR;
use common\models\parts\partner\PartnerApply;
use common\models\parts\trade\RechargeMethodAbstract;

class TradeHandler extends Handler{

    public static function createPartnerTrade(PartnerApply $apply, $return = 'throw'){
        if($apply->isPaid)return Yii::$app->EC->callback($return, 'the apply has been paid');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $tradeId = Yii::$app->RQ->AR(new AdminTradeAR)->insert([
                'type' => Trade::TYPE_PARTNER,
                'total_fee' => PartnerApply::APPLY_RMB,
                'payment_method' => RechargeMethodAbstract::METHOD_WX_INWECHAT,
                'create_datetime' => Yii::$app->time->fullDate,
                'create_unixtime' => Yii::$app->time->unixTime,
            ]);
            Yii::$app->RQ->AR(new AdminTradePartnerAR)->insert([
                'admin_trade_id' => $tradeId,
                'partner_apply_id' => $apply->id,
            ]);
            $transaction->commit();
            return new Trade(['id' => $tradeId]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }
}
