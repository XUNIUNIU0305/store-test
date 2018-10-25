<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-25
 * Time: 下午3:43
 */

namespace common\models\parts\order;

use business\models\parts\Area;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\OrderProductRecordAR;
use common\models\Object;
use common\models\parts\Order;
use yii\base\InvalidCallException;
use Yii;

class OrderProductRecord extends Object
{
    public $id;
    public $order_id;

    protected $AR;

    public function init()
    {
        if ($this->order_id) {
            if (!$this->AR = OrderProductRecordAR::findOne(['order_id' => $this->order_id])) {
                throw new InvalidCallException();
            }
            $this->id = $this->AR->id;
        } elseif ($this->id) {
            if (!$this->id || !$this->AR = OrderProductRecordAR::findOne($this->id)) {
                throw new InvalidCallException();
            }
            $this->order_id = $this->AR->order_id;
        } else {
            throw new InvalidCallException('invalid id or order_id');
        }
    }

    public static function addRecord(Order $order, $return = 'throw')
    {
        /**
         * 只允许在订单状态为未发货的情况下调用
         */
        if ($order->status != $order::STATUS_UNDELIVER) {
            return Yii::$app->EC->callback($return, 'error order status');
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $data = [
                'custom_user_id' => $order->getCustomerId(),
                'order_id' => $order->id,
                'business_area_id' => CustomUserAR::findOne($order->getCustomerId())->business_area_id,
            ];
            $data['business_quaternary_area_id'] = (new Area(['id' => $data['business_area_id']]))->parent->id;
            $data['business_tertiary_area_id'] = (new Area(['id' => $data['business_quaternary_area_id']]))->parent->id;
            $data['business_secondary_area_id'] = (new Area(['id' => $data['business_tertiary_area_id']]))->parent->id;
            $data['business_top_area_id'] = (new Area(['id' => $data['business_secondary_area_id']]))->parent->id;

            $items = $order->getItems();
            foreach ($items as $item) {
                $item->id;
                $data['product_id'] = $item->getItem()->getProductId();
                $data['product_sku_id'] = $item->getItemId();
                $data['price'] = $item->price;
                $data['count'] = $item->getCount();
                $data['total_fee'] = $item->getTotalFee();
                $data['create_unixtime'] = time();
                if (!Yii::$app->RQ->AR(new OrderProductRecordAR())->insert($data)) {
                    throw new \Exception;
                }
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public static function deleteRecord(Order $order, $return = 'throw')
    {
        /**
         * 只允许在订单状态为取消的状态下调用
         */
        if ($order->status != $order::STATUS_CANCELED) {
            return Yii::$app->EC->callback($return, 'error order status');
        }
        try {
            if (OrderProductRecordAR::deleteAll(['order_id' => $order->id])) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

}
