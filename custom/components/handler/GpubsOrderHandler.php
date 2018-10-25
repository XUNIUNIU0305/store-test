<?php
namespace custom\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\SupplyUserAR;
use common\models\RapidQuery;
use common\models\parts\Item;
use common\models\parts\Order;
use custom\models\parts\OrderIdGenerator;

class GpubsOrderHandler extends Handler{
    public static function create(array $items, $detail){
        if(!$items)return false;
        $itemsData = [];
        $itemsSupplier = [];
        $orderFee = [];
        foreach($items as $item){
            if(!($item instanceof Item))return false;
            $attributes = array_map(function($attribute){
                return [
                    'attribute' => $attribute['name'],
                    'option' => $attribute['selectedOption']['name'],
                ];
            }, $item->attributes);
            $itemsData[] = [
                $detail->custom_user_id,
                $itemsSupplier[] = $item->supplier,
                $item->id,
                $orderFee[] = $detail->quantity * $detail->product_sku_price,
                $item->title,
                serialize($attributes),
                $item->productObj->customerLimit,
                $item->costPrice,
                $detail->product_sku_price,
                $detail->quantity,
                $item->mainImage->id,
                $item->customId,
                $item->barCode,
                $detail->comment,
            ];
        }
        if(count(array_unique($itemsSupplier, SORT_NUMERIC)) != 1)return false;
        $supplierId = (current($items))->supplier;
        $storeName = SupplyUserAR::findOne($supplierId)->store_name;
        $orderTotalFee = array_sum($orderFee);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!(new RapidQuery(new OrderAR))->insert([
                'order_number' =>  (new OrderIdGenerator)->id,
                'custom_user_id' => $detail->custom_user_id,
                'supply_user_id' => $supplierId,
                'store_name' => $storeName,
                'items_fee' => $orderTotalFee,
                'total_fee' => $orderTotalFee,
                'receive_consignee' => $detail->consignee,
                'receive_address' => $detail->full_address,
                'receive_mobile' => $detail->mobile,
                'receive_postal_code' => $detail->postal_code,
                'status' => 1,
                'pay_datetime' => $detail->join_datetime,
                'pay_unixtime' => $detail->join_unixtime,
            ]))throw new \Exception;
            $orderId = Yii::$app->db->lastInsertId;
            Yii::$app->RQ->AR(OrderAR::findOne($orderId))->update([
                'pay_datetime' => $detail->join_datetime,
            ]);
            Yii::$app->db->createCommand()->batchInsert(OrderItemAR::tableName(), [
                'order_id',
                'custom_user_id',
                'supply_user_id',
                'product_sku_id',
                'total_fee',
                'title',
                'sku_attributes',
                'customer_limit',
                'cost_price',
                'price',
                'count',
                'oss_upload_file_id',
                'custom_id',
                'bar_code',
                'comments',
            ], array_map(function($data)use($orderId){
                array_unshift($data, $orderId);
                return $data;
            }, $itemsData))->execute();

            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        return new Order(['id' => $orderId]);
    }
}
