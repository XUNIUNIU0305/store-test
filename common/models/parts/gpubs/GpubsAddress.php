<?php
namespace common\models\parts\gpubs;

use Yii;
use common\ActiveRecord\ActivityGpubsAddressAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use yii\base\InvalidCallException;
use common\models\parts\Address;

class GpubsAddress extends Address{

    //地址表主键
    public $id;

    protected $AR;

    public function init(){
        if(!$this->AR = ActivityGpubsAddressAR::findOne($this->id))throw new InvalidCallException;
    }

    /**
     * 获取自提点名称
     *;
     * @return string
     */
    public function getSpotName(){
        return $this->AR->spot_name;
    }

    /**
     * 设置自提点名称
     *;
     * @return boolean
     */
    public function setSpotName($name){
        $this->AR->spot_name = $name;
        return $this->AR->update();
    }

    /**
     * 设置当前地址为默认地址
     *
     * @return boolean
     */
    public function setDefault($default){
        if($default !== true)return false;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($customUserAddressAR = ActivityGpubsAddressAR::findOne(['custom_user_id' => $this->AR->custom_user_id, 'default' => ActivityGpubsAddressAR::DEFAULT_ADDRESS])){
                if ($customUserAddressAR->id == $this->AR->id){
                    $transaction->commit();
                    return true;
                }
                $customUserAddressAR->default = ActivityGpubsAddressAR::NORMAL_ADDRESS;
                if(!$customUserAddressAR->update())throw new \Exception;
            }
            $this->AR->default = ActivityGpubsAddressAR::DEFAULT_ADDRESS;
            if(!$this->AR->update())throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 设置多个收货信息
     *
     * @return boolean
     */
    public function set(array $attributes){
        if(!$attributes)return null;
        $keys = [
            'spot_name' => 'spot_name',
            'consignee' => 'consignee',
            'district_province_id' => 'district_province_id',
            'district_city_id' => 'district_city_id',
            'district_district_id' => 'district_district_id',
            'detailed_address' => 'detailed_address',
            'mobile' => 'mobile',
            'postal_code' => 'postal_code',
        ];
        $changedAttributes = array_intersect_key($attributes, $keys);
        foreach($changedAttributes as $key => $attribute){
            $this->AR->{$keys[$key]} = $attribute;
        }
        return $this->AR->update();
    }

}
