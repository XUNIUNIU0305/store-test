<?php
namespace business\controllers;

use Yii;
use business\models\UserModel;

class UserController extends Controller{

    protected $access = [
        'balance' => ['@', 'get'],
    ];

    public function actionBalance(){
        return $this->success(UserModel::getUserBalance(), false);
    }
}
