<?php
namespace custom\controllers;

use Yii;
use common\controllers\Controller;
use custom\models\TradeModel;
use yii\web\NotFoundHttpException;

class TradeController extends Controller{

    public $layout = 'header_footer_order';

    protected $access = [
        'alipay' => ['@', 'get'],
        'balance' => ['@', 'get'],
    ];

    public function actionBalance(){
        if($balanceReturn = TradeModel::getBalanceReturn()){
            return $this->render('return_success', $balanceReturn);
        }else{
            throw new NotFoundHttpException;
        }
    }

    public function actionAlipay(){
        if($alipayReturn = TradeModel::getAlipayReturn()){
            return $this->render('return_success', $alipayReturn);
        }else{
            throw new NotFoundHttpException;
        }
    }
}
