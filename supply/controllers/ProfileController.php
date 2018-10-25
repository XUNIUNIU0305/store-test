<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/29
 * Time: 15:21
 */

namespace supply\controllers;

//获取商户资料
use common\controllers\Controller;
use supply\models\ProfileModel;

class ProfileController extends Controller
{

    protected $access = [
        'get-profile' => ['@', 'get'],
        'save-profile' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-profile' =>[
            'scenario'=> ProfileModel::SCE_GET_PROFILE,
            'convert'=>false,
        ],

        'save-profile'=>ProfileModel::SCE_SAVE_PROFILE,
        '_model' => 'supply\models\ProfileModel',
    ];



}