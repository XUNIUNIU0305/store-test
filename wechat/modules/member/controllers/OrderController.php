<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 14:27
 */

namespace wechat\modules\member\controllers;

use wechat\controllers\Controller;
use wechat\modules\member\models\OrderModel;

class OrderController extends Controller
{


    protected $access=[
        'index'=>['@','get'],
        'get-order-quantity'=>['@','get'],
        'get-order-list'=>['@','get'],
        'get-order-info'=>['@','get'],
    ];


    protected $actionUsingDefaultProcess=[
        'get-order-list'=>OrderModel::SCE_GET_LIST,
        'get-order-info'=>OrderModel::SCE_GET_ORDER_INFO,
        '_model'=>'mobile\modules\member\models\OrderModel',
    ];
    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:订单列表展示
     * @return string
     */
    public function actionIndex(){
        return $this->render("index");
    }

    /**
     * Author:JiangYi
     * Date:2017/05/19
     * Desc:获取订单数量信息
     * @return \common\controllers\json
     */
    public function actionOrderQuantity(){
        return  $this->success(OrderModel::getOrderQuantity());
    }


    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:订单详情展示
     * @return string
     */
    public function actionDetail(){
        return $this->render('detail');
    }


}