<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 14:07
 */

namespace api\models;



use common\ActiveRecord\CarBrandAR;
use common\components\handler\car\CarBrandHandler;

use common\models\Model;
use common\models\parts\car\CarBrand;

class CarModel extends  Model
{

    const SCE_GET_CAR_BRAND="get_car_brand";//获取品牌列表
    const SCE_GET_CAR_TYPE="get_car_type";//获取车型列表


    public $brand_id;

    public function scenarios()
    {
        return [
            self::SCE_GET_CAR_BRAND=>[],
            self::SCE_GET_CAR_TYPE=>['brand_id'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['brand_id'],
                'required',
                'message'=>9001,
            ],
            [
                ['brand_id'],
                'exist',
                'targetClass'=>CarBrandAR::className(),
                'targetAttribute'=>['brand_id'=>'id'],
                'message'=>7041,
            ]
        ];
    }

    //获取品牌列表
    public function getCarBrand(){
        return array_map(function($item){
            return [
                'id'=>$item->id,
                'name'=>$item->getName(),
                'sign'=>$item->getChar(),
            ];

        }, CarBrandHandler::getCarBrandList());
    }

    //获取车型
    public function getCarType(){
        if($list=(new CarBrand(['id'=>$this->brand_id]))->getTypeList()){
            return $list;
        }
        $this->addError('getCarType',7096);
        return false;
    }

}