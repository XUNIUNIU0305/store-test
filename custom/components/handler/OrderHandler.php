<?php
namespace custom\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\SupplyUserAR;
use common\models\RapidQuery;
use custom\models\parts\ItemInCart;
use common\models\parts\Order;
use common\models\parts\Address;
use custom\models\parts\OrderIdGenerator;
use common\models\parts\trade\WalletAbstract;
use common\models\parts\Product;
use common\ActiveRecord\OrderCustomizationAR;

class OrderHandler extends Handler{

    public static function cancel(Order $order, $return = 'throw'){
        /* 限制：只允许未付款和未发货的订单取消 */
        if($order->status != $order::STATUS_UNPAID && $order->status != $order::STATUS_UNDELIVER)return Yii::$app->EC->callback($return, 'error order status');
        /* 可能临时，可能长期 */
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $originalStatus = $order->status;
            $order->setStatus($order::STATUS_CANCELED, true);
            if($originalStatus == $order::STATUS_UNDELIVER){
                $customerWallet = new \custom\models\parts\trade\Wallet([
                    'userId' => $order->customerId,
                    'receiveType' => WalletAbstract::RECEIVE_ORDER_CANCELED,
                ]);
                $adminWallet = new \admin\models\parts\trade\Wallet;
                if(!$adminWallet->pay($order, $customerWallet))throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'cancel order failed');
        }
    }

    /**
     * 创建订单
     *
     * 所有商品的销售商必须是同一个
     *
     * @param array $items item对象
     * @param Object Address $address
     *
     * @return Object
     */
    public static function create(array $items, Address $address){
        if(!$items)return false;
        if(Yii::$app->user->isGuest)return false;
        $itemsData = [];
        $itemsSupplier = [];
        $orderFee = [];
        foreach($items as $item){
            if(!($item instanceof ItemInCart))return false;
            $attributes = array_map(function($attribute){
                return [
                    'attribute' => $attribute['name'],
                    'option' => $attribute['selectedOption']['name'],
                ];
            }, $item->attributes);
            $itemsData[] = [
                Yii::$app->user->id, //custom_user_id
                $itemsSupplier[] = $item->supplier, //supply_user_id
                $item->id, //product_sku_id
                $orderFee[] = $item->price * $item->count, //total_fee
                $item->title, //title
                serialize($attributes), //sku_attributes
                $item->productObj->customerLimit, //customer_limit
                $item->costPrice, //cost_price
                $item->price, //price
                $item->count, //count
                $item->mainImage->id, //oss_upload_file_id
                $item->customId, //custom_id
                $item->barCode, //bar_code
                $item->comments,//comments
            ];
        }

        if(count(array_unique($itemsSupplier, SORT_NUMERIC)) != 1)return false;
        $supplierId = (current($items))->supplier;
        $storeName = SupplyUserAR::findOne($supplierId)->store_name;
        $orderTotalFee = array_sum($orderFee);

        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!(new RapidQuery(new OrderAR))->insert([
                'order_number' => (new OrderIdGenerator)->id,
                'custom_user_id' => Yii::$app->user->id,
                'supply_user_id' => $supplierId,
                'store_name' => $storeName,
                'items_fee' => $orderTotalFee,
                'total_fee' => $orderTotalFee,
                'receive_consignee' => $address->consignee,
                'receive_address' => strval($address),
                'receive_mobile' => $address->mobile,
                'receive_postal_code' => $address->postalCode,
            ]))throw new \Exception;

            $orderId = Yii::$app->db->lastInsertId;
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
            foreach($items as $item){
                if(!$item->decreaseStock($item->count))throw new \Exception;
                if(!Yii::$app->CustomUser->cart->removeItem($item))throw new \Exception;
            }
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        return new Order(['id' => $orderId]);
    }

    public static function createCustomizeOrders(ItemInCart $item, Address $address){
        $attributes = serialize(array_map(function($attribute){
            return [
                'attribute' => $attribute['name'],
                'option' => $attribute['selectedOption']['name'],
            ];
        }, $item->attributes));
        $itemData = [
            'custom_user_id' => $item->userId,
            'supply_user_id' => $item->supplier,
            'product_sku_id' => $item->id,
            'total_fee' => $item->price,
            'title' => $item->title,
            'sku_attributes' => $attributes,
            'customer_limit' => $item->productObj->customerLimit,
            'cost_price' => $item->costPrice,
            'price' => $item->price,
            'count' => 1,
            'oss_upload_file_id' => $item->mainImage->id,
            'custom_id' => $item->customId,
            'bar_code' => $item->barCode,
            //'comments' => $item->comments,
        ];
        $transaction = Yii::$app->db->beginTransaction();
        $orderIds = [];
        try{
            for($i = 0; $i < $item->count; $i++){
                $orderNumber = (new OrderIdGenerator)->id;
                $orderId = Yii::$app->RQ->AR(new OrderAR)->insert([
                    'order_number' => $orderNumber,
                    'custom_user_id' => $item->userId,
                    'supply_user_id' => $item->supplier,
                    'store_name' => SupplyUserAR::findOne($item->supplier)->store_name,
                    'items_fee' => $item->price,
                    'total_fee' => $item->price,
                    'receive_consignee' => $address->consignee,
                    'receive_address' => strval($address),
                    'receive_mobile' => $address->mobile,
                    'receive_postal_code' => $address->postalCode,
                    'is_customization' => Product::TYPE_CUSTOMIZATION,
                ]);
                if(is_array($item->comments))$itemData['comments'] = $item->comments[$i];
                Yii::$app->RQ->AR(new OrderItemAR)->insert(array_merge(['order_id' => $orderId], $itemData));
                $orderIds[] = $orderId;
            }
            if(!$item->decreaseStock($item->count))throw new \Exception;
            if(!Yii::$app->CustomUser->cart->removeItem($item))throw new \Exception;
            $transaction->commit();
            return array_map(function($orderId){
                return new Order(['id' => $orderId]);
            }, $orderIds);
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 创建多个订单
     *
     * 根据销售商分组
     *
     * @param array $items item对象
     * @param Object Address $address
     *
     * @return array
     */
    public static function multiCreate(array $items, Address $address){
        if(!$items)return false;
        if(Yii::$app->user->isGuest)return false;
         $itemsGroupBySupplier = [];
        foreach($items as $key => $item){
            if(!($item instanceof ItemInCart))return false;
            $itemsGroupBySupplier[$item->supplier][] = $item;
        }
        $orders = [];
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($itemsGroupBySupplier as $itemsOfOneSupplier){
                foreach($itemsOfOneSupplier as $k => $oneKindOfItem){
                    if($oneKindOfItem->productObj->isCustomization){
                        if(!$customizeOrders = self::createCustomizeOrders($oneKindOfItem, $address))throw new \Exception;
                        $orders = array_merge($orders, $customizeOrders);
                        unset($itemsOfOneSupplier[$k]);
                    }
                }
                if(!empty($itemsOfOneSupplier)){
                    if(!$standardOrder = self::create($itemsOfOneSupplier, $address))throw new \Exception;
                    $orders[] = $standardOrder;
                }
            }
            $transaction->commit();
            return $orders;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
