<?php
namespace api\controllers;

use Yii;
use api\models\CustomModel;

class CustomController extends Controller{

    protected $access = [
        'validate-account' => [null, 'post'],
        'achieve-account-by-wechat-user' => [null, 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'validate-account' => [
            'scenario' => CustomModel::SCE_VALIDATE_ACCOUNT,
            'convert' => false,
        ],
        'achieve-account-by-wechat-user' => [
            'scenario' => CustomModel::SCE_ACHIEVE_ACCOUNT_BY_WECHAT_USER,
            'convert' => false,
        ],
        '_model' => CustomModel::class,
    ];
}
