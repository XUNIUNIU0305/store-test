<?php
namespace common\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\CustomUserAddressAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\models\RapidQuery;
use yii\base\InvalidCallException;

class Address extends Object{

    //地址表主键
    public $id;

    protected $AR;

    public function init(){
        if(!$this->AR = CustomUserAddressAR::findOne($this->id))throw new InvalidCallException;
    }

    /**
     * 返回完整的地址信息
     * 包括省、市、区和详细地址信息
     */
    public function __toString(){
        return $this->getProvinceString() . $this->getCityString() . $this->getDistrictString() . $this->getDetail();
    }

    /**
     * 获取用户ID
     *
     * @return int
     */
    public function getUserId(){
        return $this->AR->custom_user_id;
    }

    /**
     * 获取收货人
     *
     * @return string
     */
    public function getConsignee(){
        return $this->AR->consignee;
    }

    /**
     * 获取省级信息
     *
     * @param boolean $string 是否返回文字
     *
     * @return string|int
     */
    public function getProvince($string = false){
        if($string){
            return $this->getProvinceString();
        }else{
            return $this->AR->district_province_id;
        }
    }

    /**
     * 获取市级信息
     *
     * @param boolean $string 是否返回文字
     *
     * @return string|int
     */
    public function getCity($string = false){
        if($string){
            return $this->getCityString();
        }else{
            return $this->AR->district_city_id;
        }
    }

    /**
     * 获取区级信息
     *
     * @param boolean $string 是否返回文字
     *
     * @return string|int
     */
    public function getDistrict($string = false){
        if($string){
            return $this->getDistrictString();
        }else{
            return $this->AR->district_district_id;
        }
    }

    /**
     * 获取详细地址信息
     *
     * @return string
     */
    public function getDetail(){
        return $this->AR->detailed_address;
    }

    /**
     * 获取手机号码
     *
     * @return int
     */
    public function getMobile(){
        return $this->AR->mobile;
    }

    /**
     * 获取邮政编码
     *
     * @return int
     */
    public function getPostalCode(){
        return $this->AR->postal_code;
    }

    /**
     * 该地址是否是默认地址
     *
     * @return boolean
     */
    public function getIsDefault(){
        return $this->AR->default ? true : false;
    }

    /**
     * 设置收货人
     *
     * @return boolean
     */
    public function setConsignee($consignee){
        $this->AR->consignee = $consignee;
        return $this->AR->update();
    }

    /**
     * 设置省ID
     *
     * @return boolean
     */
    public function setProvince($province){
        if(DistrictProvinceAR::findOne($province)){
            $this->AR->district_province_id = $province;
            return $this->AR->update();
        }else{
            return false;
        }
    }

    /**
     * 设置市ID
     *
     * @return boolean
     */
    public function setCity($city){
        if(DistrictCityAR::findOne($city)){
            $this->AR->district_city_id = $city;
        }else{
            return false;
        }
    }

    /**
     * 设置区ID
     *
     * @return boolean
     */
    public function setDistrict($district){
        if(DistrictDistrictAR::findOne($district)){
            $this->AR->district_district_id = $district;
            return $this->AR->update();
        }else{
            return false;
        }
    }

    /**
     * 设置详细地址
     *
     * @return boolean
     */
    public function setDetail($detail){
        $this->AR->detailed_address = $detail;
        return $this->AR->update();
    }

    /**
     * 设置手机号码
     *
     * @return boolean
     */
    public function setMobile($mobile){
        $this->AR->mobile = $mobile;
        return $this->AR->update();
    }

    /**
     * 设置邮政编码
     *
     * @return boolean
     */
    public function setPostalCode($code){
        $this->AR->postal_code = $code;
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
            if($customUserAddressAR = CustomUserAddressAR::findOne(['custom_user_id' => $this->AR->custom_user_id, 'default' => CustomUserAddressAR::DEFAULT_ADDRESS])){
                $customUserAddressAR->default = CustomUserAddressAR::NORMAL_ADDRESS;
                if(!$customUserAddressAR->update())throw new \Exception;
            }
            $this->AR->default = CustomUserAddressAR::DEFAULT_ADDRESS;
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
            'consignee' => 'consignee',
            'province' => 'district_province_id',
            'city' => 'district_city_id',
            'district' => 'district_district_id',
            'detail' => 'detailed_address',
            'mobile' => 'mobile',
            'postalCode' => 'postal_code',
        ];
        $changedAttributes = array_intersect_key($attributes, $keys);
        foreach($changedAttributes as $key => $attribute){
            $this->AR->{$keys[$key]} = $attribute;
        }
        return $this->AR->update();
    }

    /**
     * 获取省名称
     *
     * @return string
     */
    protected function getProvinceString(){
        return DistrictProvinceAR::findOne($this->AR->district_province_id)->name;
    }

    /**
     * 获取市名称
     *
     * @return string
     */
    protected function getCityString(){
        if(!$this->AR->district_city_id)return '';
        return DistrictCityAR::findOne($this->AR->district_city_id)->name;
    }

    /**
     * 获取区名称
     *
     * @return string
     */
    protected function getDistrictString(){
        if(!$this->AR->district_district_id)return '';
        return DistrictDistrictAR::findOne($this->AR->district_district_id)->name;
    }
}
