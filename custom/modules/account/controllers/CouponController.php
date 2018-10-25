<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/25
 * Time: 15:38
 */

namespace custom\modules\account\controllers;




use common\controllers\Controller;
use custom\modules\account\models\CouponModel;

class CouponController extends Controller
{


    protected $access=[
        'index'=>['@','get'],//优惠券首页
        'active'=>['@','get'],//激活优惠券
        'get-ticket-info'=>['@','get'],//获取优惠券信息
        'get-ticket-list'=>['@','get'],//获取优惠券列表
        'active-ticket'=>['@','post'],//激活优惠券
    ];

    protected $actionUsingDefaultProcess=[
        'get-ticket-info'=>CouponModel::SCE_GET_TICKET_INFO,
        'get-ticket-list'=>CouponModel::SCE_GET_TICKET_LIST,
        'active-ticket'=>CouponModel::SCE_VALIDATE_TICKET,
        '_model' => '\custom\modules\account\models\CouponModel',
    ];


    //会员优惠券首页
    public function actionIndex(){
        return $this->render('index');
    }

    //激活优惠券
    public function actionActive(){
        return $this->render('active');
    }
}