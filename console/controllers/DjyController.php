<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\models\temp\djy\Djy;
use common\models\temp\djy\Commanders;

class DjyController extends Controller{

    public function actionReset(){
        (new Djy)->reset();
        (new Commanders)->reset();
    }
}
