<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 下午3:44
 */

namespace business\models\handler;


use common\ActiveRecord\MembraneOrderAR;
use common\ActiveRecord\MembraneOrderItemAR;
use common\models\parts\MembraneOrder;

class MembraneOrderHandler
{
    private static $activeStatus = [
        MembraneOrder::STATUS_PAYED,
        MembraneOrder::STATUS_ACCEPTED,
        MembraneOrder::STATUS_FINISHED
    ];

    /**
     * 按custom_user获取订单
     * @param $uid
     * @param $dateStart
     * @param $dateEnd
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryActiveOrderBy($uid, $dateStart = null, $dateEnd = null)
    {
        $query = MembraneOrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andWhere(['>=', 'created_date', $dateStart])
            ->andWhere(['<=', 'created_date', $dateEnd])
            ->andWhere(['status' => static::$activeStatus]);
        return $query->all() ?? [];
    }

    /**
     * 获取时间范围内所有订单
     * @param $uid
     * @param $start
     * @param $end
     * @param $condition
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryOrderBy($uid, $start, $end, $condition = [])
    {
        return MembraneOrderAR::find()
                ->where(['custom_user_id' => $uid])
                ->andFilterWhere(['>=', 'created_date', $start])
                ->andFilterWhere(['<=', 'created_date', $end])
                ->andFilterWhere($condition)
                ->all() ?? [];
    }

    /**
     * @param $uid
     * @param $dateStart
     * @param $dateEnd
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryCloseOrderBy($uid, $dateStart = null, $dateEnd = null)
    {
        return MembraneOrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andWhere(['>=', 'created_date', $dateStart])
            ->andWhere(['<=', 'created_date', $dateEnd])
            ->andWhere(['status' => MembraneOrder::STATUS_FINISHED])
            ->all() ?? [];
    }

    /**
     * 查询消费用户ID
     * @param $uid
     * @return array
     */
    public static function queryOrderUid($uid)
    {
        return MembraneOrderAR::find()
            ->select(['custom_user_id'])
            ->where(['custom_user_id' => $uid])
            ->andWhere(['status' => static::$activeStatus])
            ->groupBy('custom_user_id')
            ->asArray()->column();
    }

    /**
     * 查询时间段内销量
     * @param $uid
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function queryTotalFeeBy($uid, $start = null, $end = null)
    {
        return MembraneOrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andFilterWhere(['>=', 'pay_date', $start])
            ->andFilterWhere(['<=', 'pay_date', $end])
            ->andWhere(['status' => static::$activeStatus])
            ->sum('total_fee') ?? 0;
    }

    /**
     * 统计时间段内完成订单销量
     * @param $uid
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function queryFinishTotalFeeBy($uid, $start, $end)
    {
        return MembraneOrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andWhere(['>=', 'finish_date', $start])
            ->andWhere(['<=', 'finish_date', $end])
            ->andWhere(['status' => MembraneOrder::STATUS_FINISHED])
            ->sum('total_fee') ?? 0;

    }

    /**
     * 统计时间范围内订单晕金额
     * @param $uid
     * @param $start
     * @param $end
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryTimeTotalBy($uid, $start, $end)
    {
        return MembraneOrderAR::find()
            ->select(['finish_date date', 'total_fee'])
            ->where(['custom_user_id' => $uid])
            ->andWhere(['>=', 'finish_date', $start])
            ->andWhere(['<=', 'finish_date', $end])
            ->andWhere(['status' => MembraneOrder::STATUS_FINISHED])
            ->limit(20)
            ->asArray()->all();
    }
}