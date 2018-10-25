<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-8
 * Time: 下午5:03
 */

namespace admin\modules\homepage\controllers;


use admin\controllers\Controller;
use admin\modules\homepage\models\SupplyModel;

class SupplyController extends Controller
{
    protected $access = [
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'index'  => SupplyModel::SCE_GET_LIST,
        '_model' => SupplyModel::class
    ];
}