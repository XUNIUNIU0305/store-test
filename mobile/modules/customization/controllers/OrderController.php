<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/8/1 0001
 * Time: 10:17
 */

namespace mobile\modules\customization\controllers;

use common\models\parts\OrderCustomization;
use mobile\modules\customization\models\OrderModel;

class OrderController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'view' => ['@', 'get'],
        'one' => ['@', 'get'],
        'cancel' => ['@', 'post'],
        'search' => ['@', 'get'],
        'status' => ['@', 'get'],
        'brand' => ['@', 'get'],
        'types' => ['@', 'get'],
        'upload' => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'one' => OrderModel::SCE_VIEW,
        'search' => OrderModel::SCE_SEARCH,
        'cancel' => OrderModel::SCE_CANCEL,
        'brand' => OrderModel::SCE_BRAND,
        'types' => OrderModel::SCE_BRAND_TYPE,
        'upload' => OrderModel::SCE_UPLOAD,
        '_model' => OrderModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionStatus()
    {
        return $this->success(OrderCustomization::$status);
    }
}