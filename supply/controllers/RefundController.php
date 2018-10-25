<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 10:27
 */

namespace supply\controllers;
use common\controllers\Controller;
use supply\models\RefundModel;

class RefundController extends Controller
{

    protected $access = [
        'index' => ['@', 'get'],
        'detail'=>['@','get'],
        'get-refund-list'=>['@','get'],//获取商户退换订单列表
        'agree-custom-refund'=>['@','post'],//同意客户退换
        'supply-send-back'=>['@','post'],//商户发出换货
        'agree-refund-money'=>['@','post'],//商户同意退款
        'get-refund-order-info'=>['@','get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-refund-list'=>RefundModel::SCE_GET_REFUND_LIST,
        'agree-custom-refund'=>RefundModel::SCE_SUPPLY_AGREE_REFUND,
        'supply-send-back'=>RefundModel::SCE_SUPPLY_SEND_BACK,
        'agree-refund-money'=>RefundModel::SCE_AGREE_REFUND_MONEY,
        'get-refund-order-info'=>RefundModel::SCE_GET_REFUND_ORDER_INFO,
         '_model' => 'supply\models\RefundModel',
    ];

    //退换货首页
    public function actionIndex(){
        return $this->render('index');
    }

    //退换货详情
    public function actionDetail(){
        $this->layout="refund_header";
        return $this->render('detail');
    }

}