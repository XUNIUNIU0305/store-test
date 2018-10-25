<?php
namespace mobile\components\handler;

use Yii;
use common\components\handler\Handler;
use common\models\RapidQuery;
use common\ActiveRecord\ActivityGpubsAddressAR;
use common\models\parts\gpubs\GpubsAddress;

class SpotAddressHandler extends Handler{

    /**
     * 创建地址对象
     *
     * @param array $attributes 地址表字段
     *
     * @return Object
     */
    public static function create(array $attributes){
        if((new RapidQuery(new ActivityGpubsAddressAR))->insert($attributes)){
            $addressId = Yii::$app->db->lastInsertId;
            return new GpubsAddress(['id' => $addressId]);
        }else{
            return false;
        }
    }

    /**
     * 自提点地址列表
     *
     * @param int $return 默认true：返回全部地址的对象，int：该地址id的地址对象
     * @param int $user 用户ID
     *
     * Object|false
     */
    public static function getList($return = true, $userId = null){
        if ($return === true){
            $ids = (new RapidQuery(new ActivityGpubsAddressAR))->column([
                'select' => ['id'],
                'where' => ['custom_user_id' => $userId],
            ]);
            return array_values(array_map(function($address){
                return new GpubsAddress(['id' => $address]);
            }, $ids));
        }else{
            return new GpubsAddress(['id' => $return]);
        }
    }

    /**
     * 获取默认地址
     *
     * 如果没有默认地址则返回false
     *
     * @return Object|false
     */
    public static function getDefaultAddress($userId){
        if($addressAR = ActivityGpubsAddressAR::findOne(['custom_user_id' => $userId, 'default' => ActivityGpubsAddressAR::DEFAULT_ADDRESS])){
            return new GpubsAddress(['id' => $addressAR->id]);
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
    public static function setDefault(GpubsAddress $address){
        return $address->setDefault(true);
    }


    /**
     * 删除地址
     *
     * @param Object Address $address 地址对象
     *
     * @return boolean
     */
    public static function remove(GpubsAddress $address){
        return ActivityGpubsAddressAR::findOne($address->id)->delete();
    }

}
