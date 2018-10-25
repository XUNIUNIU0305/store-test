<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-31
 * Time: 上午10:13
 */

namespace admin\modules\homepage\controllers;

use admin\modules\homepage\models\ColumnBrandModel;
use admin\controllers\Controller;

class ColumnBrandController extends Controller
{
    protected $access = [
        'index'  => ['@', 'get'],
        'add'    => ['@', 'post'],
        'update' => ['@', 'post'],
        'delete' => ['@', 'post'],
        'one'    => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'index'  => ColumnBrandModel::SCE_LIST,
        'add'    => ColumnBrandModel::SCE_ADD,
        'update' => ColumnBrandModel::SCE_UPDATE,
        'delete' => ColumnBrandModel::SCE_DELETE,
        'one'    => ColumnBrandModel::SCE_GET_BRAND,
        '_model' => ColumnBrandModel::class
    ];
}