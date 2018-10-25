<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/22
 * Time: 下午3:15
 */

namespace custom\controllers;
use common\controllers\Controller;
use custom\models\ProductRecommendModel;

class ProductRecommendController extends Controller
{
    protected $access = [
        'goods'=>[null,'get'],
        'group-purchase-goods'=>['@','get']
    ];
    protected $actionUsingDefaultProcess = [
        'goods'=>ProductRecommendModel::SCE_GOODS,
        'group-purchase-goods'=>ProductRecommendModel::SCE_GROUP_PURCHASE_GOODS,
        '_model'=>'custom\models\ProductRecommendModel'
    ];

}