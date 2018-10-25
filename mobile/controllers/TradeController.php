<?php
/**
 * User: JiangYi
 * Date: 2017/5/27
 * Time: 15:55
 * Desc:
 */

namespace mobile\controllers;


use yii\web\NotFoundHttpException;
use mobile\models\TradeModel;

class TradeController extends Controller
{

    public $access=[
        'fail' => ['@', 'get'],
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

    public function actionFail(){
        return $this->render('fail');
    }
}
