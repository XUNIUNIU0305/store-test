<?php
namespace admin\modules\fund\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\fund\models\DepositAndDrawApplicationModel;

class DepositAndDrawApplicationController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'user-info' => ['@', 'get'],
        'create' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'user-info' => [
            'scenario' => DepositAndDrawApplicationModel::SCE_GET_USER_INFO,
            'convert' => false,
        ],
        'create' => DepositAndDrawApplicationModel::SCE_CREATE,
        '_model' => DepositAndDrawApplicationModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
