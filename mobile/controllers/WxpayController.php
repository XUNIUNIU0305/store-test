<?php
namespace mobile\controllers;

use Yii;
use mobile\models\WxpayModel;
use yii\web\ForbiddenHttpException;

class WxpayController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public $layout = 'global';

    public function actionIndex(){
        if($params = WxpayModel::getPayParams()){
            return $this->render('index', ['params' => $params]);
        }else{
            throw new ForbiddenHttpException;
        }
    }
}
