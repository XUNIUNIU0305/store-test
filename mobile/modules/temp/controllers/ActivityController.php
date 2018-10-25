<?php
namespace mobile\modules\temp\controllers;

use Yii;

class ActivityController extends Controller{

    public $layout = 'empty';

    public function actionIndex(){
        return $this->render('index');
    }
}
