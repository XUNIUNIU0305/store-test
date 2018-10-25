<?php
namespace common\models\parts\district;

use Yii;
use common\ActiveRecord\DistrictDistrictAR;
use common\models\RapidQuery;
use yii\base\InvalidCallException;

class District extends City{

    //district_district表主键
    public $districtId;

    private $_cityId;

    public function init(){
        if(!is_null($this->districtId)){
            if($this->districtId == 0){
                $this->_provinceId = $this->provinceId;
                $this->_cityId = $this->cityId;
            }else{
                if(!$this->AR = DistrictDistrictAR::findOne($this->districtId))throw new InvalidCallException;
                $this->_provinceId = $this->AR->district_province_id;
                $this->_cityId = $this->AR->district_city_id;
            }
        }
    }

    /**
     * 获取市对象
     * 必须指定区ID
     *
     * @return Object
     */
    public function getCity(){
        return is_null($this->_cityId) ? false : new City(['cityId' => $this->_cityId]);
    }

    /**
     * 获取区列表
     * 必须指定省、市，可以单独指定
     *
     * @param mix $return 需要返回的信息 详见：\common\models\parts\district\Province::getList()
     * 
     * @return array|false
     */
    public function getList($return = false){
        $provinceId = $this->_provinceId ?? $this->provinceId;
        $cityId = $this->_cityId ?? $this->cityId;
        if(is_null($provinceId) || is_null($cityId))return false;
        if($return === false)$return = ['id'];
        if($return === true){
            $districtIds = (new RapidQuery(new DistrictDistrictAR))->column([
                'select' => ['id'],
                'where' => [
                    'district_province_id' => $provinceId,
                    'district_city_id' => $cityId,
                    'show' => DistrictDistrictAR::SHOW,
                ],
            ]);
            return array_map(function($districtId){
                return new District(['districtId' => $districtId]);
            }, $districtIds);
        }else{
            return (new RapidQuery(new DistrictDistrictAR))->all([
                'select' => $return,
                'where' => [
                    'district_province_id' => $provinceId,
                    'district_city_id' => $cityId,
                    'show' => DistrictDistrictAR::SHOW,
                ],
            ]);
        }
    }

    //没有区以下信息
    public function getChildList($return = false){
        return false;
    }

    /**
     * 验证省ID、市ID
     *
     * @return boolean
     */
    public function validate(){
        if(is_null($this->provinceId) || is_null($this->cityId))return false;
        if($this->provinceId != $this->_provinceId || $this->cityId != $this->_cityId)return false;
        return true;
    }

    //没有子级
    public function getHasChild(){
        return false;
    }
}
