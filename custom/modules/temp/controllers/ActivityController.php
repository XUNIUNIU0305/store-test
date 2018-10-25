<?php
namespace custom\modules\temp\controllers;

use Yii;
use common\controllers\Controller;

class ActivityController extends Controller{

    public function actionIndex(){
        $this->module->layout = 'empty';
        $this->module->setLayoutPath('@temp/views/layouts');
        return $this->render('index');
    }
}
