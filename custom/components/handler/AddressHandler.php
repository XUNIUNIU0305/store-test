<?php
namespace custom\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserAddressAR;
use common\models\RapidQuery;
use common\models\parts\Address;

class AddressHandler extends Handler{
    
    /**
     * 创建地址对象
     *
     * @param array $attributes 地址表字段
     *
     * @return Object
     */
    public static function create(array $attributes){
        if((new RapidQuery(new CustomUserAddressAR))->insert($attributes)){
            $addressId = Yii::$app->db->lastInsertId;
            return new Address(['id' => $addressId]);
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
    public static function remove(Address $address){
        return CustomUserAddressAR::findOne($address->id)->delete();
    }
}
