<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/4
 * Time: 10:23
 */

namespace wechat\controllers;

use wechat\models\ConfirmOrderModel;

class ConfirmOrderController extends Controller
{
    protected $access = [
        'list'      => ['@', 'get'],
        'confirm'   => ['@', 'post'],
        'payment'   => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'list'      => ConfirmOrderModel::SCE_GET_LIST,
        'confirm'   => ConfirmOrderModel::SCE_GENERATE_TRADE,
        '_model'    => ConfirmOrderModel::class
    ];

    public function actionPayment(){
        return $this->success(ConfirmOrderModel::getPaymentMethod());
    }
}