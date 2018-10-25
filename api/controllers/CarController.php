<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 14:04
 * DESC:获取车型相关接品
 */

namespace api\controllers;


use api\models\CarModel;

class CarController extends  Controller
{


    protected $access = [
        'brand' => [null, 'get'],//获取品牌
        'type' => [null, 'get'],//获取车弄
    ];

    protected $actionUsingDefaultProcess = [
        'brand' =>CarModel::SCE_GET_CAR_BRAND,
        'type'=>CarModel::SCE_GET_CAR_TYPE,
        '_model' => '\api\models\CarModel',
    ];

}