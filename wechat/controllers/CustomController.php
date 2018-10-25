<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/13
 * Time: 18:11
 */

namespace wechat\controllers;


use wechat\models\CustomModel;

class CustomController extends Controller
{
    protected $access = [
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'index' => CustomModel::SCE_GET_LIST,
        '_model' => CustomModel::class
    ];
}