<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 13:47
 * Desc:
 */

namespace wechat\modules\member\controllers;

use custom\modules\account\models\CouponModel;
use wechat\controllers\Controller;

class CouponController extends Controller
{


    protected $access=[
        'get-coupon-list'=>['@','get'],//获取用户优惠券列表
        'index'=>['@','get'],//优惠券列表
        'validate-ticket'=>['@','post'],//激活优惠券
        'get-coupon-info'=>['@','get'],//获取优惠券信息

    ];

    protected  $actionUsingDefaultProcess=[
        'get-coupon-list'=>CouponModel::SCE_GET_TICKET_LIST,
        'validate-ticket'=>CouponModel::SCE_VALIDATE_TICKET,
        'get-coupon-info'=>CouponModel::SCE_GET_TICKET_INFO,
        '_model'=>'custom\modules\account\models\CouponModel',
    ];


    /**
     * Author:JiangYi
     * Date:2017/5/24
     * Desc:优惠券列表信息
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
    }


    /**
     * Author:JiangYi
     * Date:2017/5/24
     * Desc:优惠券激活页面
     * @return string
     */
    public function actionActive(){
        return $this->render('active');
    }

}