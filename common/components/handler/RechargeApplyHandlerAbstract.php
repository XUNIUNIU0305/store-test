<?php
namespace common\components\handler;

use Yii;
use common\ActiveRecord\RechargeApplyAR;
use common\models\RapidQuery;
use common\models\parts\trade\recharge\RechargeApply;
use common\models\parts\trade\RechargeMethodAbstract;
use common\models\parts\trade\recharge\RechargeIdGenerator;

abstract class RechargeApplyHandlerAbstract extends Handler{

    public static function createRechargeApply($userType, int $userApplyId){
        if(!in_array($userType, RechargeApply::getUserTypes()))return false;
        if($userApplyId <= 0)return false;
        $result = (new RapidQuery(new RechargeApplyAR))->insert([
            'recharge_number' => (new RechargeIdGenerator)->id,
            'apply_user_type' => $userType,
            'corresponding_recharge_apply_id' => $userApplyId,
        ]);
        return $result ? Yii::$app->db->lastInsertId : false;
    }

    abstract public static function create(float $rmb, RechargeMethodAbstract $rechargeMethod);
}
