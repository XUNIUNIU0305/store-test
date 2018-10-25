<?php
/**
 * 定制
 */

namespace supply\controllers;

use common\controllers\Controller;
use supply\models\CustomModel;

class CustomController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'view'  => ['@', 'get'],
        'one'  => ['@', 'get'],
        'list'  => ['@', 'get'],
        'export' => ['@', 'get'],
        'note' => ['@', 'post'],
        'hold' => ['@', 'post'],
        'reject' => ['@', 'post'],
        'ship'  => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'list'  =>  CustomModel::SCE_GET_LIST,
        'export' => CustomModel::SCE_EXPORT_LIST,
        'one' => CustomModel::SCE_GET_VIEW,
        'note' => CustomModel::SCE_NOTE,
        'hold' => CustomModel::SCE_HOLD_ORDER,
        'reject' => CustomModel::SCE_REJECT_ORDER,
        'ship' => CustomModel::SCE_SHIP_ORDER,
        '_model'    => CustomModel::class
    ];

    /**
     * 订制订单列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 订制订单详情
     * @return string
     */
    public function actionView()
    {
        return $this->render('view');
    }
}