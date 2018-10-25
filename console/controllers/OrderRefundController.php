<?php
namespace console\controllers;

use Yii;
use common\models\parts\order\OrderRefund;
use common\ActiveRecord\OrderRefundAR;
use console\controllers\basic\Controller;

class OrderRefundController extends Controller{

    const REFUND_STATUS_CONFIRMED_SWITCH_TIME = 604800; //7天

    public function actionFinishRefund(){
        $deadTime = Yii::$app->time->unixTime - self::REFUND_STATUS_CONFIRMED_SWITCH_TIME;
        $unconfirmOrdersId = Yii::$app->RQ->AR(new OrderRefundAR)->column([
            'select' => ['id'],
            'where' => [
                'status' => OrderRefund::REFUND_STATUS_SENDED,
            ],
            'andWhere' => [
                '<', 'supply_refund_send_time', $deadTime,
            ],
        ]);
        foreach($unconfirmOrdersId as $id){
            $order = new OrderRefund([
                'id' => $id,
            ]);
            if(!$order->setStatus(OrderRefund::REFUND_STATUS_FINISHED)){
                Yii::warning("Refund Order: [{$id}] set status [finished] failed", __METHOD__);
            }
        }
        return 0;
    }
}
