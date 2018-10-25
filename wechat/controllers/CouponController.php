<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/17
 * Time: 16:50
 */

namespace wechat\controllers;


use wechat\models\CouponModel;

class CouponController extends Controller
{
    protected $access = [
        'list' => ['@','get'],        //获取用户优惠券列表
        'validate' => ['@', 'get'],
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'list' => CouponModel::SCE_GET_TICKET_LIST,
        'index' => CouponModel::SCE_GET_TICKET_INFO,
        'validate' => CouponModel::SCE_VALIDATE_TICKET,
        '_model' => CouponModel::class
    ];
}