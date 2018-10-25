<?php
namespace custom\models\parts\temp\OrderLimit;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use custom\models\parts\ItemInCart;
use common\models\parts\Order;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\CustomUserActivityLimitAR;
use custom\models\parts\temp\OrderLimit\activity\UniquePickIdGenerator;

class ActivityLimit extends Object{

    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;
    const UNEXCHANGE = 0;

    private $_activityLimit;

    private $_suppliers = [];

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        try{
            $this->_activityLimit = include(__DIR__ . '/activity.php');
        }catch(\Exception $e){
            $this->_activityLimit = [];
        }
        if($this->_activityLimit){
            $productIds = array_keys($this->_activityLimit);
            $this->_suppliers = Yii::$app->RQ->AR(new ProductAR)->column([
                'select' => ['supply_user_id'],
                'where' => ['id' => $productIds],
            ]);
        }
    }

    public function setOrderId(array $insertIds, Order $order){
        return CustomUserActivityLimitAR::updateAll([
            'order_id' => $order->id,
        ], [
            'id' => $insertIds,
        ]);
    }

    public static function setPaid(Order $order){
        return CustomUserActivityLimitAR::updateAll([
            'paid' => self::STATUS_PAID,
            'pay_datetime' => Yii::$app->time->fullDate,
            'pay_unixtime' => Yii::$app->time->unixTime,
        ], [
            'order_id' => $order->id,
        ]);
    }

    public function getSuppliers(){
        return $this->_suppliers;
    }

    public function isLimitProduct(ItemInCart $itemInCart){
        return isset($this->_activityLimit[$itemInCart->productId]);
    }

    public function getHasActivity(){
        return empty($this->_activityLimit) ? false : true;
    }

    public function addBought(ItemInCart $itemInCart, $return = 'throw'){
        $pickIdGenerator = new UniquePickIdGenerator;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $insertIds = [];
            for($i = 0; $i < $itemInCart->count; $i++){
                $insertIds[] = Yii::$app->RQ->AR(new CustomUserActivityLimitAR)->insert([
                    'pick_id' => $pickIdGenerator->getId(),
                    'custom_user_id' => $itemInCart->getUserId(),
                    'product_id' => $itemInCart->productId,
                    'quantity' => 1,
                    'order_datetime' => Yii::$app->time->fullDate,
                    'order_unixtime' => Yii::$app->time->unixTime,
                ]);
            }
            $transaction->commit();
            return $insertIds;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function validate(ItemInCart $itemInCart, $return = 'throw'){
        if(!$this->isLimitProduct($itemInCart))return true;
        if(!$this->validateTime($itemInCart))return Yii::$app->EC->callback($return, 'incorrect time');
        if(!$this->validateProductQuantity($itemInCart))return Yii::$app->EC->callback($return, 'incorrect product quantity');
        return true;
    }

    public function validateTime(ItemInCart $itemInCart){
        if(!$this->isLimitProduct($itemInCart))return true;
        $timeStart = $this->_activityLimit[$itemInCart->productId]['time'][0];
        $timeEnd = $this->_activityLimit[$itemInCart->productId]['time'][1];
        return (Yii::$app->time->unixTime >= $timeStart && Yii::$app->time->unixTime <= $timeEnd);
    }

    public function getLimitTime(ItemInCart $itemInCart){
        if(!$this->isLimitProduct($itemInCart)){
            return [
                'start' => '',
                'end' => '',
            ];
        }
        $timeStart = $this->_activityLimit[$itemInCart->productId]['time'][0];
        $timeEnd = $this->_activityLimit[$itemInCart->productId]['time'][1];
        return [
            'start' => date('Y-m-d', $timeStart),
            'end' => date('Y-m-d', $timeEnd),
        ];
    }

    public function validateProductQuantity(ItemInCart $itemInCart){
        if(!$this->isLimitProduct($itemInCart))return true;
        $limitQuantity = $this->_activityLimit[$itemInCart->productId]['limit'];
        $timeStart = $this->_activityLimit[$itemInCart->productId]['time'][0];
        $timeEnd = $this->_activityLimit[$itemInCart->productId]['time'][1];
        $hasBoughtQuantity = CustomUserActivityLimitAR::find()->
            where([
                'custom_user_id' => $itemInCart->getUserId(),
                'product_id' => $itemInCart->productId,
            ])->
            andWhere(['>=', 'order_unixtime', $timeStart])->
            andWhere(['<=', 'order_unixtime', $timeEnd])->
            sum('quantity') ? : 0;
        return (($hasBoughtQuantity + $itemInCart->count) <= $limitQuantity);
    }

    public function getHasBoughtQuantity(ItemInCart $itemInCart){
        if(!$this->isLimitProduct($itemInCart))return 0;
        $timeStart = $this->_activityLimit[$itemInCart->productId]['time'][0];
        $timeEnd = $this->_activityLimit[$itemInCart->productId]['time'][1];
        return CustomUserActivityLimitAR::find()->
            where([
                'custom_user_id' => $itemInCart->getUserId(),
                'product_id' => $itemInCart->productId,
            ])->
            andWhere(['>=', 'order_unixtime', $timeStart])->
            andWhere(['<=', 'order_unixtime', $timeEnd])->
            sum('quantity') ? : 0;
    }

    public function getLimitQuantity(ItemInCart $itemInCart){
        if(!$this->isLimitProduct($itemInCart))return 0;
        return $this->_activityLimit[$itemInCart->productId]['limit'];
    }
}
