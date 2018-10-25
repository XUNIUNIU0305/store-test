<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 11:28
 * Desc:退换货接口
 */

namespace custom\modules\account\controllers;


use common\controllers\Controller;
use custom\modules\account\models\RefundModel;
use Yii;

class RefundController extends Controller
{



    protected $access = [
        'index'=>['@','get'],//首页列表
        'create'=>['@','get'],//创建申请
        'detail'=>['@','get'],//详情
        'get-order-item'=>['@','get'],//获取订单项信息
        'create-request'=>['@','post'],//创建退换货申请单
        'get-list'=>['@','get'],//获取用户退货申请单列表
        'get-order-info'=>['@','get'],//获取退货详信息
        'send-package'=>['@','post'], //商家发货
        'finished'=>['@','post'],//完成结束退换订单
        'if-refund'=>['@','get'],//验证是否允许退换货
    ];

    protected $actionUsingDefaultProcess = [
        'create-request'=>RefundModel::SCE_CREATE_REQUEST,
        'get-list'=>RefundModel::SCE_GET_LIST,
        'get-order-info'=>RefundModel::SCE_GET_REFUND_ORDER_INFO,
        'send-package'=>RefundModel::SCE_SEND_PACKAGE,
        'finished'=>RefundModel::SCE_FINISHED_ORDER,
        'get-order-item'=>RefundModel::SCE_GET_ORDER_ITEM,
        'if-refund'=>RefundModel::SCE_CUSTOM_CAN_REFUND,
        '_model' => '\custom\modules\account\models\RefundModel',
    ];


    //退换货列表
    public function actionIndex(){
        return $this->render('index');
    }

    //退换货申请页
    public function actionCreate(){
        return $this->render('create');
    }

    //退换单详情
    public function actionDetail(){
        $this->layout='empty';
        return $this->render('detail');
    }





}