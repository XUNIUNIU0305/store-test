<?php
namespace business\controllers;

use Yii;
use business\models\MainModel;

class MainController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'logout' => [null, 'get'],
        'menu' => ['@', 'get'],
        'user-info' => ['@', 'get'],
    ];

    public $layout = 'main';

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionLogout(){
        if(!Yii::$app->user->isGuest){
            MainModel::logout();
        }
        Yii::$app->user->loginRequired();
    }

    public function actionMenu(){
        return $this->success(MainModel::getMenu());
    }

    public function actionUserInfo(){
        return $this->success(MainModel::getUserInfo());
    }
}
