<?php
namespace common\models\parts\gpubs;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidCallException;
use common\models\ObjectAbstract;
use common\ActiveRecord\ActivityGpubsGroupTicketAR;
use common\models\parts\Product;
use common\models\parts\custom\CustomUser;

class GpubsGroupTicket extends ObjectAbstract{

    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;

    const JOIN_WAIT = -1;
    const JOIN_FAILED = 0;
    const JOIN_SUCCESS = 1;

    public $id;

    private $_product;
    private $_customUser;
    private $_group;

    public function init(){
        if(!$this->AR = ActivityGpubsGroupTicketAR::findOne($this->id))throw new InvalidConfigException;
    }

    protected function _gettingList() : array{
        return [
            'activity_gpubs_product_id',
            'activity_gpubs_product_sku_id',
            'activity_gpubs_group_id',
            'custom_user_id',
            'product_id',
            'product_sku_id',
            'quantity',
            'price',
            'total_fee',
            'comment',
            'create_datetime',
            'create_unixtime',
            'pay_datetime',
            'pay_unixtime',
            'status',
            'is_join',
            'district_province_id',
            'district_city_id',
            'district_district_id',
            'detailed_address',
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

    public function setStatus(int $status, $return = 'throw'){
        if($status != self::STATUS_PAID){
            throw new InvalidCallException;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR($this->AR)->update([
                'status' => self::STATUS_PAID,
                'pay_datetime' => Yii::$app->time->fullDate,
                'pay_unixtime' => Yii::$app->time->unixTime,
            ]);
            if($this->joinGroup(false)){
                $this->setJoined(true);
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

    protected function joinGroup($return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $detail = $this->getGroup()->generateDetail($this);
            $this->getGroup()->join($detail);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function setJoined(bool $success, $return = 'throw'){
        if($this->AR->is_join != self::JOIN_FAILED)return Yii::$app->EC->callback($return, 'this ticket is used');
        return Yii::$app->RQ->AR($this->AR)->update([
            'is_join' => $success ? self::JOIN_SUCCESS : self::JOIN_FAILED,
        ], $return);
    }

    public function getProduct(){
        if(is_null($this->_product)){
            $this->_product = new Product([
                'id' => $this->AR->product_id,
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

    public function getGroup(){
        if(is_null($this->_group)){
            $this->_group = new GpubsGroup([
                'id' => $this->AR->activity_gpubs_group_id,
            ]);
        }
        return $this->_group;
    }
}
