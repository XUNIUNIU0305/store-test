<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\quality;




use common\ActiveRecord\QualityAttributeAR;
use common\components\handler\Handler;

use common\models\parts\quality\QualityPackage;
use common\models\parts\quality\QualityPlace;
use Yii;

class QualityAttributeHandler extends  Handler
{



    //获取套餐属性信息
    public static  function getList(QualityPackage $package=null,QualityPlace $place,$sort=['sort'=>SORT_ASC]){

        $where="1";
        if($package!==null){
            $where.=" and quality_package_id='$package->id'";
        }
        if($place!==null){
            $where.=" and quality_place_id='$place->id'";
        }

        return Yii::$app->RQ->AR(new QualityAttributeAR())->all([
            'select'=>['id','name','value'],
            'where'=>$where,
            'orderBy'=>$sort,
        ]);
    }



}