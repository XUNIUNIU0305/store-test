<?php
namespace business\modules\leader\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\leader\models\CustomQuantityModel;

class CustomQuantityController extends Controller{

    protected $access = [
        'index' => ['50', 'get'],
        'list' => ['50', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => CustomQuantityModel::SCE_GET_CUSTOM_QUANTITY,
        '_model' => 'business\modules\leader\models\CustomQuantityModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
