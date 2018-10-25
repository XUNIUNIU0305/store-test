<?php
namespace admin\modules\fund\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\fund\models\DepositAndDrawListModel;

class DepositAndDrawListController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => [
            'scenario' => DepositAndDrawListModel::SCE_GET_LIST,
            'convert' => false,
        ],
        '_model' => DepositAndDrawListModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
