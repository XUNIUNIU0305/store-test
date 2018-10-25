<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 19/06/18
 * Time: 11:57
 */

namespace api\components\handler;

use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\MembraneOrderAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\SupplyUserAR;
use common\ActiveRecord\ActivityGpubsGroupAR;
use common\models\parts\gpubs\GpubsGroup;
use common\components\handler\Handler;
use common\models\parts\MembraneOrder;
use common\models\parts\Order;
use Yii;

class DataMonitorHandler extends Handler
{
    // 排行榜显示限制
    private static $topLimit = 6;
    // 门店过滤
    private static $supplyFilter = [
        // 需剔除统计的店铺：370171214（展柜），663533947（欧帕斯膜），732577178（天御膜）714345395（退换货，专训）
        370171214, 663533947, 732577178, 714345395
    ];

    // 有效订单状态
    private static function getOrderStatus(){
        return [
            Order::STATUS_UNDELIVER,
            Order::STATUS_DELIVERED,
            Order::STATUS_CONFIRMED,
            Order::STATUS_CLOSED
        ];
    }

    /**
     * 获取全国销售总额
     * @param $date
     * @param $scale day|month
     * @return float
     */
    public static function getTotalSales($date, $scale = 'day')
    {
        if ($scale == 'day') {
            $endDate = date('Y-m-d', strtotime ("+1 day", strtotime($date)));
        } elseif ($scale == 'month') {
            $date = date('Y-m-01', strtotime($date));
            $endDate = date('Y-m-d', strtotime ("+1 month", strtotime($date)));
        }


        $normal = Yii::$app->RQ->AR(new OrderAR())->sum([
            'where'     => ['between', 'pay_datetime', $date, $endDate],
            'andWhere'  => ['status' => static::getOrderStatus() ]
        ], 'total_fee');

        $gpubs = Yii::$app->RQ->AR(new ActivityGpubsGroupAR)->sum([
            'where' => ['between', 'group_establish_datetime', $date, $endDate],
            'andWhere' => ['status' => GpubsGroup::STATUS_ESTABLISH],
        ], 'total_fee');
//        $membrane = Yii::$app->RQ->AR(new MembraneOrderAR())->sum([
//            'where'     => ['between', 'pay_date', $date, $endDate],
//            'andWhere'  => ['status' => MembraneOrder::$validStatus]
//        ], 'total_fee');
//        return floatval($normal + $membrane);
        return floatval($normal + $gpubs);
    }

    /**
     * 获取各省销售总额
     * @param $date
     * @param string $scale day|month
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getProvinceSales($date, $scale = 'day')
    {
        if ($scale == 'day') {
            $endDate = date('Y-m-d', strtotime ("+1 day", strtotime($date)));
        } elseif ($scale == 'month') {
            $date = date('Y-m-01', strtotime($date));
            $endDate = date('Y-m-d', strtotime ("+1 month", strtotime($date)));
        }

//        $res = Yii::$app->db->createCommand("
//            SELECT `area`.`name` AS `x`, sum(`u_fee`.`total_fee`) AS `y`
//            FROM ((
//                      SELECT `order`.`custom_user_id` AS `custom_user_id`, `order`.`total_fee` AS `total_fee`
//                      FROM {{%order}} AS `order`
//                      WHERE `order`.`pay_datetime` BETWEEN :date AND :endDate AND `order`.`status` IN (1,2,3,5)
//                  )
//                  UNION ALL (
//                      SELECT `membrane`.`custom_user_id` AS `custom_user_id`, `membrane`.`total_fee` AS `total_fee`
//                      FROM {{%membrane_order}} AS `membrane`
//                      WHERE `membrane`.`pay_date` BETWEEN :date AND :endDate AND `membrane`.`status` IN (2,3,4)
//                  )
//            ) AS `u_fee`
//            INNER JOIN {{%custom_user}} AS `user` ON `u_fee`.`custom_user_id` = `user`.`id`
//            INNER JOIN {{%business_area}} AS `area` ON `user`.`business_top_area_id` = `area`.`id`
//            GROUP BY `area`.`name`
//            ORDER BY `y` DESC
//        ", [':date' => $date, ':endDate' => $endDate])->queryAll();

        $res = Yii::$app->db->createCommand("
            SELECT `area`.`name` AS `x`, sum(`u_fee`.`total_fee`) AS `y`
            FROM (
                  SELECT `order`.`custom_user_id` AS `custom_user_id`, `order`.`total_fee` AS `total_fee`
                  FROM {{%order}} AS `order` 
                  WHERE `order`.`pay_datetime` BETWEEN :date AND :endDate AND `order`.`status` IN (1,2,3,5)
            ) AS `u_fee`
            INNER JOIN {{%custom_user}} AS `user` ON `u_fee`.`custom_user_id` = `user`.`id`
            INNER JOIN {{%business_area}} AS `area` ON `user`.`business_top_area_id` = `area`.`id`
            GROUP BY `area`.`name`
            ORDER BY `y` DESC
        ", [':date' => $date, ':endDate' => $endDate])->queryAll();
        return $res;
    }

    // 获取分时数据
    public static function getHourTotalFee($date)
    {
        $startDate = date('Y-m-d H:i:s', strtotime ("-1 day", strtotime($date)));
        $date = date('Y-m-d H:i:s', strtotime($date));

        $order = Yii::$app->RQ->AR(new OrderAR())->all([
            'select' => ['sum(total_fee) as totalFee', 'DATE_FORMAT(pay_datetime, "%Y-%m-%d %H") as hour'],
            'where' => ['status' => self::getOrderStatus()],
            'andWhere' => ['between', 'pay_datetime', $startDate, $date],
            'groupBy' => ['DATE_FORMAT(pay_datetime, "%Y-%m-%d %H")'],
            'orderBy' => 'hour ASC',
        ]);
//        $membrane = Yii::$app->RQ->AR(new MembraneOrderAR())->all([
//            'select' => ['sum(total_fee) as totalFee', 'DATE_FORMAT(pay_date, "%Y-%m-%d %H") as hour'],
//            'where' => ['status' => MembraneOrder::$validStatus],
//            'andWhere' => ['between', 'pay_date', $startDate, $date],
//            'groupBy' => ['DATE_FORMAT(pay_date, "%Y-%m-%d %H")'],
//            'orderBy' => 'hour ASC',
//        ]);

//        $membrane[] = [
//            'totalFee' => '10',
//            'hour' => '2018-06-25 03',
//        ];
//        var_dump(self::arraySort(self::arrayMerge($order, $membrane, 'hour'), 'hour'));exit;

//        return self::arraySort(self::arrayMerge($order, $membrane, 'hour'), 'hour');
        return self::arraySort($order, 'hour');
    }

    // 获取地区数据
    public static function getSalesArea($date)
    {
        $endDate = date('Y-m-d', strtotime ("+1 day", strtotime($date)));
        $order = OrderAR::find()
            ->from(OrderAR::tableName().' O')
            ->select(['C.district_province_id', 'sum(O.total_fee) as totalFee'])
            ->leftJoin(CustomUserAR::tableName(). ' C', 'O.custom_user_id = C.id')
            ->where(['O.status' => self::getOrderStatus()])
            ->andWhere(['between', 'pay_datetime', $date, $endDate])
            ->groupBy('C.district_province_id')
            ->orderBy('totalFee DESC')
            ->asArray()
            ->all();
//        $membrane = MembraneOrderAR::find()
//            ->from(MembraneOrderAR::tableName().' M')
//            ->select(['C.district_province_id', 'sum(M.total_fee) as totalFee'])
//            ->leftJoin(CustomUserAR::tableName(). ' C', 'M.custom_user_id = C.id')
//            ->where(['M.status' => MembraneOrder::$activeStatus])
//            ->andWhere(['between', 'pay_date', $date, $endDate])
//            ->groupBy('C.district_province_id')
//            ->orderBy('totalFee DESC')
//            ->asArray()
//            ->all();

//        $membrane[] = [
//            'district_province_id' => '23',
//            'totalFee' => 112233,
//        ];

//        return self::arraySort(self::arrayMerge($order, $membrane, 'district_province_id'), 'totalFee', 'desc');
        return self::arraySort($order, 'totalFee', 'desc');
    }

    // 获取供应商累计营业额排行榜
    public static function getTopSalesSupply($date)
    {
        $supplyFilterIds = SupplyUserAR::find()->select('id')->where(['in', 'account', self::$supplyFilter])->asArray()->column();
        $endDate = date('Y-m-d', strtotime ("+1 day", strtotime($date)));

        return OrderAR::find()->select(['supply_user_id', 'sum(total_fee) as totalFee'])
            ->where(['status' => self::getOrderStatus()])
            ->andWhere(['between', 'pay_datetime', $date, $endDate])
            ->andWhere(['not in', 'supply_user_id', $supplyFilterIds])
            ->groupBy('supply_user_id')
            ->orderBy('totalFee DESC')
            ->limit(self::$topLimit)
            ->asArray()
            ->all();
    }

    // 获取销售产品排行榜
    public static function getTopProducts($date)
    {
        $supplyFilterIds = array_map(function ($id) {
            return intval($id);
        }, SupplyUserAR::find()->select('id')->where(['in', 'account', self::$supplyFilter])->asArray()->column());
        $endDate = date('Y-m-d', strtotime ("+1 day", strtotime($date)));
        return Yii::$app->db->createCommand("
            SELECT I.`product_sku_id`, SUM(I.`total_fee`) AS totalFee
            FROM {{%order_item}} AS I
            LEFT JOIN {{%order}} AS O ON I.`order_id` = O.`id`
            WHERE O.`pay_datetime` BETWEEN :date AND :endDate
            AND O.`status` IN (1,2,3,5)
            AND O.`supply_user_id` NOT IN (:id0, :id1, :id2, :id3)
            GROUP BY I.`product_sku_id`
            ORDER BY totalFee DESC
            LIMIT :showLimit;
        ", [':date' => $date, ':endDate' => $endDate, ':id0' => $supplyFilterIds[0], ':id1' => $supplyFilterIds[1],
            ':id2' => $supplyFilterIds[2], ':id3' => $supplyFilterIds[3], ':showLimit' => self::$topLimit,
        ])->queryAll();
    }

    private static function arrayMerge($order, $membrane, $commonKey)
    {
        $orderHours = [];
        foreach ($order as $k) {
            $orderHours[] = $k[$commonKey];
        }
        foreach ($order as $ok => $ov) {
            foreach ($membrane as $mk => $mv) {
                if (!in_array($mv[$commonKey], $orderHours)) {
                    $order[] = [
                        'totalFee' => $mv['totalFee'],
                        $commonKey => $mv[$commonKey]
                    ];
                    $orderHours[] = $mv[$commonKey];
                    break;
                }
                if ($ov[$commonKey] == $mv[$commonKey]) {
                    $order[$ok]['totalFee'] += $mv['totalFee'];
                    break;
                }
            }
        }
        return $order;
    }

    private static function arraySort($array, $keys, $sort = 'asc')
    {
        $newArr = $valArr = array();
        foreach ($array as $key => $value) {
            $valArr[$key] = $value[$keys];
        }
        ($sort == 'asc') ?  asort($valArr) : arsort($valArr);
        reset($valArr);
        foreach ($valArr as $key => $value) {
            $newArr[$key] = $array[$key];
        }
        return $newArr;
    }
}
