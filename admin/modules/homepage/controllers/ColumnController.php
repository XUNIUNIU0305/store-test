<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 上午11:57
 */

namespace admin\modules\homepage\controllers;


use admin\modules\homepage\models\ColumnModel;
use admin\controllers\Controller;

class ColumnController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
        'add' => ['@', 'post'],
        'update' => ['@', 'post'],
        'delete' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => ColumnModel::SCE_COLUMN_LIST,
        'add' => ColumnModel::SCE_COLUMN_ADD,
        'update' => ColumnModel::SCE_COLUMN_UPDATE,
        'delete' => ColumnModel::SCE_COLUMN_DELETE,
        '_model' => ColumnModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}