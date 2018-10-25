<?php
namespace business\controllers;

use Yii;

class OverviewController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public $layout = 'overview';

    public function actionIndex(){
        return $this->render('index');
    }
}
