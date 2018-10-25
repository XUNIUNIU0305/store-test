<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use admin\modules\fund\models\parts\DepositAndDrawExecution;

class NonTransactionDepositAndDrawController extends Controller{

    public function actionExecute(){
        $execution = new DepositAndDrawExecution;
        $execution->execute();
        return 0;
    }
}
