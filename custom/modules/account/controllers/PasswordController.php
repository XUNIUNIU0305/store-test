<?php
namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\account\models\PasswordModel;

class PasswordController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'modify' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'modify' => PasswordModel::SCE_MODIFY_PASSWORD,
        '_model' => '\custom\modules\account\models\PasswordModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
