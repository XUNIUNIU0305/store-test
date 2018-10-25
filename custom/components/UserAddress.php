<?php
namespace custom\components;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use common\ActiveRecord\CustomUserAddressAR;
use common\models\RapidQuery;
use common\models\parts\Address;
use custom\components\handler\AddressHandler;

class UserAddress extends Object{

    protected $userId;
    //array 地址id
    protected $address;

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        $this->userId = Yii::$app->user->id;
        $this->address = (new RapidQuery(new CustomUserAddressAR))->column([
            'select' => ['id'],
            'where' => ['custom_user_id' => $this->userId],
        ]);
    }

    /**
     * 获取用户地址数量
     *
     * @return int
     */
    public function getCount(){
        return count($this->address);
    }

    /**
     * 获取用户地址列表
     *
     * @param mix $return 需要返回的数据，默认true：返回全部地址的对象，Array：array包含地址id的地址对象，Integer：该地址id的地址对象
     *
     * @return array|Object|false
     */
    public function getList($return = true){
        if(is_array($return) || $return === true){
            if($return !== true){
                $return = array_intersect($this->address, $return);
            }else{
                $return = $this->address;
            }
            return array_values(array_map(function($address){
                return new Address(['id' => $address]);
            }, $return));
        }else{
            if(in_array($return, $this->address)){
                return new Address(['id' => $return]);
            }else{
                return false;
            }
        }
    }

    /**
     * 获取默认地址
     *
     * 如果没有默认地址则返回false
     *
     * @return Object|false
     */
    public function getDefaultAddress(){
        if($addressAR = CustomUserAddressAR::findOne(['custom_user_id' => $this->userId, 'default' => CustomUserAddressAR::DEFAULT_ADDRESS])){
            return new Address(['id' => $addressAR->id]);
        }else{
            return false;
        }
    }

    /**
     * 设置默认地址
     *
     * @param Object Address $address 地址对象
     *
     * @return boolean
     */
    public function setDefaultAddress(Address $address){
        if($address->userId == $this->userId){
            return $address->setDefault(true);
        }else{
            return false;
        }
    }

    /**
     * 删除地址
     *
     * @param Object Address $address 地址对象
     *
     * @return boolean
     */
    public function remove(Address $address){
        if($address->userId != $this->userId)return false;
        return AddressHandler::remove($address);
    }
}
