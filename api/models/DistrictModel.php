<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\models\parts\district\Province;
use common\models\parts\district\City;
use common\models\parts\district\District;

class DistrictModel extends Model{

    const SCE_GET_CITY = 'get_city';
    const SCE_GET_DISTRICT = 'get_district';

    public $province;
    public $city;

    public function scenarios(){
        return [
            self::SCE_GET_CITY => [
                'province',
            ],
            self::SCE_GET_DISTRICT => [
                'province',
                'city',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['province', 'city'],
                'required',
                'message' => 9001,
            ],
            [
                ['province'],
                'common\validators\district\ProvinceValidator',
                'message' => 7011,
                'on' => [self::SCE_GET_CITY],
            ],
            [
                ['city'],
                'common\validators\district\CityValidator',
                'province' => $this->province,
                'message' => 7012,
            ],
        ];
    }

    public static function getProvince(){
        $province = new Province();
        return $province->getList(['id', 'name']);
    }

    public function getCity(){
        $province = new Province(['provinceId' => $this->province]);
        return $province->getChildList(['id', 'name']);
    }

    public function getDistrict(){
        $district = new District([
            'provinceId' => $this->province,
            'cityId' => $this->city,
        ]);
        return $district->getList(['id', 'name']);
    }
}
