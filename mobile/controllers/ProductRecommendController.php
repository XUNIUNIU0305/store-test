<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/10/9
 * Time: 上午9:38
 */

namespace mobile\controllers;


use mobile\models\ProductRecommendModel;

class ProductRecommendController extends Controller
{
    protected $access = [
        'goods'=>[null,'get'],
        'rand'=>[null,'get'],
        'group-purchase-goods'=>[null,'get']
    ];
    protected $actionUsingDefaultProcess = [
        'goods'=>ProductRecommendModel::SCE_GOODS,
        'group-purchase-goods'=>ProductRecommendModel::SCE_GROUP_PURCHASE_GOODS,
        'rand'=>ProductRecommendModel::SCE_RAND,
        '_model'=>'mobile\models\ProductRecommendModel'
    ];
}