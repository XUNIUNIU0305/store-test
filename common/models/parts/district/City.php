<?php
namespace common\models\parts\district;

use Yii;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\models\RapidQuery;
use yii\base\InvalidCallException;

class City extends Province{

    //district_city表主键
    public $cityId;

    //指定市ID后自动设置，省ID
    protected $_provinceId;

    public function init(){
        if(!is_null($this->cityId)){
            if($this->cityId == 0){
                $this->_provinceId = $this->provinceId;
            }else{
                if(!$this->AR = DistrictCityAR::findOne($this->cityId))throw new InvalidCallException;
                $this->_provinceId = $this->AR->district_province_id;
            }
        }
    }

    /**
     * 获取省对象
     * 未指定市ID返回false
     *
     * @return Object|false
     */
    public function getProvince(){
        return is_null($this->_provinceId) ? false : new Province(['provinceId' => $this->_provinceId]);
    }

    /**
     * 获取市列表
     * 必须指定省ID，未指定市ID时可以单独指定省ID
     *
     * @param mix $return 需要返回的信息 详见\common\models\parts\district\Province::getList()
     *
     * @return array|false
     */
    public function getList($return = false){
        $provinceId = $this->_provinceId ?? $this->provinceId;
        if(is_null($provinceId))return false;
        if($return === false)$return = ['id'];
        if($return === true){
            $cityIds = (new RapidQuery(new DistrictCityAR))->column([
                'select' => ['id'],
                'where' => [
                    'district_province_id' => $provinceId,
                    'show' => DistrictCityAR::SHOW,
                ],
            ]);
            return array_map(function($cityId){
                return new City(['cityId' => $cityId]);
            }, $cityIds);
        }else{
            return (new RapidQuery(new DistrictCityAR))->all([
                'select' => $return,
                'where' => [
                    'district_province_id' => $provinceId,
                    'show' => DistrictCityAR::SHOW,
                ],
            ]);
        }
    }

    /**
     * 获取该市下的区列表
     * 必须指定市ID
     *
     * @param mix $return 需要返回的信息
     *
     * @return array
     */
    public function getChildList($return = false){
        if(is_null($this->AR))return false;
        return (new District([
            'provinceId' => $this->AR->district_province_id,
            'cityId' => $this->AR->id,
        ]))->getList($return);
    }

    /**
     * 验证省ID是否正确
     *
     * @return boolean
     */
    public function validate(){
        if(is_null($this->provinceId))return false;
        return $this->provinceId == $this->_provinceId;
    }

    /**
     * 检查是否有区级
     * 必须指定市ID，未指定返回false
     *
     * @return boolean
     */
    public function getHasChild(){
        if(is_null($this->AR))return false;
        return (new RapidQuery(new DistrictDistrictAR))->exists([
            'select' => ['id'],
            'where' => [
                'district_city_id' => $this->AR->id,
                'show' => DistrictDistrictAR::SHOW,
            ],
            'limit' => 1,
        ]);
    }
}
