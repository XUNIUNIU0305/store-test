<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/7
 * Time: 上午11:16
 */

namespace admin\modules\info\controllers;


use admin\controllers\Controller;
use admin\modules\info\models\OrderModel;

class OrderController extends Controller
{
    protected $access = [
        'index'=>['@', 'get'],
        'list'=>['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => OrderModel::SCE_ORDER_LIST,
        '_model' => '\admin\modules\info\models\OrderModel',
    ];


    //订单列表
    public function actionIndex(){
        return $this->render('index');
    }
}