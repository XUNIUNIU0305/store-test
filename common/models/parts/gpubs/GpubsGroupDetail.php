<?php
namespace common\models\parts\gpubs;

use common\ActiveRecord\OrderAR;
use common\models\parts\custom\CustomUser;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidCallException;
use common\models\ObjectAbstract;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\ActiveRecord\ActivityGpubsGroupDetailPickLogAR;
use common\models\parts\Product;
use common\models\parts\Order;

class GpubsGroupDetail extends ObjectAbstract{

    const STATUS_CANCELED = 0;
    const STATUS_WAIT = 1;
    const STATUS_UNPICK = 2;
    const STATUS_PICKED_PART = 3;
    const STATUS_PICKED_ALL = 4;
    const TYPE_ALL = 0;//所有 (含自提送货)


    public $id;
    public $detailNumber;

    private static $_pickingUpNumberCount;
    private $_group;
    private $_product;
    private $_custom;
    private $_order;

    public function init(){
        if($this->id){
            $this->AR = ActivityGpubsGroupDetailAR::findOne($this->id);
        }elseif($this->groupNumber){
            $this->AR = ActivityGpubsGroupDetailAR::findOne([
                'detail_number' => $this->detailNumber,
            ]);
        }
        if($this->AR){
            $this->id = $this->AR->id;
            $this->detailNumber = $this->AR->detail_number;
        }else{
            throw new InvalidConfigException;
        }
    }

    protected function _gettingList() : array{
        return [
            'detail_number',
            'group_number',
            'order_number',
            'activity_gpubs_product_id',
            'activity_gpubs_group_id',
            'activity_gpubs_product_sku_id',
            'custom_user_id',
            'custom_user_account',
            'is_owner',
            'own_user_id',
            'product_id',
            'product_title',
            'product_image_filename',
            'product_sku_id',
            'sku_attributes',
            'quantity',
            'product_sku_price',
            'total_fee',
            'comment',
            'picked_up_quantity',
            'picking_up_number',
            'join_datetime',
            'join_unixtime',
            'cancel_datetime',
            'cancel_unixtime',
            'status',
            'gpubs_type',
            'full_address',
            'postal_code',
            'consignee',
            'mobile',
            'pay_method',
        ];
    }

    protected function _settingList() : array{
        return [];
    }

    public function getSkuAttributes(){
        return unserialize($this->AR->sku_attributes);
    }

    public function setStatus(int $status, $return = 'throw'){
        switch($status){
        case self::STATUS_CANCELED:
            if($this->AR->status != self::STATUS_WAIT)return Yii::$app->EC->callback($return, 'incorrect status');
            return $this->setCanceled($return);
            break;

        case self::STATUS_WAIT:
            return Yii::$app->EC->callback($return, 'unable to change to this status');
            break;

        case self::STATUS_UNPICK:
            if($this->AR->status != self::STATUS_WAIT)return Yii::$app->EC->callback($return, 'incorrect status');
            return $this->setUnpick($return);
            break;

        case self::STATUS_PICKED_PART:
            if($this->AR->status != self::STATUS_UNPICK && $this->AR->status != self::STATUS_PICKED_PART)return Yii::$app->EC->callback($return, 'incorrect status');
            return $this->setPickedPart($return);
            break;

        case self::STATUS_PICKED_ALL:
            if($this->AR->status != self::STATUS_PICKED_PART)return Yii::$app->EC->callback($return, 'incorrect status');
            return $this->setPickedAll($return);
            break;

        default:
            throw new InvalidCallException;
        }
    }

    public function setPickedPart($return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'status' => self::STATUS_PICKED_PART,
            'last_pick_up_datetime' => Yii::$app->time->fullDate,
            'last_pick_up_unixtime' => Yii::$app->time->unixTime,
        ]);
    }
    public function setOrderNumber($order){
        return Yii::$app->RQ->AR($this->AR)->update([
            'order_number' => $order,
        ]);
    }
    public function setPickedAll($return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'status' => self::STATUS_PICKED_ALL,
        ]);
    }

    public function getGroup(){
        if(is_null($this->_group)){
            $this->_group = new GpubsGroup([
                'id' =>  $this->AR->activity_gpubs_group_id,
            ]);
        }
        return $this->_group;
    }

    public function getProduct(){
        if(is_null($this->_product)){
            $this->_product = new Product([
                'id' => $this->AR->product_id,
            ]);
        }
        return $this->_product;
    }

    public function pick(int $pickingUpNumber, int $quantity, $return = 'throw'){
        if($quantity <= 0)return Yii::$app->EC->callback($return, 'incorrect quantity');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $lineData = Yii::$app->db->createCommand("SELECT * FROM {{%activity_gpubs_group_detail}} WHERE [[id]] = :id FOR UPDATE")->bindValues([
                ':id' => $this->AR->id,
            ])->queryOne();
            if($lineData['picking_up_number'] != $pickingUpNumber)throw new \Exception;
            if(($unpickQuantity = $lineData['quantity'] - $lineData['picked_up_quantity']) < $quantity)throw new \Exception;
            Yii::$app->RQ->AR(new ActivityGpubsGroupDetailPickLogAR)->insert([
                'activity_gpubs_group_detail_id' => $this->AR->id,
                'unpicked_quantity' => $this->AR->quantity - $this->AR->picked_up_quantity,
                'quantity_to_pick' => $quantity,
                'picking_up_number' => $pickingUpNumber,
                'picking_up_datetime' => Yii::$app->time->fullDate,
                'picking_up_unixtime' => Yii::$app->time->unixTime,
            ]);
            $result = Yii::$app->db->createCommand("UPDATE {{%activity_gpubs_group_detail}} SET [[picked_up_quantity]] = [[picked_up_quantity]] + :quantity WHERE [[id]] = :id")->bindValues([
                ':quantity' => $quantity,
                ':id' => $this->AR->id,
            ])->execute();
            if(!$result)throw new \Exception;
            $this->AR = ActivityGpubsGroupDetailAR::findOne($this->AR->id);
            $this->setStatus(self::STATUS_PICKED_PART);
            if($quantity < $unpickQuantity){
                $this->freshPickingUpNumber();
            }else{
                $this->setStatus(self::STATUS_PICKED_ALL);
            }
            $this->group->pick($quantity);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function setUnpick($return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $this->freshPickingUpNumber();
            Yii::$app->RQ->AR($this->AR)->update([
                'status' => self::STATUS_UNPICK,
            ]);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function setCanceled($return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR($this->AR)->update([
                'cancel_unixtime' => time(),
                'cancel_datetime' => date('Y-m-d H:i:s'),
                'status' => self::STATUS_CANCELED,
            ]);
            $customerWallet = new \custom\models\parts\trade\Wallet([
                'userId' => $this->AR->custom_user_id,
                'receiveType' => \common\models\parts\trade\WalletAbstract::RECEIVE_GPUBS_ORDER,
            ]);
            $adminWallet = new \admin\models\parts\trade\Wallet;
            if(!$adminWallet->pay($this, $customerWallet))throw new \Exception;
            Yii::$app->db->createCommand("SELECT * FROM {{%activity_gpubs_product_sku}} WHERE [[id]] = :id FOR UPDATE")->bindValues([
                ':id' => $this->AR->activity_gpubs_product_sku_id,
            ])->queryOne();
            $result = Yii::$app->db->createCommand("UPDATE {{%activity_gpubs_product_sku}} SET [[stock]] = [[stock]] + :quantity WHERE [[id]] = :id")->bindValues([
                ':quantity' => $this->AR->quantity,
                ':id' => $this->AR->activity_gpubs_product_sku_id,
            ])->execute();
            if(!$result)throw new \Exception;
            $this->AR = ActivityGpubsGroupDetailAR::findOne($this->AR->id);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function freshPickingUpNumber($return = 'throw'){
        $newNumber = $this->achievePickingUpNumber();
        return Yii::$app->RQ->AR($this->AR)->update([
            'picking_up_number' => $newNumber,
        ]);
    }

    protected function achievePickingUpNumber(){
        $count = $this->getPickingUpNumberCount();
        $maxCount = 0;
        for($length = 4; $maxCount < $count; ++$length){
            $maxCount = 10 ** $length * 0.3;
        }
        do{
            $number = rand(10 ** $length, intval(str_repeat('9', $length + 1)));
        }while(ActivityGpubsGroupDetailAR::findOne([
            'status' => [self::STATUS_UNPICK, self::STATUS_PICKED_PART],
            'picking_up_number' => $number,
        ]));
        return $number;
    }

    public function getPickingUpNumberCount(){
        if(is_null(static::$_pickingUpNumberCount)){
            static::$_pickingUpNumberCount = Yii::$app->RQ->AR(new ActivityGpubsGroupDetailAR)->count([
                'select' => ['id'],
                'where' => [
                    'status' => [self::STATUS_UNPICK, self::STATUS_PICKED_PART],
                ],
            ]);
        }
        return static::$_pickingUpNumberCount;
    }

    /**
     * 获取订单所对应的用户
     * @return CustomUser
     */
    public function getCustomUser()
    {
        if(is_null($this->_custom)){
            $this->_custom = new CustomUser([
                'id' =>  $this->AR->custom_user_id,
            ]);
        }
        return $this->_custom;
    }

    public function getOrder(){
        if(is_null($this->_order)){
            if($this->order_number){
                $this->_order = new Order([
                    'orderNumber' => $this->order_number,
                ]);
            }else{
                $this->_order = false;
            }
        }
        return $this->_order;
    }
}
