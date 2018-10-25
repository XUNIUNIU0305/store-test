<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 16:21
 */

namespace business\modules\membrane\controllers;

use business\modules\membrane\models\HomeModel;
use common\controllers\Controller;
use common\models\parts\MembraneOrder;

class HomeController extends Controller
{
    protected $access = [
        'index' => ['!50', 'get'],
        'search' => ['!50', 'get'],
        'accept' => ['!50', 'post'],
        'finish' => ['!50', 'post'],
        'cancel' => ['!50', 'post'],
        'status' => ['!50', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search' => HomeModel::SCE_SEARCH,
        'accept' => HomeModel::SCE_ACCEPT,
        'finish' => HomeModel::SCE_FINISH,
        'cancel' => HomeModel::SCE_CANCEL,
        '_model' => HomeModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionStatus()
    {
        return $this->success(HomeModel::$statusLabel);
    }
}
