<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-6
 * Time: 上午9:56
 */

namespace admin\modules\homepage\controllers;


use admin\modules\homepage\models\WapModel;
use admin\controllers\Controller;

class WapController extends Controller
{
    protected $access = [
        'index'  => ['@', 'get'],
        'create' => ['@', 'post'],
        'update' => ['@', 'post'],
        'delete' => ['@', 'post'],
        'sort'   => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'index'  => WapModel::SCE_GET_LIST,
        'create' => WapModel::SCE_CREATE,
        'update' => WapModel::SCE_UPDATE,
        'delete' => WapModel::SCE_DELETE,
        'sort'   => WapModel::SCE_SORT,
        '_model' => WapModel::class
    ];
}