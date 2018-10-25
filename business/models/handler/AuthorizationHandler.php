<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-30
 * Time: 下午9:32
 */

namespace business\models\handler;


use common\ActiveRecord\CustomUserAuthorizationAR;
use common\models\parts\partner\Authorization;

class AuthorizationHandler
{
    /**
     * 查询通过账号
     * @param $uid
     * @param $start
     * @param $end
     * @param $source
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryUserBy($uid, $start, $end, $source)
    {
        return CustomUserAuthorizationAR::find()
            ->where(['custom_user_id'=>$uid])
                ->where(['promoter_type' => $source])
            ->andWhere(['status' => [Authorization::STATUS_AUTHORIZE_SUCCESS, Authorization::STATUS_ACCOUNT_VALID]])
                ->andWhere(['>=', 'authorize_apply_datetime', $start])
                ->andWhere(['<=', 'authorize_apply_datetime', $end])
            ->asArray()->all() ?? [];
    }
}