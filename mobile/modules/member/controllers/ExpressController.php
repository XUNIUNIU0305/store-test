<?php
namespace mobile\modules\member\controllers;

class ExpressController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
