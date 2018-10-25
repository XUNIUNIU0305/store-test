<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 10:32
 */

namespace custom\modules\account\controllers;


use common\controllers\Controller;
use custom\modules\account\models\ProfileModel;

class ProfileController extends Controller
{


    protected $access = [
        'index' => ['@', 'get'],
        'upload' => ['@', 'get'],
        'get-profile' => ['@', 'get'],
        'save' => ['@', 'post'],

    ];

    protected $actionUsingDefaultProcess = [
        'upload' => ProfileModel::SCE_UPLOAD_HEADERIMG,
        'save' => ProfileModel::SCE_SAVE_PROFILE,
        'get-profile' => [
            'scenario' => ProfileModel::SCE_GET_PROFILE,
            'convert' => false,
    ],
        '_model' => '\custom\modules\account\models\ProfileModel',
    ];

    //修改手机号码首页
    public function actionIndex()
    {
        return $this->render("index");
    }


}
