<?php
namespace admin\modules\site\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\site\models\RegistercodeModel;

class RegistercodeController extends Controller{

    protected $access = [
        'custom' => ['@', 'get'],
        'create-custom' => ['@', 'post'],
        'get-custom' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'create-custom' => RegistercodeModel::SCE_CREATE_CUSTOM_CODE,
        'get-custom' => RegistercodeModel::SCE_GET_CUSTOM_CODE,
        '_model' => '\admin\modules\site\models\RegistercodeModel',
    ];

    public function actionCustom(){
        return $this->render('custom');
    }
}
