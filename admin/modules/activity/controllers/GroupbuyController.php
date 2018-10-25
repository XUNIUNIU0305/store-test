<?php
namespace admin\modules\activity\controllers;


use admin\controllers\Controller;
use admin\modules\activity\models\groupbuy\GroupbuyModel;


class GroupbuyController extends Controller
{
    protected $access=[
        'create-groupbuy'       => ['@','post'],
        'get-groupbuy-product'  => ['@','get'],
        'get-groupbuy'          => ['@','get'],
        'get-sku'               => ['@', 'get'],
        'export'                => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess=[
        'get-groupbuy-product'  => GroupbuyModel::SCE_GET_GROUPBUY_PRODUCT,
        'get-groupbuy'          => GroupbuyModel::SCE_GET_GROUPBUY,
        'create-groupbuy'       => GroupbuyModel::SCE_CREATE_GROUPBUY,
        'get-sku'               => GroupbuyModel::SCE_GET_SKU,
        'export'                => GroupbuyModel::SCE_EXPORT,
        '_model'                => GroupbuyModel::class,
    ];
    
    public function actionIndex(){
        return $this->render('index');
    }
}