<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\quality;



use common\ActiveRecord\QualityPackageAR;
use common\ActiveRecord\QualityPlaceAR;
use common\components\handler\Handler;

use common\models\parts\quality\QualityPlace;
use Yii;

class QualityPlaceHandler extends  Handler
{



    //获取套餐列表
    public static  function getList($type=null,$sort=['sort'=>SORT_ASC]){
        $where="1";
        if($type!==null){
            $where.=" and type='$type'";
        }
        return Yii::$app->RQ->AR(new QualityPlaceAR())->all([
            'select'=>['id','name'],
            'where'=>$where,
            'orderBy'=>$sort,
        ]);
    }


    //获取默认列表
    public static function getDefaultPlace(){

        if($result=Yii::$app->RQ->AR(new QualityPlaceAR())->one([
            'where'=>['type'=>QualityPlace::TYPE_ALL],
            'select'=>['id'],
        ])){

            return (new QualityPlace(['id'=>$result['id']]));
        }return false;

    }

    //获取Business站套餐列表
    public static  function getBusinessList($type=null,$sort=['sort'=>SORT_ASC]){
        $where=" is_available = 1 ";
        if($type!==null){
            $where.=" and type='$type'";
        }
        return Yii::$app->RQ->AR(new QualityPlaceAR())->all([
            'select'=>['id','name'],
            'where'=>$where,
            'orderBy'=>$sort,
        ]);
    }




}