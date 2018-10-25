<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 下午3:44
 */

namespace business\models\handler;


use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSKUAR;
use common\models\parts\Order;

class OrderHandler
{
    public static $activeStatus = [
        Order::STATUS_UNDELIVER,
        Order::STATUS_DELIVERED,
        Order::STATUS_CONFIRMED,
        Order::STATUS_CLOSED
    ];

    /**
     * 按门店ID，时间查找有效订单订单
     * @param $uid
     * @param $dateStart
     * @param $dateEnd
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryActiveOrderBy($uid, $dateStart = null, $dateEnd = null)
    {
        $query = OrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andWhere(['>=', 'create_datetime', $dateStart])
            ->andWhere(['<=', 'create_datetime', $dateEnd])
            ->andWhere(['status' => static::$activeStatus]);
        return $query->all() ?? [];
    }

    /**
     * 获取门店时间范围内所有订单
     * @param $uid
     * @param $start
     * @param $end
     * @param $condition
     * @return array
     */
    public static function queryOrderBy($uid, $start = null, $end = null, $condition = [])
    {
        return OrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andFilterWhere(['>=', 'create_datetime', $start])
            ->andFilterWhere(['<=', 'create_datetime', $end])
            ->andFilterWhere($condition)
            ->asArray()->all() ?? [];
    }

    /**
     * @param $uid
     * @param $dateStart
     * @param $dateEnd
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryCloseOrderBy($uid, $dateStart = null, $dateEnd = null)
    {
        return OrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andFilterWhere(['>=', 'create_datetime', $dateStart])
            ->andFilterWhere(['<=', 'create_datetime', $dateEnd])
            ->andWhere(['status' => Order::STATUS_CLOSED])
            ->all() ?? [];
    }

    /**
     * @param $uid
     * @param $dateStart
     * @param $dateEnd
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryHotSupply($uid, $dateStart, $dateEnd)
    {
        return OrderAR::find()->select(['sum(total_fee) total', 'supply_user_id id'])
            ->where(['status' => static::$activeStatus])
            ->andWhere(['custom_user_id' => $uid])
            ->andWhere(['>=', 'create_datetime', $dateStart])
            ->andWhere(['<=', 'create_datetime', $dateEnd])
            ->groupBy('supply_user_id')
            ->orderBy(['total' => SORT_DESC])
            ->limit(1)->asArray()->one() ?? [];
    }

    /**
     * 按供应商获取前三销量的产品
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryTopItemBySupply($id, $limit = 3)
    {
        return OrderItemAR::find()->alias('a')
            ->select(['sum(total_fee) total', 'b.product_id id'])
            ->leftJoin(ProductSKUAR::tableName() . ' b', 'a.product_sku_id = b.id')
            ->leftJoin(ProductAR::tableName() . 'c', 'b.product_id = c.id')
            ->where(['c.supply_user_id' => $id])
            ->groupBy('b.product_id')
            ->orderBy(['total' => SORT_DESC])
            ->limit($limit)->asArray()->all() ?? [];
    }

    /**
     * 查询时间范围内销量
     * @param $uid
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function queryTotalFeeBy($uid, $start = null, $end = null)
    {
        return OrderAR::find()
            ->where(['custom_user_id' => $uid])
            ->andFilterWhere(['>=', 'pay_datetime', $start])
            ->andFilterWhere(['<=', 'pay_datetime', $end])
            ->andWhere(['status' => static::$activeStatus])
            ->sum('total_fee') ?? 0;
    }

    /**
     * 查询消费用户ID
     * @param $uid
     * @return array
     */
    public static function queryOrderUid($uid)
    {
        return OrderAR::find()
            ->select(['custom_user_id'])
            ->where(['custom_user_id' => $uid])
            ->andWhere(['status' => static::$activeStatus])
            ->groupBy('custom_user_id')
            ->asArray()->column();
    }

    /**
     * 统计时间段内非定制订单销量
     * @param $uid
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function queryNormalTotalFeeBy($uid, $start, $end)
    {
        return OrderAR::find()
            ->where(['status' => static::$activeStatus])
            ->andWhere(['custom_user_id' => $uid])
            ->andWhere(['>=', 'pay_datetime', $start])
            ->andWhere(['<=', 'pay_datetime', $end])
            ->andWhere(['is_customization' => Order::CUSTOM_STATUS_NO])
            ->sum('total_fee') ?? 0;
    }

    /**
     * 统计时间段内定制订单销量
     * @param $uid
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function queryCustomizationTotalFeeBy($uid, $start, $end)
    {
        return OrderAR::find()
            ->where(['status' => static::$activeStatus])
            ->andWhere(['custom_user_id' => $uid])
            ->andWhere(['>=', 'pay_datetime', $start])
            ->andWhere(['<=', 'pay_datetime', $end])
            ->andWhere(['is_customization' => Order::CUSTOM_STATUS_IS])
            ->sum('total_fee') ?? 0;
    }

    /**
     * 统计时间范围内支付时间与金额
     * @param $uid
     * @param $start
     * @param $end
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryTimeTotalFeeBy($uid, $start, $end)
    {
        return OrderAR::find()
            ->select(['pay_datetime date', 'total_fee'])
            ->where(['status' => static::$activeStatus])
            ->andWhere(['custom_user_id' => $uid])
            ->andWhere(['>=', 'pay_datetime', $start])
            ->andWhere(['<=', 'pay_datetime', $end])
            ->limit(20)
            ->asArray()->all();
    }

    /**
     * 查询销售前n的订单
     * @param $uid
     * @param $start
     * @param $end
     * @param $is
     * @param $num
     * @return array
     */
    public static function queryTopNormalProductBy($uid, $start, $end, $is, $num)
    {
        return OrderAR::find()->alias('a')
            ->select(['sum(a.total_fee) total', 'c.product_id'])
            ->where(['a.custom_user_id' => $uid])
            ->andFilterWhere(['>=', 'pay_datetime', $start])
            ->andFilterWhere(['<=', 'pay_datetime', $end])
            ->andFilterWhere(['is_customization' => $is])
            ->leftJoin(OrderItemAR::tableName() . ' b', 'b.order_id = a.id')
            ->leftJoin(ProductSKUAR::tableName() . ' c', 'c.id = b.product_sku_id')
            ->groupBy('c.product_id')
            ->orderBy(['total' => SORT_DESC])
            ->limit($num)
            ->asArray()->all() ?? [];
    }

    /**
     * 查询销量最高的产品
     * @param $uid
     * @param $start
     * @param $end
     * @return array
     */
    public static function queryTopOneProduct($uid, $start, $end)
    {
        return OrderAR::find()->alias('a')
            ->select(['sum(a.total_fee) total', 'c.product_id id'])
            ->where(['a.custom_user_id' => $uid])
            ->andWhere(['>=', 'pay_datetime', $start])
            ->andWhere(['<=', 'pay_datetime', $end])
                ->andWhere(['status' => Order::STATUS_CLOSED])
            ->leftJoin(OrderItemAR::tableName() . ' b', 'b.order_id = a.id')
            ->leftJoin(ProductSKUAR::tableName() . ' c', 'c.id = b.product_sku_id')
            ->groupBy('c.product_id')
            ->limit(1)
            ->asArray()->one() ?? [];
    }

    /**
     * 按商品查询销售额
     * @param $uid
     * @param $start
     * @param $end
     * @param $id
     * @return mixed
     */
    public static function queryTotalFeeByProduct($uid, $start, $end, $id)
    {
        return OrderItemAR::find()->alias('a')
            ->leftJoin(OrderAR::tableName() . ' b', 'b.id = a.order_id')
            ->leftJoin(ProductSKUAR::tableName() . ' c', 'c.id = a.product_sku_id')
            ->where(['c.product_id' => $id])
            ->andWhere(['b.custom_user_id' => $uid])
            ->andWhere(['>=', 'b.pay_datetime', $start])
            ->andWhere(['<=', 'b.pay_datetime', $end])
            ->andWhere(['b.status' => static::$activeStatus])
            ->sum('a.total_fee') ?? 0;
    }

    /**
     * 查询商品
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryProducts($id)
    {
        return ProductAR::find()
            ->select(['id', 'title'])
            ->where(['id' => $id])
            ->indexBy('id')
            ->asArray()->all() ?? [];
    }

    /**
     * 查询销量最高的产品sku
     * @param $id
     * @param $start
     * @param $end
     * @return array
     */
    public static function queryHotSkuBy($id, $start = null, $end = null)
    {
        return OrderItemAR::find()->alias('a')
            ->select(['sum(a.total_fee) total', 'b.id', 'b.price', 'max(a.sku_attributes) attributes'])
            ->leftJoin(OrderAR::tableName() . ' c', 'a.order_id = c.id')
            ->filterWhere(['>=', 'c.pay_datetime', $start])
            ->andFilterWhere(['<=', 'c.pay_datetime', $end])
            ->leftJoin(ProductSKUAR::tableName() . ' b', 'b.id = a.product_sku_id')
            ->andWhere(['b.product_id' => $id])
            ->groupBy('b.id')
            ->orderBy(['total' => SORT_DESC])
            ->limit(1)
            ->asArray()->one();
    }
}