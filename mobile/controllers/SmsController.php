<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 14:33
 * Desc:发送短信接口
 */

namespace mobile\controllers;


use common\controllers\Controller;
use custom\models\SmsModel;

class SmsController extends  Controller
{
    protected $access = [
        'send' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'send' => SmsModel::SCE_SEND,
        '_model' => '\custom\models\SmsModel',
    ];

}