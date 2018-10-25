<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 14:41
 */

namespace admin\modules\service\controllers;


use admin\controllers\Controller;
use admin\modules\service\models\OrderRefundModel;

class RefundController extends Controller
{

    protected $access = [
        'index' => ['@', 'get'],
        'order-list'=>['@','get'],//退换订单列表
        'order-info'=>['@','get'],//退换货订单信息
        'check-order'=>['@','post'],//审核退换货申请
        'add-comments'=>['@','post'],//添加备注
        'refund-rmb' => ['@', 'post'], //执行退款
        'cancel-refund' => ['@', 'post'], //取消退换单
        'install-custom-sending' => ['@', 'post'], //代替客户发货
    ];

    protected $actionUsingDefaultProcess = [
        'order-list' => OrderRefundModel::SCE_GET_REFUND_ORDER_LIST,
        'order-info'=>OrderRefundModel::SCE_GET_REFUND_ORDER_INFO,
        'check-order'=>OrderRefundModel::SCE_CHECK_REFUND_ORDER,
        'add-comments'=>OrderRefundModel::SCE_ADD_COMMENTS,
        'refund-rmb' => OrderRefundModel::SCE_REFUND_RMB,
        'cancel-refund' => OrderRefundModel::SCE_CANCEL_REFUND,
        'install-custom-sending' => OrderRefundModel::SCE_INSTALL_CUSTOM_SENDING,
        '_model' => '\admin\modules\service\models\OrderRefundModel',
    ];


    public function actionIndex(){
        return $this->render('index');
    }

}
