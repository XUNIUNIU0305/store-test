<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\quality;



use common\ActiveRecord\QualityPackageAR;
use common\components\handler\Handler;

use Yii;

class QualityPackageHandler extends  Handler
{
    //获取套餐列表
    public static  function getList($sort=['sort'=>SORT_ASC]){
        return Yii::$app->RQ->AR(new QualityPackageAR())->all([
            'select'=>['id','name'],
            'orderBy'=>$sort,
        ]);
    }


    //获取套餐列表
    public static  function getBusinessList($sort=['sort'=>SORT_ASC]){
        return Yii::$app->RQ->AR(new QualityPackageAR())->all([
            'select'=>['id','name'],
            'where'=>[
                'is_available'=>1,
            ],
            'orderBy'=>$sort,
        ]);
    }



}