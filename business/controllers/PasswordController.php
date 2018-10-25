<?php
namespace business\controllers;

use Yii;
use business\models\PasswordModel;

class PasswordController extends Controller{

    protected $access = [
        'index' => ['?', 'get'],
        'reset' => ['?', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'reset' => PasswordModel::SCE_RESET_PASSWORD,
        '_model' => '\business\models\PasswordModel',
    ];

    public $layout = 'index';

    public function actionIndex(){
        return $this->render('index');
    }
}
