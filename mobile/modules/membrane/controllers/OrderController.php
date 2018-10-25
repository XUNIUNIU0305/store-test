<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/8/3 0003
 * Time: 11:11
 */

namespace mobile\modules\membrane\controllers;


use common\models\parts\MembraneOrder;
use mobile\modules\membrane\models\OrderModel;

class OrderController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
        'status' => ['@', 'get'],
        'detail' => ['@', 'get'],
        'cancel' => ['@', 'post'],
        'view' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'list' => OrderModel::SCE_LIST,
        'view' => OrderModel::SCE_VIEW,
        'cancel' => OrderModel::SCE_CANCEL,
        '_model' => OrderModel::class
    ];

    /**
     * 货单列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionStatus()
    {
        return $this->success(MembraneOrder::$status);
    }

    /**
     * 详情
     * @return string
     */
    public function actionDetail()
    {
        return $this->render('detail');
    }
}