<?php
namespace mobile\modules\member\controllers;

use Yii;
use mobile\modules\member\models\DjyModel;

class DjyController extends Controller{

    protected $access = [
        'user' => [null, 'get'],
        'order' => [null, 'get'],
        'total' => ['@', 'get'],
        'sku' => ['@', 'get'],
        'store-list' => ['@', 'get'],
        'order-list' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'total' => DjyModel::SCE_GET_TOTAL,
        'sku' => DjyModel::SCE_GET_SKU,
        'store-list' => DjyModel::SCE_GET_STORE_LIST,
        'order-list' => DjyModel::SCE_GET_ORDER_LIST,
        '_model' => DjyModel::class,
    ];

    public function actionUser(){
        return $this->render('user');
    }

    public function actionOrder(){
        return $this->render('order');
    }
}
