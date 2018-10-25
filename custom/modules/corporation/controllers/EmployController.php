<?php
namespace custom\modules\corporation\controllers;

use Yii;
use common\controllers\Controller;

class EmployController extends Controller{

    protected $access = [
        'index' => [null, 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
