<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 19/06/18
 * Time: 11:26
 */

namespace api\models;

use api\components\handler\DataMonitorHandler;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSKUAR;
use common\models\Model;
use common\models\parts\district\Province;
use common\models\parts\Supplier;
use yii\helpers\ArrayHelper;

class DataMonitorModel extends Model
{
    const DAY_TOTAL_SALES = 'day_total_sales';
    const MONTH_TOTAL_SALES = 'month_total_sales';
    const PROVINCE_DAY_SALES = 'province_day_sales';
    const PROVINCE_MONTH_SALES = 'province_month_sales';
    const SALES_AMOUNT_SPEED = 'sales_amount_speed';
    const SALES_AREA = 'sales_area';
    const TOP_SALES_SUPPLY = 'top_sales_supply';
    const TOP_PRODUCTS = 'top_products';

    public function scenarios()
    {
        return [
            self::DAY_TOTAL_SALES => [],
            self::MONTH_TOTAL_SALES => [],
            self::PROVINCE_DAY_SALES => [],
            self::PROVINCE_MONTH_SALES => [],
            self::SALES_AMOUNT_SPEED => [],
            self::SALES_AREA => [],
            self::TOP_SALES_SUPPLY => [],
            self::TOP_PRODUCTS => [],
        ];
    }

    // 当日全国累计销售总额
    public function dayTotalSales()
    {
        $date = date('Y-m-d');
        $sum = DataMonitorHandler::getTotalSales($date, 'day');
        return [
            [
                'name'  => '',
                'value' => $sum
            ]
        ];
    }

    // 当月全国累计销售总额
    public function monthTotalSales()
    {
        $date = date('Y-m-d');
        $sum = DataMonitorHandler::getTotalSales($date, 'month');
        return [
            [
                'name'  => '',
                'value' => $sum
            ]
        ];
    }

    // 当日各省累计销售总额
    public function provinceDaySales()
    {
        $date = date('Y-m-d');
        $res = DataMonitorHandler::getProvinceSales($date, 'day');
        foreach ($res as $key => $val) {
            $val['y'] = round($val['y'] / 100) / 100;
            $res[$key] = $val;
        }
        $res = empty($res) ? [["x" => "", "y" => 0]] : $res;
        return $res;
    }

    // 当月各省累计销售总额
    public function provinceMonthSales()
    {
        $date = date('Y-m-d');
        $res = DataMonitorHandler::getProvinceSales($date, 'month');
        foreach ($res as $key => $val) {
            $val['y'] = round($val['y'] / 100) / 100;
            $res[$key] = $val;
        }
        $res = empty($res) ? [["x" => "", "y" => 0]] : $res;
        return $res;
    }

    // 销售额增速(分时销售)
    public function salesAmountSpeed()
    {
        $date = date('Y-m-d H:i:s');
        // $date  = '2018-06-25 09:47:19';

        // 获取各小时内总销售金额(未消费没有数据)
        $hourSaleAmountSpeed = DataMonitorHandler::getHourTotalFee($date);
        array_shift($hourSaleAmountSpeed);
        array_shift($hourSaleAmountSpeed);

        $startDate = strtotime('-1 days', strtotime($date)) + 2 * 3600;
        $date = strtotime($date);
        for ($i = $startDate; $i <= $date; $i += 3600) {
            $withinHour24[] = date('Y-m-d H', $i);
        }

        // 没有销售额的时间点
        $diffArr = array_diff($withinHour24, array_map(function($v) {
            return $v;
        }, array_column($hourSaleAmountSpeed, 'hour')));
        foreach ($diffArr as $value) {
            $hourSaleAmountSpeed[] = [
                'totalFee' => 0,
                'hour' => $value
            ];
        }
        ArrayHelper::multisort($hourSaleAmountSpeed, 'hour');

        $saleSpeed = [];
        foreach ($hourSaleAmountSpeed as $key => $item) {
            $saleSpeed[$key] = [
                'x' => bcadd(explode(' ', $item['hour'])[1], 1),
                'y' => round($item['totalFee'] / 100) / 100,
                's' => 1
            ];
        }
        // ArrayHelper::multisort($saleSpeed,'x');
        return $saleSpeed;
    }

    // 全国销售地图
    public function salesArea()
    {
        $date = date('Y-m-d');
        $provinceHotArea = DataMonitorHandler::getSalesArea($date);
        $hotAreaProvinceIdAndTotalFee =  array_column($provinceHotArea, 'totalFee', 'district_province_id');
        // 最大销售额省份
        $maxProvince = current($hotAreaProvinceIdAndTotalFee);

        $hotArea = array_map(function($data) use($maxProvince) {
            return [
                'id' => (new Province(['provinceId' => $data['district_province_id']]))->adCode,
                'value' => bcdiv($data['totalFee'], $maxProvince, 10),
            ];
        }, $provinceHotArea);
        return $hotArea;
    }


    // 供应商销售排行榜
    public function topSalesSupply()
    {
        $date = date('Y-m-d');
        $supplyUserIdTotalFeeArr = DataMonitorHandler::getTopSalesSupply($date);
        $supplySale = array_map(function ($data) {
            return [
                'x' => (new Supplier(['id' => $data['supply_user_id']]))->brandName,
                'y' => round($data['totalFee'] / 100) / 100,
            ];
        }, $supplyUserIdTotalFeeArr);
        return $supplySale;
    }

    // 销售产品排行榜
    public function topProducts()
    {
        $date = date('Y-m-d');
        $topProducts = DataMonitorHandler::getTopProducts($date);
        $topProducts = array_map(function ($data) {
            $title = ProductSKUAR::find()
                ->from(ProductSKUAR::tableName() . ' S')
                ->select(['P.title'])
                ->leftJoin(ProductAR::tableName() . ' P', 'P.id = S.product_id')
                ->where(['S.id' => $data['product_sku_id']])->scalar();
            return [
                'x' => mb_substr($title, 0, 10) . '...',
                'y' => round($data['totalFee'] / 100) / 100,
                's' => 1
            ];
        }, $topProducts);
        return $topProducts;
    }
}