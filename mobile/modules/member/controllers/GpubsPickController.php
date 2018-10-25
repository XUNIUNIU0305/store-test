<?php
namespace mobile\modules\member\controllers;

use Yii;

class GpubsPickController extends Controller{

    public $access = [
        'index' => ['@', 'get'],
        'detail' => ['@','get']
    ];

    public function actionIndex(){
        return $this->render('index');
    }
    public function actionDetail(){
        return $this->render('detail');
    }
}
