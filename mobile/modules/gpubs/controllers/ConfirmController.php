<?php
namespace mobile\modules\gpubs\controllers;

use Yii;
use common\controllers\Controller;
use mobile\modules\gpubs\models\ConfirmModel;

class ConfirmController extends Controller{

    public $access = [
        'index' => ['@', 'get'],
        'order' => ['@', 'post'],
    ];

    public $actionUsingDefaultProcess = [
        'order' => ConfirmModel::SCE_ORDER,
        '_model' => ConfirmModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
