<?php
namespace common\models\parts\gpubs;

use common\ActiveRecord\ActivityGpubsGroupTicketAR;
use common\models\parts\Product;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidCallException;
use common\models\ObjectAbstract;
use common\ActiveRecord\ActivityGpubsGroupAR;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\models\parts\custom\CustomUser;
use custom\components\handler\GpubsOrderHandler;
use common\models\parts\coupon\CouponRecord;

class GpubsGroup extends ObjectAbstract{

    const STATUS_UNPAID = 0;
    const STATUS_WAIT = 1;
    const STATUS_ESTABLISH = 2;
    const STATUS_CANCELED = 3;
    const STATUS_DELIVERED = 4;
    const STATUS_CONFIRMED = 5;


    public $id;
    public $groupNumber;

    private $_gpubsProduct;
    private $_customUser;
    private $_product;


    public function init(){
        if($this->id){
            $this->AR = ActivityGpubsGroupAR::findOne($this->id);
        }elseif($this->groupNumber){
            $this->AR = ActivityGpubsGroupAR::findOne([
                'group_number' => $this->groupNumber,
            ]);
        }
        if($this->AR){
            $this->id = $this->AR->id;
            $this->groupNumber = $this->AR->group_number;
        }else{
            throw new InvalidConfigException;
        }
    }

    protected function _gettingList() : array{
        return [
            'activity_gpubs_product_id',
            'custom_user_id',
            'supply_user_id',
            'group_start_datetime',
            'group_start_unixtime',
            'group_end_datetime',
            'group_end_unixtime',
            'group_establish_datetime',
            'group_establish_unixtime',
            'group_cancel_datetime',
            'group_cancel_unixtime',
            'group_deliver_datetime',
            'group_deliver_unixtime',
            'target_quantity',
            'present_quantity',
            'total_fee',
            'district_province_id',
            'district_city_id',
            'district_district_id',
            'detailed_address',
            'mobile',
            'consignee',
            'full_address',
            'status',
            'picked_up_quantity',
            'gpubs_type',
            'gpubs_rule_type',
            'target_member',
            'present_member',
            'group_number',
            'spot_name',
            'postal_code',
            'min_quantity_per_member_of_group',
        ];
    }

    protected function _settingList() : array{
        return [];
    }

    public function getDeliverTime($unixTime = false)
    {
        return $unixTime ? $this->AR->group_deliver_datetime : ($this->AR->group_deliver_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->group_deliver_datetime);
    }

    public function getGpubsProduct(){
        if(is_null($this->_gpubsProduct)){
            $this->_gpubsProduct = new GpubsProduct([
                'id' => $this->AR->activity_gpubs_product_id,
            ]);
        }
        return $this->_gpubsProduct;
    }
    public function getProduct(){
        if(is_null($this->_product)){
            $this->_product = new Product([
                'id' => $this->getGpubsProduct()->product_id,
            ]);
        }
        return $this->_product;
    }

    public function getCustomUser(){
        if(is_null($this->_customUser)){
            $this->_customUser = new CustomUser([
                'id' => $this->AR->custom_user_id,
            ]);
        }
        return $this->_customUser;
    }

    public function getDetail(){
        $detailIds = Yii::$app->RQ->AR(new ActivityGpubsGroupDetailAR)->column([
            'select' => ['id'],
            'where' => [
                'activity_gpubs_group_id' => $this->AR->id,
            ],
        ]);
        return array_map(function($id){
            return new GpubsGroupDetail([
                'id' => $id,
            ]);
        }, $detailIds);
    }

    public function setStatus(int $status, $return = 'throw'){
        switch($status){
        case self::STATUS_UNPAID:
            return Yii::$app->EC->callback($return, 'unable to change to this status');
            break;

        case self::STATUS_WAIT:
            if($this->AR->status != self::STATUS_UNPAID){
                return Yii::$app->EC->callback($return, 'incorrect status');
            }
            return $this->setWait($return);
            break;

        case self::STATUS_ESTABLISH:
            if($this->AR->status != self::STATUS_WAIT){
                return Yii::$app->EC->callback($return, 'incorrect status');
            }
            switch($this->gpubs_type){
                case GpubsProduct::GPUBS_TYPE_INVITE:
                    if($this->AR->target_quantity > $this->AR->present_quantity){
                        return Yii::$app->EC->callback($return, 'not enough quantity');
                    }
                    break;

                case GpubsProduct::GPUBS_TYPE_DELIVER:
                    switch($this->gpubs_rule_type){
                        case GpubsProduct::STATUS_GPUBS_RULE_MEMBER:
                        case GpubsProduct::STATUS_GPUBS_PRE_NUMBER:
                            if($this->AR->target_member > $this->AR->present_member){
                                return Yii::$app->EC->callback($return, 'not enough member');
                            }
                            break;

                        case GpubsProduct::STATUS_GPUBS_RULE_NUMBER:
                            if($this->AR->target_quantity > $this->AR->present_quantity){
                                return Yii::$app->EC->callback($return, 'not enough quantity');
                            }
                            break;

                        default:
                            return Yii::$app->EC->callback($return, 'unavailable type');
                    }
                    break;

                default:
                    return Yii::$app->EC->callback($return, 'unavailable type');
            }
            return $this->setEstablished($return);
            break;

        case self::STATUS_CANCELED:
            if(!in_array($this->AR->status, [self::STATUS_UNPAID, self::STATUS_WAIT])){
                return Yii::$app->EC->callback($return, 'incorrect status');
            }
            return $this->setCanceled($return);
            break;

        case self::STATUS_DELIVERED:
            if(!in_array($this->AR->status, [self::STATUS_ESTABLISH])){
                return Yii::$app->EC->callback($return, 'incorrect status');
            }
            return $this->setDelivered($return);
            break;

        default:
            throw new InvalidCallException;
        }
    }

    public function setWait($return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'status' => self::STATUS_WAIT,
        ], $return);
    }

    public function setDelivered($return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'group_deliver_datetime' => Yii::$app->time->fullDate,
            'group_deliver_unixtime' => Yii::$app->time->unixTime,
            'status' => self::STATUS_DELIVERED,
        ], $return);
    }

    public function setEstablished($return = 'throw'){
        $totalFee = Yii::$app->RQ->AR(new ActivityGpubsGroupDetailAR)->sum([
            'where' => [
                'activity_gpubs_group_id' => $this->id,
            ],
        ], 'total_fee');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($this->detail as $detail){
                $detail->freshPickingUpNumber();
                $detail->setStatus(GpubsGroupDetail::STATUS_UNPICK);
            }
            Yii::$app->RQ->AR($this->AR)->update([
                'group_establish_datetime' => Yii::$app->time->fullDate,
                'group_establish_unixtime' => Yii::$app->time->unixTime,
                'total_fee' => $totalFee,
                'status' => self::STATUS_ESTABLISH,
            ], $return);
            if($this->gpubs_type == GpubsProduct::GPUBS_TYPE_DELIVER){
                if(!$this->createOrder())throw new \Exception;
            }
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
            foreach($this->detail as $detail){
                $detail->setStatus(GpubsGroupDetail::STATUS_CANCELED);
            }
            Yii::$app->RQ->AR($this->AR)->update([
                'group_cancel_datetime' => Yii::$app->time->fullDate,
                'group_cancel_unixtime' => Yii::$app->time->unixTime,
                'status' => self::STATUS_CANCELED,
            ]);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }
    private function createItem($detail){
        $items=[];
            $product = new product(['id'=>$detail->product_id]);
            if ($product->getCustomization() == Product::TYPE_STANDARD) {
                $items[$product->getSupplier()][Product::TYPE_STANDARD][$detail->product_sku_id] = $detail->comment;
                $items[$product->getSupplier()][Product::TYPE_STANDARD]['ticket'] = '';
            } elseif ($product->getCustomization() == Product::TYPE_CUSTOMIZATION) {
                $items[$product->getSupplier()][Product::TYPE_CUSTOMIZATION][$detail->product_sku_id][0]['comment'] = $detail->comment;
                $items[$product->getSupplier()][Product::TYPE_CUSTOMIZATION][$detail->product_sku_id][0]['ticket'] = '';
            } else {
                throw new \Exception;
            }
        return $items;
    }
    public function createOrder(){
        $details = $this->getDetail();
       foreach ($details as $d){
            $item = $this->createItem($d);
            if (!$order = $this->multiCreate($item, $d)) {
                return false;
            }else{
                $d->setOrderNumber($order[0]->getOrderNo());
            }
        }
        return true;
    }

    public function join(GpubsGroupDetail $detail, $return = 'throw'){
        if(!$this->canJoin())return Yii::$app->EC->callback($return, 'unable to join this group');
        if($detail->activity_gpubs_group_id != $this->AR->id)return Yii::$app->EC->callback($return, 'unmatch with group and detail');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $lineData = Yii::$app->db->createCommand("SELECT * FROM {{%activity_gpubs_group}} WHERE [[id]] = :id FOR UPDATE")->bindValues([
                ':id' => $this->AR->id,
            ])->queryOne();
            $result = Yii::$app->db->createCommand("UPDATE {{%activity_gpubs_group}} SET [[present_quantity]] = [[present_quantity]] + :quantity, [[present_member]] = [[present_member]] + 1 WHERE [[id]] = :id")->bindValues([
                ':quantity' => $detail->quantity,
                ':id' => $this->AR->id,
            ])->execute();

            if($result){
                $this->AR = ActivityGpubsGroupAR::findOne($this->AR->id);
                if($this->AR->status == self::STATUS_UNPAID){
                    $this->setStatus(self::STATUS_WAIT);
                }
                switch ($lineData['gpubs_type']){
                    case GpubsProduct::GPUBS_TYPE_INVITE:
                        if ($lineData['present_quantity']  + $detail->quantity >= $this->AR->target_quantity){
                            $this->setStatus(self::STATUS_ESTABLISH);
                        }
                        break;

                    case GpubsProduct::GPUBS_TYPE_DELIVER:
                        switch ($lineData['gpubs_rule_type']){
                            case GpubsProduct::STATUS_GPUBS_RULE_MEMBER:
                            case GpubsProduct::STATUS_GPUBS_PRE_NUMBER:
                                if($lineData['present_member'] + 1 >= $this->AR->target_member){
                                    $this->setStatus(self::STATUS_ESTABLISH);
                                }
                                break;

                            case GpubsProduct::STATUS_GPUBS_RULE_NUMBER:
                                if($lineData['present_quantity'] + $detail->quantity >= $this->AR->target_quantity){
                                    $this->setStatus(self::STATUS_ESTABLISH);
                                }
                                break;

                            default:
                                throw new \Exception;
                        }
                        break;

                    default:
                        throw new \Exception;
                }

            }else{
                throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function canJoin(){
        if (in_array($this->AR->status, [self::STATUS_UNPAID, self::STATUS_WAIT]) &&
            Yii::$app->time->unixTime < $this->AR->group_end_unixtime &&
            time() >= $this->AR->group_start_unixtime)
        {
            if ($this->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE) {
                return $this->target_quantity > $this->present_quantity;
            }elseif($this->gpubs_type == GpubsProduct::GPUBS_TYPE_DELIVER){
                switch($this->gpubs_rule_type) {
                    case GpubsProduct::STATUS_GPUBS_RULE_MEMBER:
                        return $this->target_member > $this->present_member;
                        break;
                    case GpubsProduct::STATUS_GPUBS_RULE_NUMBER:
                        return $this->target_quantity > $this->present_quantity;
                        break;
                    case GpubsProduct::STATUS_GPUBS_PRE_NUMBER:
                        return $this->target_member > $this->present_member;
                        break;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public function pick(int $quantity, $return = 'throw'){
        if($quantity <= 0)return Yii::$app->EC->callback($return, 'incorrect quantity');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->db->createCommand("SELECT * FROM {{%activity_gpubs_group}} WHERE [[id]] = :id FOR UPDATE")->bindValues([
                ':id' => $this->AR->id,
            ])->queryOne();
            $result = Yii::$app->db->createCommand("UPDATE {{%activity_gpubs_group}} SET [[picked_up_quantity]] = [[picked_up_quantity]] + :quantity WHERE [[id]] = :id")->bindValues([
                ':quantity' => $quantity,
                ':id' => $this->AR->id,
            ])->execute();
            if($result){
                $this->AR = ActivityGpubsGroupAR::findOne($this->AR->id);
                $transaction->commit();
                return true;
            }else{
                throw new \Exception;
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function generateDetail(GpubsGroupTicket $ticket, $return = 'throw'){
        return GpubsGroupDetailGenerator::create($this, $ticket, $return);
    }

    private function multiCreate($items, $detail){
        $orders = [];
        try{
            foreach($items as $supplier){
                if(isset($supplier[Product::TYPE_STANDARD]) && count($supplier[Product::TYPE_STANDARD]) > 1){
                    if(!$standardOrder = $this->createStandardOrder($supplier[Product::TYPE_STANDARD], $detail))throw new \Exception;
                    $orders = array_merge($orders, [$standardOrder]);
                }
                if(isset($supplier[Product::TYPE_CUSTOMIZATION])){
                    return false;
                }
            }
        }catch(\Exception $e){
            return false;
        }
        if(!$orders)return false;
        return $orders;
    }

    private function createStandardOrder($standardItems, $detail){
        $ticket = $standardItems['ticket'] ? new CouponRecord(['id' => $standardItems['ticket']]) : false;
        unset($standardItems['ticket']);
        $items = [];
        foreach($standardItems as $itemId => $comment){
            $items[] = new \common\models\parts\Item([
                'id' => $itemId,
            ]);
        }
        if(!$order = GpubsOrderHandler::create($items, $detail))return false;
        if($ticket){
            $order->useCoupon($ticket);
        }
        return $order;
    }

}
