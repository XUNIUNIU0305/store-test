<?php
namespace common\models\parts\district;

use Yii;
use yii\base\Object;
use common\models\parts\basic\DistrictInterface;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\models\RapidQuery;
use yii\base\InvalidCallException;

class Province extends Object implements districtInterface{

    //district_province表主键
    public $provinceId;

    protected $AR;

    public function init(){
        if(!is_null($this->provinceId)){
            if(!$this->AR = DistrictProvinceAR::findOne($this->provinceId))throw new InvalidCallException;
        }
    }

    /**
     * 获取名称
     *
     * 如果未指定省ID返回false
     *
     * @return string|false
     */
    public function getName(){
        return is_null($this->AR) ? false : $this->AR->name;
    }

    /**
     *====================================================
     * 获取经度
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function getLat(){
        return is_null($this->AR) ? false : (float)$this->AR->lat;
    }

    /**
     *====================================================
     * 获取纬度
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function getLng(){
        return is_null($this->AR) ? false : (float)$this->AR->lng;
    }

    /**
     * 获取城市编码
     *
     * 如果未指定省ID返回false
     *
     * @return string|false
     */
    public function getCityCode(){
        return is_null($this->AR) ? false : $this->AR->citycode;
    }

    /**
     * 获取区域编码
     *
     * 如果未指定省ID返回false
     *
     * @return string|false
     */
    public function getAdCode(){
        return is_null($this->AR) ? false : $this->AR->adcode;
    }

    /**
     * 获取省列表
     *
     * @param mix $return 需要返回的信息 默认返回省id，TRUE：返回对象，Array：指定返回字段
     *
     * @return array
     */
    public function getList($return = false){
        if($return === false)$return = ['id'];
        if($return === true){
            $provinceIds = (new RapidQuery(new DistrictProvinceAR))->column([
                'select' => ['id'],
                'where' => ['show' => DistrictProvinceAR::SHOW],
            ]);
            return array_map(function($provinceId){
                return new Province(['provinceId' => $provinceId]);
            }, $provinceIds);
        }else{
            return (new RapidQuery(new DistrictProvinceAR))->all([
                'select' => $return,
                'where' => ['show' => DistrictProvinceAR::SHOW],
            ]);
        }
    }

    /**
     * 在指定省ID时返回该省下的市列表
     *
     * @param mix $return 需要返回的信息
     *
     * @return array
     */
    public function getChildList($return = false){
        if(is_null($this->AR))return false;
        return (new City(['provinceId' => $this->AR->id]))->getList($return);
    }

    /**
     * 检查该省是否有市级
     * 未指定省ID返回false
     *
     * @return false
     */
    public function getHasChild(){
        if(is_null($this->AR))return false;
        return (new RapidQuery(new DistrictCityAR))->exists([
            'select' => ['id'],
            'where' => [
                'district_province_id' => $this->AR->id,
                'show' => DistrictCityAR::SHOW,
            ],
            'limit' => 1,
        ]);
    }
}
