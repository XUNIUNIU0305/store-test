<?php
namespace mobile\modules\member\controllers;

use Yii;

class GpubsOrderController extends Controller{

    public $access = [
        'index' => ['@', 'get'],
        'detail' => ['@','get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
    public function actionDetail(){
        return $this->render('detail');
    }
}
