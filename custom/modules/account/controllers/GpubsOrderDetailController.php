<?php
namespace custom\modules\account\controllers;

use common\controllers\Controller;

class GpubsOrderDetailController extends Controller{

    public $layout = 'empty';

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
