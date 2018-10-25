<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 下午3:10
 */

namespace business\modules\data\controllers;

use business\modules\data\models\RealTimeModel;

class RealTimeController extends Controller
{
    protected $access = [
        'index' => ['20', 'get'],
        'total-preview' => ['20', 'get'],
        'top-product' => ['20', 'get'],
        'top-price' => ['20', 'get'],
        'top-brand' => ['20', 'get'],
        'area' => ['20', 'get'],
        'store' => ['20', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'total-preview' => RealTimeModel::SCE_TOTAL_PREVIEW,
        'top-product' => RealTimeModel::SCE_DAY_TOP_PRODUCT,
        'top-price' => RealTimeModel::SCE_DAY_TOP_PRICE,
        'top-brand' => RealTimeModel::SCE_DAY_TOP_BRAND,
        'area' => RealTimeModel::SCE_DAY_AREA,
        'store' => RealTimeModel::SCE_DAY_STORE,
        '_model' => RealTimeModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}
