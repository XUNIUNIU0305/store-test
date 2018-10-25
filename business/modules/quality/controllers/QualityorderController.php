<?php
/**
 * User: JiangYi
 * Date: 2017/5/28
 * Time: 10:43
 * Desc:
 */

namespace business\modules\quality\controllers;


use business\controllers\Controller;
use business\modules\quality\models\CreateModel;
use business\modules\quality\models\QualityOrderModel;

class QualityorderController extends Controller
{
    protected $access = [
        'index'=>['239','get'],//首页
        'create'=>['!50','get'],//新增
        'detail'=>['239','get'],//详情

        'create-order'=>['!50','post'],//创建订单
        'search'=>['239','get'],//订单搜索
        'get-order-info'=>['239','get'],//获取订单详情
        'get-technican-list'=>['50','get'],//获取技师列表
    ];


    protected $actionUsingDefaultProcess = [
        'create-order' => QualityOrderModel::SCE_CREATE_ORDER,
        'search'=>[
            'scenario'=>QualityOrderModel::SCE_GET_ORDER_LIST,
            'convert'=>false,
        ],
        'get-order-info'=>[
            'scenario'=>QualityOrderModel::SCE_GET_ORDER_INFO,
            'convert'=>false,
        ],
        'get-technican-list'=>QualityOrderModel::SCE_GET_TECHNICAN,
        '_model' => '\business\modules\quality\models\QualityOrderModel',
    ];


    public function actionIndex(){
        return $this->render('index');
    }


    public function actionCreate(){
        return $this->render('create');
    }


    public function actionDetail(){
        return $this->render('detail');
    }

}
