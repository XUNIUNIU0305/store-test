<?php
namespace custom\components\handler;

use common\ActiveRecord\CustomUserTradeMembraneAR;
use common\models\parts\coupon\CouponRecord;
use common\models\parts\MembraneOrder;
use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserTradeAR;
use common\ActiveRecord\CustomUserTradeOrderAR;
use common\models\RapidQuery;
use common\models\parts\Order;
use custom\models\parts\trade\PaymentMethod;
use custom\models\parts\trade\Trade;
use custom\modules\temp\models\parts\Zodiac;
use common\ActiveRecord\CustomUserTradeZodiacAR;

class TradeHandler extends Handler{

    /**
     * 创建订单交易单
     *
     * @param array $orders 订单对象
     * @param Object PaymentMethod $paymentMethod 支付方式
     *
     * @return Object
     */
    public static function createOrdersTrade(array $orders, PaymentMethod $paymentMethod, $return = 'throw'){

        if(Yii::$app->user->isGuest)return self::errCallback($return, 'guest');

        if(!$paymentMethod->currentPaymentMethod)return self::errCallback($return, 'no payment method');

        $totalFee = 0;
        $userIds = [];
        $ordersId = [];

        foreach($orders as $order){

            if(!($order instanceof Order))return self::errCallback($return, 'unavailable Object');

            $ordersId[] = [$order->id];
            $userIds[] = $order->customerId;
            $totalFee += $order->totalFee;

        }

        $userIds = array_unique($userIds);
        if(count($userIds) != 1 ||
            current($userIds) != Yii::$app->user->id
        )return self::errCallback($return, 'error user id');
        if($totalFee <= 0)return self::errCallback($return, 'unavailable total fee');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!(new RapidQuery(new CustomUserTradeAR))->insert([
                'custom_user_id' => Yii::$app->user->id,
                'type' => Trade::TYPE_ORDER,
                'order_total_fee'=>$totalFee,
                'total_fee' => $totalFee,
                'coupon_record_id'=> 0,
                'coupon_money'=> 0,
                'payment_method' => $paymentMethod->currentPaymentMethod,
            ]))throw new \Exception;
            $tradeId = Yii::$app->db->lastInsertId;
            Yii::$app->db->createCommand()->batchInsert(CustomUserTradeOrderAR::tableName(),[
                'custom_user_trade_id',
                'order_id',
            ],array_map(function($orderId)use($tradeId){
                array_unshift($orderId, $tradeId);
                return $orderId;
            }, $ordersId))->execute();
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            return self::errCallback($return, 'mysql');
        }
        return new Trade(['id' => $tradeId]);
    }

    public static function createZodiacTrade(Zodiac $zodiac, PaymentMethod $payment, $return = 'throw'){
        if(Yii::$app->user->isGuest)return Yii::$app->EC->callback($return, 'the user must login');
        if(!$payment->currentPaymentMethod)return Yii::$app->EC->callback($return, 'no payment method');
        if(!$zodiac->number)return Yii::$app->EC->callback($return, 'no selected number');
        if(!$totalFee = $zodiac->totalFee ? : 0)return Yii::$app->EC->callback($return, 'error total fee');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $tradeId = Yii::$app->RQ->AR(new CustomUserTradeAR)->insert([
                'custom_user_id' => Yii::$app->user->id,
                'type' => Trade::TYPE_ZODIAC,
                'total_fee' => $totalFee,
                'payment_method' => $payment->currentPaymentMethod,
            ]);
            $zodiacData = [];
            foreach($zodiac->number as $numberId){
                $zodiacData[] = [
                    $tradeId,
                    $numberId,
                ];
            }
            Yii::$app->db->createCommand()->batchInsert(
                CustomUserTradeZodiacAR::tableName(),
                [
                    'custom_user_trade_id',
                    'temp_youga_zodiac_number_id',
                ],
                $zodiacData
            )->execute();
            $transaction->commit();
            return new Trade(['id' => $tradeId]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'create zodiac trade failed');
        }
    }

    /**
     * 生成交易单
     * @param array $orders
     * @param $payment
     * @param $uid
     * @return Trade
     * @throws \Exception
     */
    public static function createMembraneTrade(array $orders, $payment, $uid)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $orderId = []; $totalFee = 0;

            /** @var MembraneOrder $order */
            foreach ($orders as $order){
                $orderId[] = $order->id;
                $totalFee += $order->getTotalFee();
            }
            $trade = new CustomUserTradeAR;
            $trade->custom_user_id = $uid;
            $trade->type = Trade::TYPE_MEMBRANE;
            $trade->order_total_fee = $trade->total_fee = $totalFee;
            $trade->payment_method = $payment;
            $trade->insert();

            Yii::$app->db->createCommand()->batchInsert(CustomUserTradeMembraneAR::tableName(), [
                'membrane_order_id',
                'custom_user_trade_id'
            ], array_map(function($id) use ($trade){
                return [ $id, $trade->id ];
            }, $orderId))->execute();
            $transaction->commit();
            return new Trade(['id' => $trade->id]);
        } catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    public static function createGpubsTrade(array $tickets, PaymentMethod $paymentMethod, $return = 'throw'){
        $customUserId = null;
        $totalFee = 0;
        $ticketIds = [];
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($tickets as $ticket){
                if(!($ticket instanceof \common\models\parts\gpubs\GpubsGroupTicket))throw new \Exception;
                if(is_null($customUserId)){
                    $customUserId = $ticket->custom_user_id;
                }else{
                    if($customUserId != $ticket->custom_user_id)throw new \Exception;
                }
                $totalFee += $ticket->total_fee;
                $ticketIds[] = $ticket->id;
            }
            if(is_null($customUserId) || $totalFee <= 0)throw new \Exception;
            $insertId = Yii::$app->RQ->AR(new CustomUserTradeAR)->insert([
                'custom_user_id' => $customUserId,
                'type' => Trade::TYPE_GPUBS,
                'total_fee' => $totalFee,
                'order_total_fee' => $totalFee,
                'payment_method' => $paymentMethod->currentPaymentMethod,
            ]);
            Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserTradeGpubsAR)->batchInsert([
                'custom_user_trade_id',
                'activity_gpubs_group_ticket_id',
            ], array_map(function($ticketId)use($insertId){
                return [$insertId, $ticketId];
            }, $ticketIds));
            $transaction->commit();
            return new Trade([
                'id' => $insertId,
            ]);
        }catch(\Exception $e){
            $transaction->commit();
            return Yii::$app->EC->callback($return, $e);
        }
    }
}
