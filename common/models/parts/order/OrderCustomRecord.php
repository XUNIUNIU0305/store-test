<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-25
 * Time: 下午2:33
 */

namespace common\models\parts\order;

use business\models\parts\Area;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\OrderCustomRecordAR;
use common\models\Object;
use common\models\parts\Order;
use yii\base\InvalidCallException;
use Yii;

class OrderCustomRecord extends Object
{
    public $id;
    public $order_id;

    protected $AR;

    public function init()
    {
        if ($this->order_id) {
            if (!$this->AR = OrderCustomRecordAR::findOne(['order_id' => $this->order_id])) {
                throw new InvalidCallException();
            }
            $this->id = $this->AR->id;
        } elseif ($this->id) {
            if (!$this->id || !$this->AR = OrderCustomRecordAR::findOne($this->id)) {
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
        try {
            $data = [
                'custom_user_id' => $order->getCustomerId(),
                'order_id' => $order->id,
                'business_area_id' => CustomUserAR::findOne($order->getCustomerId())->business_area_id,
                'rmb' => $order->getTotalFee()
            ];
            $data['business_quaternary_area_id'] = (new Area(['id' => $data['business_area_id']]))->parent->id;
            $data['business_tertiary_area_id'] = (new Area(['id' => $data['business_quaternary_area_id']]))->parent->id;
            $data['business_secondary_area_id'] = (new Area(['id' => $data['business_tertiary_area_id']]))->parent->id;
            $data['business_top_area_id'] = (new Area(['id' => $data['business_secondary_area_id']]))->parent->id;
            $data['create_unixtime'] = time();
            if (Yii::$app->RQ->AR(new OrderCustomRecordAR)->insert($data)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
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
            if (OrderCustomRecordAR::deleteAll(['order_id' => $order->id])) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
     }


}
