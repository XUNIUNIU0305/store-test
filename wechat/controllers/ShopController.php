<?php
/**
 * User: JiangYi
 * Date: 2017/5/27
 * Time: 12:48
 * Desc:
 */

namespace wechat\controllers;

use wechat\models\ShopModel;

class ShopController extends Controller
{
    public $access=[
        'get-supply-info'=>[null,'get'],//获取商户信息
    ];

    public $actionUsingDefaultProcess=[
        'get-supply-info'=>ShopModel::SCE_GET_SUPPLY_INFO,
        '_model'=>'mobile\models\ShopModel',
    ];




}