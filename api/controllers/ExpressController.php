<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/10
 * Time: 17:38
 */

namespace api\controllers;


use api\models\ExpressModel;

class ExpressController extends Controller
{
    protected $access = [
        'get-company' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-company' =>ExpressModel::SCE_GET_EXPRESS_COMPANY_LIST ,
        '_model' => '\api\models\ExpressModel',
    ];
}