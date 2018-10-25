<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 15:33
 */

namespace api\controllers;


use api\models\CustomerModel;

class CustomerController extends  Controller
{

    protected $access = [
        'technician' => [null, 'get'],//获取门店技师
     ];

    protected $actionUsingDefaultProcess = [
        'technician' =>CustomerModel::SCE_GET_TECHNICIAN_LIST,
         '_model' => '\api\models\CustomerModel',
    ];


}