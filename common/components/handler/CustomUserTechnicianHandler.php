<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 15:28
 */

namespace common\components\handler;


use common\ActiveRecord\CustomUserTechnicianAR;
use common\models\parts\custom\CustomUser;
use common\models\parts\custom\CustomUserTechnician;
use Yii;

class CustomUserTechnicianHandler extends  Handler
{


    //获取技师列表
    public static function getList(CustomUser $customer=null){
        $where="1";
        if($customer){
            $where.=" and custom_user_id='$customer->id'";
        }

        return array_map(function($item){
            return new CustomUserTechnician(['id'=>$item['id']]);
        },Yii::$app->RQ->AR(new CustomUserTechnicianAR())->all(
            [
                'where'=>$where,
                'select'=>['id'],
            ]
        ));
    }

}