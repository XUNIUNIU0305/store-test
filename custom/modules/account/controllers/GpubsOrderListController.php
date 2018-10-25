<?php
namespace custom\modules\account\controllers;

use common\controllers\Controller;

class GpubsOrderListController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
