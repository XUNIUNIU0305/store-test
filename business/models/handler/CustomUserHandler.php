<?php
namespace business\models\handler;

use common\ActiveRecord\CustomUserAuthorizationAR;
use common\ActiveRecord\OrderAR;
use common\models\parts\custom\CustomUser;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserAR;
use common\models\parts\Order;
use common\models\parts\partner\Authorization;
use yii\data\ActiveDataProvider;
use business\models\parts\Area;

class CustomUserHandler extends Handler{

    public static function provide($account = null, Area $area = null, int $currentPage, int $pageSize){
        if($currentPage <= 0)$currentPage = 1;
        if($pageSize <= 0)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => CustomUserAR::find()->
                select(['id', 'account', 'shop_name', 'nick_name', 'business_area_id'])
                ->where(['status' => 0])
                ->andFilterWhere(['business_area_id' => $area ? $area->id : null])
                ->andFilterwhere(['account' => $account])
                ->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }

    /**
     * 按５级区域ID查询用户
     * @param $area
     * @return array
     */
    public static function findUserBy($area)
    {
        return CustomUserAR::find()
            ->select(['id', 'account', 'mobile'])
            ->where(['authorized' => CustomUser::AUTHORIZED])
            ->andWhere(['status' => CustomUser::STATUS_NORMAL])
            ->andWhere(['business_area_id' => $area])
            ->asArray()->all() ?? [];
    }

    /**
     * 按５级区域ID查询用户
     * @param $area
     * @param $level
     * @return array
     */
    public static function findUserIdBy($area, $level = null)
    {
        return CustomUserAR::find()
            ->select(['id'])
            ->where(['authorized' => CustomUser::AUTHORIZED])
            ->andWhere(['status' => CustomUser::STATUS_NORMAL])
            ->andWhere(['business_area_id' => $area])
            ->andFilterWhere(['level' => $level])
            ->column();
    }

    /**
     * 按5级区域ID统计邀请用户
     * @param $area
     * @return array
     */
    public static function findInviteUserIdBy($area)
    {
        return CustomUserAuthorizationAR::find()->alias('a')
            ->select(['custom_user_id'])
            ->where(['a.status' => Authorization::STATUS_ACCOUNT_VALID])
            ->leftJoin(CustomUserAR::tableName() . ' b', 'b.id = a.custom_user_id')
            ->andWhere(['b.business_area_id' => $area])
            ->groupBy('custom_user_id')
            ->column();
    }

    /**
     * 查找范围内账号
     * @param $area
     * @param $start
     * @param $end
     * @return array
     */
    public static function findRegisterUserBy($area, $start, $end)
    {
        return CustomUserAR::find()->alias('a')
            ->where(['a.business_area_id' => array_column($area, 'id')])
            ->leftJoin(CustomUserAuthorizationAR::tableName() . ' b', 'a.id = b.custom_user_id')
            ->andWhere(['between', 'b.account_valid_datetime', $start, $end])
            ->andWhere(['b.status' => Authorization::STATUS_ACCOUNT_VALID])
            ->all();
    }

    /**
     * 查询范围内邀请用户数
     * @param $area
     * @param $start
     * @param $end
     * @return int
     */
    public static function countInviteNumBy($area, $start, $end)
    {
        return CustomUserAuthorizationAR::find()->alias('a')
            ->where(['>=', 'pay_datetime', $start])
            ->andWhere(['<=', 'pay_datetime', $end])
            ->leftJoin(CustomUserAR::tableName() . ' b', 'b.id = a.custom_user_id')
            ->andWhere(['b.business_area_id' => $area])
            ->count(1);
    }

    /**
     * 查询范围内开通用户数
     * @param $area
     * @param $start
     * @param $end
     * @return int|string
     */
    public static function countOpenedUserNumBy($area, $start, $end)
    {
        return CustomUserAuthorizationAR::find()->alias('a')
            ->where(['>=', 'authorized_datetime', $start])
            ->andWhere(['<=', 'authorized_datetime', $end])
            ->andWhere(['a.status'=> Authorization::STATUS_ACCOUNT_VALID])
            ->leftJoin(CustomUserAR::tableName() . ' b', 'b.id = a.custom_user_id')
            ->andWhere(['b.business_area_id' => $area])
            ->count(1);
    }

    /**
     * 查询用户订单消费
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function querySumOrderTotalFeeBy($id)
    {
        return OrderAR::find()
                ->select(['sum(total_fee) total', 'custom_user_id id'])
            ->where(['custom_user_id' => $id])
            ->andWhere(['status' => Order::STATUS_CLOSED])
            ->groupBy('custom_user_id')
            ->asArray()->all() ?? [];
    }
}
