<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\ActiveRecord\OrderAR;
use common\models\parts\Order;
use custom\components\handler\OrderHandler;

/**
 * 订单操作
 */
class OrderController extends Controller{

    const STATUS_CONFIRMED_SWITCH_TIME = 604800; //7天
    const STATUS_CLOSED_SWITCH_TIME = 604800; //7天
    const STATUS_CANCEL_SWITCH_TIME = 7200; //2小时

    /**
     * 强制取消订单
     */
    public function actionCancelForcely($orderNumber){
        try{
            $order = new Order(['orderNumber' => $orderNumber]);
            OrderHandler::cancel($order);
            $this->stdout("Cancel order [{$orderNumber}] success\n");
        }catch(\Exception $e){
            $this->stdout("Cancel order [{$orderNumber}] fail\n");
        }
        return 0;
    }

    /**
     * 取消订单
     */
    public function actionCancelOrder(){
        $deadTime = Yii::$app->time->unixTime - self::STATUS_CANCEL_SWITCH_TIME;
        $ordersId = Yii::$app->RQ->AR(new OrderAR)->column([
            'select' => ['id'],
            'where' => ['<', 'create_unixtime', $deadTime],
            'andWhere' => ['status' => Order::STATUS_UNPAID],
        ]);
        foreach($ordersId as $orderId){
            $order = new Order(['id' => $orderId]);
            if(!OrderHandler::cancel($order, false)){
                Yii::warning("Order: [{$order->id}] set status [canceled] failed", __METHOD__);
            }
        }
        return 0;
    }

    /**
     * 确认订单
     */
    public function actionConfirmOrder(){
        $deadTime = Yii::$app->time->unixTime - self::STATUS_CONFIRMED_SWITCH_TIME;
        $ordersId = Yii::$app->RQ->AR(new OrderAR)->column([
            'select' => ['id'],
            'where' => [
                '<', 'deliver_unixtime', $deadTime,
            ],
            'andWhere' => [
                'status' => Order::STATUS_DELIVERED,
            ],
        ]);
        foreach($ordersId as $orderId){
            $order = new Order([
                'id' => $orderId,
            ]);
            if(!$order->setStatus(Order::STATUS_CONFIRMED, false, false)){
                Yii::warning("Order: [{$order->id}] set status [confirmed] failed", __METHOD__);
            }
        }
        return 0;
    }

    /**
     * 关闭订单
     */
    public function actionCloseOrder(){
        $deadTime = Yii::$app->time->unixTime - self::STATUS_CLOSED_SWITCH_TIME;
        $ordersId = OrderAR::find()->select(['id'])->where(['<', 'receive_unixtime', $deadTime])->andWhere(['refund_status' => 0])->andWhere(['status' => Order::STATUS_CONFIRMED])->column();
        foreach($ordersId as $orderId){
            $order = new Order([
                'id' => $orderId,
            ]);
            if(!$order->setStatus(Order::STATUS_CLOSED, false, false)){
                Yii::warning("Order: [{$order->id}] set status [closed] failed", __METHOD__);
            }
        }
        return 0;
    }
}
