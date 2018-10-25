<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\car;


use common\ActiveRecord\CarBrandAR;
use common\components\handler\Handler;
use common\models\car\CarAlphabet;
use common\models\parts\car\CarBrand;
use Yii;

class CarBrandHandler extends  Handler
{



    //获取品牌列表
    public static  function getCarBrandList(CarAlphabet $carAlphabet=null){
        $where="1";
        if($carAlphabet){
            $where.=" and alphabet_id='".$carAlphabet->id."'";
        }
        return array_map(function($item){
            return new CarBrand(['id'=>$item['id']]);
        },Yii::$app->RQ->AR(new CarBrandAR())->all([
            'where'=>$where,
            'select'=>['id'],
        ]));
    }



}