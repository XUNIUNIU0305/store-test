<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\quality;



use common\ActiveRecord\QualityPriceAR;
use common\components\handler\Handler;
use Yii;

class QualityPriceHandler extends  Handler
{


    //获取各方位报价列表
    public static function getPriceList(){


    }

    //获取价格信息
    public static  function getPrice(int $package_id,int $place_id,$type_id){
        $where=['car_type_id'=>$type_id,'quality_package_id'=>$package_id,'quality_place_id'=>$place_id,''];

        return Yii::$app->RQ->AR(new QualityPriceAR())->one([
            'select'=>['price','area','hard','time'],
            'where'=>$where,
        ]);
    }



}