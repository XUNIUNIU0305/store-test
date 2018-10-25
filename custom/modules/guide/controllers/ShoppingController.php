<?php
namespace custom\modules\guide\controllers;

use Yii;
use common\controllers\Controller;

class ShoppingController extends Controller{

    protected $access = [
        'index' => [null, 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
