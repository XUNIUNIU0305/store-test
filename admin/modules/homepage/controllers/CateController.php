<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-9
 * Time: 下午3:42
 */

namespace admin\modules\homepage\controllers;


use admin\controllers\Controller;
use admin\modules\homepage\models\CateModel;

class CateController extends Controller
{
    protected $access = [
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'index' => CateModel::SCE_GET_LIST,
        '_model' => CateModel::class
    ];
}