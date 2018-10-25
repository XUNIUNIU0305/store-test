<?php
namespace mobile\modules\temp\controllers;

use Yii;
use common\controllers\Controller;

class InviteController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'confirm' => [null, 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionConfirm(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }else{
            return $this->render('confirm');
        }
    }
}
