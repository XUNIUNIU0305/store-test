<?php
namespace console\models\groupbuy;

use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\ProductSKUAR;
use common\ActiveRecord\ActivityGroupbuyAR;
use common\ActiveRecord\ActivityGroupbuyPriceAR;
use common\ActiveRecord\ActivityGroupbuyOrderAR;
use common\ActiveRecord\ActivityGroupbuyLogAR;
use console\models\groupbuy\exceptions;
use Yii;

class GroupbuyOrder
{
    public function isGroupbuyProduct($productId)
    {
        return ActivityGroupbuyAR::find()
                ->where(['product_id' => $productId])
                ->exists();
    }

    public function setDbInto()
    {
        foreach(func_get_args() as $object) {
            if(method_exists($object, 'setDb')) {
                $object->setDb($this);
            }
        }
        return $this;
    }
    
    public function getAllGroupbuy()
    {
        return ActivityGroupbuyAR::find()
                ->select(['id'])
                ->asArray()
                ->all();
    }

    public function getOrderProduct($orderId)
    {
        $productSku     = ProductSKUAR::getTableSchema()->fullName;
        $orderItem      = OrderItemAR::getTableSchema()->fullName;
        $groupbuyOrder  = ActivityGroupbuyOrderAR::getTableSchema()->fullName;
        
        return OrderItemAR::find()
            ->select([
                "$productSku.product_id",
                "$orderItem.count",
            ])
            ->where(["$orderItem.order_id" => $orderId])
            ->leftjoin($productSku, "`$orderItem` . `product_sku_id` = `$productSku` . `id`")
            ->asArray()
            ->all();

    }
    
    public function getOrderPrice($orderId)
    {      
        $orders =  OrderItemAR::find()
            ->select([
                'product_sku_id',
                'order_id',
                'price',
                'count',
            ])
            ->where(["order_id" => $orderId])
            ->asArray()
            ->all();

        if(!$orders) {
            return null;
        }
        
        foreach($orders as $order) {
            $validGroupbuyId = $this->getValidGroupbuyId($order['product_sku_id']);
            if(ActivityGroupbuyPriceAR::find()->where(['product_sku_id' => $order['product_sku_id'], 'groupbuy_id' => $validGroupbuyId])->exists()) {
                $groupbuyOrder[] = $order;
            }
        }
        
        return $groupbuyOrder;
    }

    public function getValidGroupbuyId($skuId)
    {
        foreach(ActivityGroupbuyPriceAR::find()->select(['groupbuy_id'])->where(['product_sku_id' => $skuId])->asArray()->all() as $v) {
            if(ActivityGroupbuyAR::find()->where(['id' => $v['groupbuy_id'], 'status' => 1])->exists()) {
                return $v['groupbuy_id'];
            }
        }
    }

    public function getAllOrder()
    {
        return ActivityGroupbuyOrderAR::find()
                ->asArray()
                ->all();
    }
    
    public function getAllOrderToRefund()
    {
        return ActivityGroupbuyOrderAR::find()
                ->where(['status' => 0])
                ->asArray()
                ->all();
    }
    
    public function markRefunded($id, $refund)
    {
        
        $groupbuyOrder = ActivityGroupbuyOrderAR::findOne($id);
        if(!$groupbuyOrder) {
            return false;
        }
        $groupbuyOrder->status = 1;
        $groupbuyOrder->cash_back_amount = $refund;
        $groupbuyOrder->cash_back_timestrap = time();
        $groupbuyOrder->cash_back_datetime = date('Y-m-d H:i:s');
        $groupbuyOrder->save();
        return true;
    }

    public function comment($id, $comment)
    {
        
        $groupbuyOrder = ActivityGroupbuyOrderAR::findOne($id);
        if(!$groupbuyOrder) {
            return false;
        }
        $groupbuyOrder->comment = $comment;
        $groupbuyOrder->save();
        return true;
    }
    
    public function getCurrentPrice($sku)
    {
        $validGroupbuyId = $this->getValidGroupbuyId($sku);
        return ActivityGroupbuyPriceAR::find()
                ->select(['final_price'])
                ->where(['product_sku_id' => $sku, 'groupbuy_id' => $validGroupbuyId, 'calculated' => 1])
                ->asArray()
                ->one();
    }
    
    public function updateGroupbuyCurrentPrice()
    {
        $groupbuy = ActivityGroupbuyAR::find()
            ->select(['id', 'achieve_sales', 'first_gradient_sales_goals', 'second_gradient_sales_goals', 'third_gradient_sales_goals'])
            ->where(['status' => 1])
            ->asArray()
            ->all();

        foreach($groupbuy as $v) {
            $grad = $this->getGradient($v['achieve_sales'], $v['first_gradient_sales_goals'], $v['second_gradient_sales_goals'], $v['third_gradient_sales_goals']);
            $gradient[] = [$v['id'], $grad];
        }

        return $this->batchUpdateGroupbuyPriceGradient($gradient);
    }
    
    protected function getGradient($current, $first, $second, $third)
    {
        if($current  < $first) {
            return 0;
        } elseif($current  < $second) {
            return 1;
        } elseif($current >= $second && $current < $third) {
            return 2;
        } elseif($current >= $third) {
            return 3;
        }
        return 0;
    }
            
    public function batchUpdateGroupbuyProductSalesCount(array $salesCountArray)
    {
        if(empty($salesCountArray)) {
            return 0;
        }
        
        foreach($salesCountArray as $k => $v) {
            $groupbuy = ActivityGroupbuyAR::findOne(['product_id' => $k, 'status' => 1]);
            $groupbuy->achieve_sales = $v;
            $groupbuy->save();
        }
        
        return count($salesCountArray);
    }
    
    protected function batchUpdateGroupbuyPriceGradient(array $gradients)
    {
        if(empty($gradients)) {
            return 0;
        }
        
        $count = 0;
        foreach($gradients as $v) {
            $groupbuySkus = ActivityGroupbuyPriceAR::findAll(['groupbuy_id' => $v[0]]);
            foreach($groupbuySkus as $groupbuySku) {
                if(!$groupbuySku) {
                    continue;
                }
                $groupbuySku->final_price = $this->getGroupFinalPrice($groupbuySku, $gradients);
                $groupbuySku->calculated = 1;
                $groupbuySku->save();
                $count++;
            }
        }
        
        return $count;
    }
    
    protected function getGroupFinalPrice($groupbuySku, $gradients)
    {
        foreach($gradients as $gradient) {
            if($gradient[0] == $groupbuySku->groupbuy_id) {
                $gradientLevel = (int)$gradient[1];
            }
        }
        
        if(!isset($gradientLevel)){
            return $groupbuySku->first_gradient_price;
        }
            
        switch ($gradientLevel) {
            case 0:
                return -1;
            case 1:
                return $groupbuySku->first_gradient_price;
            case 2:
                return $groupbuySku->second_gradient_price;
            case 3:
                return $groupbuySku->third_gradient_price;
            default:
                return $groupbuySku->first_gradient_price;
        }
    }
}
