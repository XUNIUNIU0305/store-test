<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/10
 * Time: 17:44
 */

namespace common\components\handler;


use common\ActiveRecord\ExpressCorporationAR;
use Yii;

class ExpressCorporationHandler extends  Handler
{
    public static function getExpressCorporationList(){
        return Yii::$app->RQ->AR(new ExpressCorporationAR())->all([
                'select'=>['id','name','code'],
            ]
        );
    }

}