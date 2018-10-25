<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 上午11:22
 */

namespace business\modules\data\controllers;


use business\modules\data\models\DayModel;

class DayController extends Controller
{
    protected $access = [
        'index' => ['20', 'get'],
        'total-preview' => ['20', 'get'],
        'top-product' => ['20', 'get'],
        'top-price' => ['20', 'get'],
        'top-brand' => ['20', 'get'],
        'area' => ['20', 'get'],
        'store' => ['20', 'get'],
        'custom-consumption' => ['20', 'get'],
        'area-consumption' => ['20', 'get'],
        'day-register' => ['20', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'total-preview' => DayModel::SCE_TOTAL_PREVIEW,
        'top-product' => DayModel::SCE_DAY_TOP_PRODUCT,
        'top-price' => DayModel::SCE_DAY_TOP_PRICE,
        'top-brand' => DayModel::SCE_DAY_TOP_BRAND,
        'area' => DayModel::SCE_DAY_AREA,
        'store' => DayModel::SCE_DAY_STORE,
        'custom-consumption' => DayModel::SCE_DAY_CUSTOM_CONSUMPTION,
        'area-consumption' => DayModel::SCE_DAY_AREA_CONSUMPTION,
        'day-register' => DayModel::SCE_DAY_REGISTER,
        '_model' => DayModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}
