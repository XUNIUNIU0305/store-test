<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 10:38
 */

namespace custom\modules\membrane\controllers;

use custom\modules\membrane\models\HomeModel;

class HomeController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'address' => ['@', 'get'],
        'payment' => ['@', 'get'],
        'order' => ['@', 'post'],
        'status' => ['@', 'get'],
        'balance' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'address' => HomeModel::SCE_ADDRESS,
        'payment' => HomeModel::SCE_PAYMENT,
        'order' => HomeModel::SCE_ORDER,
        'status' => HomeModel::SCE_STATUS,
        'balance' => HomeModel::SCE_BALANCE,
        '_model' => HomeModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}