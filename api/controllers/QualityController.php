<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 16:19
 */

namespace api\controllers;


use api\models\QualityModel;

class QualityController extends  Controller
{


    protected $access = [
        'package' => [null, 'get'],//获取门店技师
        'place'=>[null,'get'],//施工位置
        'get-attribute'=>[null,'get'],//获取套餐内施工部位属性
        'get-price'=>[null,'get'],//获取报价信息,
        'get-price-list'=>[null,'get'],
        'business-package' => [null, 'get'],//获取business门店技师
        'business-place'=>[null,'get'],//business施工位置

    ];

    protected $actionUsingDefaultProcess = [
        'package' =>QualityModel::SCE_GET_PACKAGE_LIST,
        'place'=>QualityModel::SCE_GET_PLACE,
        'business-package' =>QualityModel::SCE_GET_BUSINESS_PACKAGE_LIST,
        'business-place'=>QualityModel::SCE_GET_BUSINESS_PLACE,
        'get-attribute'=>QualityModel::SCE_GET_ATTRIBUTE,
        'get-price'=>QualityModel::SCE_GET_PRICE,
        'get-price-list'=>QualityModel::SCE_GET_PRICE_LIST,
        '_model' => '\api\models\QualityModel',
    ];

}