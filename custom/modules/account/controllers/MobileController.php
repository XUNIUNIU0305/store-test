<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 9:47
 */

namespace custom\modules\account\controllers;


use common\controllers\Controller;
use custom\modules\account\models\MobileModel;

class MobileController extends Controller
{

    protected $access = [
        'index' => ['@', 'get'],
        'sendsms' => ['@', 'get'],
        'verifysms' => ['@', 'post'],
        'bindmobile' => ['@', 'post'],
        'changemobile' => ['@', 'post'],
        'getmobile' => ['@', 'post'],

    ];

    protected $actionUsingDefaultProcess = [
        'sendsms' => MobileModel::SCE_SEND_CURRENT_USER,
        'verifysms' => MobileModel::SCE_CHECK_VERIFY_CODE,
        'changemobile' => MobileModel::SCE_BIND_NEW_MOBILE,
        'bindmobile' => MobileModel::SCE_BIND_MOBILE,
        'getmobile' => MobileModel::SCE_GET_MOBILE,
        '_model' => '\custom\modules\account\models\MobileModel',
    ];

    //修改手机号码首页
    public function actionIndex()
    {
        return $this->render("index");
    }


}