<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/28 0028
 * Time: 9:42
 */

namespace custom\modules\account\controllers;


use common\controllers\Controller;
use common\models\parts\MembraneOrder;
use custom\modules\account\models\MembraneModel;

class MembraneController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'search' => ['@', 'get'],
        'status' => ['@', 'get'],
        'cancel' => ['@', 'post'],
        'pay' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search' => MembraneModel::SCE_SEARCH,
        'pay' => MembraneModel::SCE_PAY,
        'cancel' => MembraneModel::SCE_CANCEL,
        '_model' => MembraneModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionStatus()
    {
        return $this->success(MembraneOrder::$status);
    }
}