<?php
/**
 * User: JiangYi
 * Date: 2017/5/27
 * Time: 15:55
 * Desc:
 */

namespace wechat\controllers;

use wechat\models\TradeModel;
use yii\web\NotFoundHttpException;

class TradeController extends Controller
{

    public $access=[
        'balance'=>['@','get'],
        'get-order-list'=>['@','get'],//获取交易单中的订单列表
    ];

    public $actionUsingDefaultProcess=[
        'get-order-list'=>TradeModel::SCE_GET_ORDER_LIST,
        '_model'=>'mobile\models\TradeModel',
    ];



    public function actionBalance(){
        if($balanceReturn = TradeModel::getBalanceReturn()){
            return $this->render('balance', $balanceReturn);
        }else{
            throw new NotFoundHttpException;
        }

    }

}