<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 下午3:51
 */

namespace admin\modules\homepage\controllers;


use admin\modules\homepage\models\ColumnItemModel;
use admin\controllers\Controller;

class ColumnItemController extends Controller
{
    protected $access = [
        'index'  => ['@', 'get'],
        'add'    => ['@', 'post'],
        'update' => ['@', 'post'],
        'delete' => ['@', 'post'],
        'bind'   => ['@', 'post'],
        'unbind' => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'index'  => ColumnItemModel::SCE_LIST,
        'add'    => ColumnItemModel::SCE_ADD,
        'update' => ColumnItemModel::SCE_UPDATE,
        'delete' => ColumnItemModel::SCE_DELETE,
        'bind'   => ColumnItemModel::SCE_BIND,
        'unbind' => ColumnItemModel::SCE_UNBIND,
        '_model' => ColumnItemModel::class
    ];
}