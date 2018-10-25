<?php
namespace custom\modules\temp\controllers;

use Yii;
use common\controllers\Controller;

class LotteryController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
