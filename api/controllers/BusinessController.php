<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-11
 * Time: 下午12:50
 */

namespace api\controllers;

use api\models\BusinessModel;

class BusinessController extends Controller
{

    protected $access = [
        'validate-account' => [null, 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'validate-account' => [
            'scenario' => BusinessModel::SCE_VALIDATE_ACCOUNT,
            'convert' => false,
        ],
        '_model' => BusinessModel::class,
    ];
}