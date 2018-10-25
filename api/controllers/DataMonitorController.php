<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 19/06/18
 * Time: 11:21
 */

namespace api\controllers;

use api\components\handler\DataMonitorHandler;
use api\models\DataMonitorModel;
use common\controllers\Controller;
use Yii;

class DataMonitorController extends Controller
{
    protected $access = [
        'day-total-sales' => [null, 'get'], // 当日全国累计销售总额
        'month-total-sales' => [null, 'get'], // 当月全国累计销售总额
        'province-day-sales' => [null, 'get'], // 当日各省累计销售总额
        'province-month-sales' => [null, 'get'], // 当月各省累计销售总额
        'sales-amount-speed' => [null, 'get'], // 分时销售情况
        'sales-area' => [null, 'get'], // 全国销售地图
        'top-sales-supply' => [null, 'get'], // 供应商销售排行榜
        'top-products' => [null, 'get'], // 销售产品排行榜
    ];

    protected $actionUsingDefaultProcess = [
        'day-total-sales' => DataMonitorModel::DAY_TOTAL_SALES,
        'month-total-sales' => DataMonitorModel::MONTH_TOTAL_SALES,
        'province-day-sales' => DataMonitorModel::PROVINCE_DAY_SALES,
        'province-month-sales' => DataMonitorModel::PROVINCE_MONTH_SALES,
        'sales-amount-speed' => DataMonitorModel::SALES_AMOUNT_SPEED,
        'sales-area' => DataMonitorModel::SALES_AREA,
        'top-sales-supply' => DataMonitorModel::TOP_SALES_SUPPLY,
        'top-products' => DataMonitorModel::TOP_PRODUCTS,
        '_model' => '\api\models\DataMonitorModel'
    ];

    protected function returnJson($code, $param, $convert)
    {
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($convert) {
            $param = $this->convertNumericType($param);
        }
        return $param;
    }
}