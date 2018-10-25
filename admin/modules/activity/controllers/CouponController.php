<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/25
 * Time: 15:21
 */

namespace admin\modules\activity\controllers;


use admin\controllers\Controller;
use admin\modules\activity\models\CouponModel;


class CouponController extends  Controller
{

    protected $access=[
        'index'=>['@','get'],//首页列表
        'detail'=>['@','get'],//详情
        'create'=>['@','get'],//创建优惠券
        'create-coupon'=>['@','post'],//生成优惠券
        'get-coupon-list'=>['@','get'],//获取优惠券列表
        'get-coupon-info'=>['@','get'],//获取优惠券详情
        'delete-coupon'=>['@','get'],//删除优惠券信息
        'get-ticket-list'=>['@','get'],//获取惠券领取（分发）记录
        'send-ticket'=>['@','post'],//发送优惠券至个人
        'create-ticket'=>['@','post'],//创建实体券
        'create-rule'=>['@','post'],//创建规则
        'add-quantity'=>['@','post'],//新增发行量
        'cancel-ticket'=>['@','post'],//注销优惠券
        'export'=>['@','get'],//导出excel

    ];

    protected $actionUsingDefaultProcess=[
        'create-coupon'=>CouponModel::SCE_CREATE_COUPON,
        'get-coupon-list'=>CouponModel::SCE_GET_COUPON_LIST,
        'get-coupon-info'=>CouponModel::SCE_GET_COUPON_INFO,
        'delete-coupon'=>CouponModel::SCE_DELETE_COUPON,
        'send-ticket'=>CouponModel::SCE_SEND_TICKET_TO_PERSON,
        'get-ticket-list'=>CouponModel::SCE_GET_TICKET_LIST,
        'create-ticket'=>CouponModel::SCE_CREATE_TICKET,
        'create-rule'=>CouponModel::SCE_CREATE_RULE,
        'add-quantity'=>CouponModel::SCE_ADD_QUANTITY,
        'cancel-ticket'=>CouponModel::SCE_CANCEL_TICKET,
        'export'=>CouponModel::SCE_EXPORT,
        '_model' => '\admin\modules\activity\models\CouponModel',
    ];

    //首页列表
    public function actionIndex(){
        return $this->render('index');
    }

    //详情
    public function actionDetail(){
        return $this->render('detail');
    }

    //创建
    public function actionCreate(){
        return $this->render('create');
    }


}