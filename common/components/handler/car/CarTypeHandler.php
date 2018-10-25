<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\car;


use common\ActiveRecord\CarTypeAR;
use common\components\handler\Handler;

use common\models\parts\car\CarType;
use common\models\parts\car\CarBrand;
use Yii;

class CarTypeHandler extends  Handler
{

    public static function getTypeList(CarBrand $brand=null){
        $where="1";
        if($brand){
            $where.=" and brand_id='".$brand->id."'";
        }
        return array_map(function($item){
            return new CarType(['id'=>$item['id']]);
        },Yii::$app->RQ->AR(new CarTypeAR())->all([
            'where'=>$where,
            'select'=>['id'],
        ]));
    }

}