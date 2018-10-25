<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午1:47
 */

namespace mobile\modules\lottery\controllers;


use mobile\modules\lottery\models\RecordModel;

class RecordController extends Controller
{
    protected $access = [
        'index'     => ['@', 'get'],
        'winning'   => ['@', 'get'],
        'arms'    => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'winning'   => RecordModel::SCE_WINNING,
        'arms'    => RecordModel::SCE_ARMS,
        '_model'    => RecordModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}