<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-24
 * Time: 下午5:43
 */

namespace business\models\handler;

use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSKUAR;
use common\ActiveRecord\ShoppingCartAR;
use common\models\parts\Product;

class ShoppingCartHandler
{
    /**
     * 查询时间范围内总价
     * @param $uid
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function queryTotalFeeBy($uid, $start, $end)
    {
        return ShoppingCartAR::find()->alias('a')
            ->where(['a.custom_user_id' => $uid])
            ->andWhere(['>=', 'a.add_datetime', $start])
            ->andWhere(['<=', 'a.add_datetime', $end])
            ->leftJoin(ProductSKUAR::tableName() . ' b', 'a.product_sku_id = b.id')
            ->leftJoin(ProductAR::tableName() . ' c', 'a.product_id = c.id')
            ->andWhere(['c.sale_status' => Product::SALE_STATUS_ONSALE])
            ->sum('b.price') ?? 0;
    }
}