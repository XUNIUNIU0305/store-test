<?php
namespace admin\modules\count\controllers;

//use admin\controllers\Controller;


use admin\controllers\Controller;

class AccountConsumptionController extends Controller {

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
