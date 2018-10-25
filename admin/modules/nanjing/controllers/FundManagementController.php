<?php
namespace admin\modules\nanjing\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\nanjing\models\FundManagementModel;

class FundManagementController extends Controller{

    protected $access = [
        'send-captcha' => ['@', 'post'],
        'deposit' => ['@', 'post'],
        'draw' => ['@', 'post'],
        'list' => ['@', 'get'],
        'balance' => ['@', 'get'],
        'all-users-fund' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'send-captcha' => [
            'scenario' => FundManagementModel::SCE_SEND_CAPTCHA,
            'convert' => false,
        ],
        'deposit' => FundManagementModel::SCE_DEPOSIT_TO_MAIN_ACCOUNT,
        'draw' => FundManagementModel::SCE_DRAW_FROM_MAIN_ACCOUNT,
        'list' => [
            'scenario' => FundManagementModel::SCE_GET_LIST,
            'convert' => false,
        ],
        'balance' => [
            'scenario' => FundManagementModel::SCE_GET_BALANCE,
            'convert' => false,
        ],
        'all-users-fund' => [
            'scenario' => FundManagementModel::SCE_GET_ALL_USERS_FUND,
            'convert' => false,
        ],
        '_model' => FundManagementModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
