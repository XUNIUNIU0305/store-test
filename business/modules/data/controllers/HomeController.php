<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 上午11:10
 */

namespace business\modules\data\controllers;


use business\modules\data\models\HomeModel;

class HomeController extends Controller
{
    protected $access = [
        'index' => ['200', 'get'],
        'conversion-rate' => ['200', 'get'],
        'total-consume' => ['200', 'get'],
        'shop-cart' => ['200', 'get'],
        'is-custom' => ['200', 'get'],
        'time-amount' => ['200', 'get'],
        'store' => ['200', 'get'],
        'level' => ['200', 'get'],
        'self-area' => ['200', 'get'],
        'user-level' => ['200', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'conversion-rate' => HomeModel::SCE_CONVERSION_RATE,
        'total-consume' => HomeModel::SCE_TOTAL_CONSUME,
        'shop-cart' => HomeModel::SCE_SHOP_CART,
        'is-custom' => HomeModel::SCE_IS_CUSTOMIZATION,
        'time-amount' => HomeModel::SCE_TIME_AMOUNT,
        'store' => HomeModel::SCE_STORE,
        'level' => HomeModel::SCE_AREA_LEVEL,
        'self-area' => HomeModel::SCE_SELF_AREA,
        'user-level' => HomeModel::SCE_USER_LEVEL,
        '_model' => HomeModel::class
    ];


    public function actionIndex()
    {
        return $this->render('index');
    }
}