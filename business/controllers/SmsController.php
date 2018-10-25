<?php
namespace business\controllers;

use Yii;
use business\models\SmsModel;

class SmsController extends Controller{

    protected $access = [
        'register' => ['?', 'post'],
        'reset' => ['?', 'post'],
        'draw' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'register' => SmsModel::SCE_REGISTER,
        'reset' => SmsModel::SCE_RESET_PASSWORD,
        'draw' => SmsModel::SCE_DRAW,
        '_model' => '\business\models\SmsModel',
    ];
}
