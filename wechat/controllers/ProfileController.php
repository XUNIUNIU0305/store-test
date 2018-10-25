<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/19 0019
 * Time: 15:57
 */

namespace wechat\controllers;


use wechat\models\ProfileModel;

class ProfileController extends Controller
{
    protected $access = [
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'index' => ProfileModel::SCE_GET_INFO,
        '_model' => ProfileModel::class
    ];
}