<?php
/**
 * User: JiangYi
 * Date: 2017/5/27
 * Time: 12:48
 * Desc:
 */

namespace mobile\controllers;


use mobile\models\ShopModel;

class ShopController extends Controller
{
    public $access=[
        'get-supply-info'=>[null,'get'],//获取商户信息
        'goods'=>[null,'get'],//获取商户商品信息
        'adv'=>[null,'get'],//获取商户广告信息
        'get-goods-by-condition' => [null, 'get'],
        'get-list' => [null, 'get'], // 得到主图
        'get-list-product' => [null, 'get'], // 得到甄选商品
    ];

    public $actionUsingDefaultProcess=[
        'get-supply-info'=>ShopModel::SCE_GET_SUPPLY_INFO,
        'adv'=>ShopModel::SCE_ADV,
        'get-list' => ShopModel::SCE_GET_LIST,
        'get-list-product' => ShopModel::SCE_GET_LIST_PRODUCT,
        'get-goods-by-condition' => [
            'scenario' => \custom\models\ShopModel::SCE_SHOP_LIST_BY_CONDITION,
            'model'=>'custom\models\ShopModel'
        ],
        'goods'=>[
            'scenario'=>\custom\models\ShopModel::SCE_SHOP_LIST,
            'model'=>'custom\models\ShopModel'
        ],
        '_model'=>'mobile\models\ShopModel',
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}