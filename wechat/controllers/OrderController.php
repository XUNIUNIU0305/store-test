<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/19 0019
 * Time: 15:26
 */

namespace wechat\controllers;


use wechat\models\OrderModel;

class OrderController extends Controller
{
    protected $access = [
        'list' => ['@', 'get'],
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'list' => OrderModel::SCE_GET_LIST,
        'index' => OrderModel::SCE_GET_ORDER_INFO,
        '_model' => OrderModel::class
    ];
}