<?php
namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;

class RechargeController extends Controller{

    protected $access =  [
        'index' => ['@', 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }

}
