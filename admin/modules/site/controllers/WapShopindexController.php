<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 28/05/18
 * Time: 18:18
 */

namespace admin\modules\site\controllers;

use admin\controllers\Controller;
use admin\modules\site\models\WapShopindexModel;

class WapShopindexController extends Controller
{
    protected $access = [
        'create' => ['@','post'],
        'list' => ['@','get'],
        'edit' => ['@','post'],
        'delete' => ['@', 'post'],
        'sort' => ['@', 'post'],
        'shop-products' => ['@', 'get'],
        'search-product' => ['@', 'get'],
        'create-product' => ['@','post'],
        'list-product' => ['@','get'],
        'edit-product' => ['@','post'],
        'delete-product' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'create' => WapShopindexModel::SCE_CREATE,
        'list' => WapShopindexModel::SCE_LIST,
        'edit' => WapShopindexModel::SCE_EDIT,
        'delete' => WapShopindexModel::SCE_DELETE,
        'sort' => WapShopindexModel::SCE_SORT,
        'shop-products' => WapShopindexModel::SCE_SUPPLY_SHOP_PRODUCTS,
        'search-product' => WapShopindexModel::SCE_SEARCH_SHOP_PRODUCT,
        'create-product' => WapShopindexModel::SCE_CREATE_PRODUCT,
        'list-product' => WapShopindexModel::SCE_LIST_PRODUCT,
        'edit-product' => WapShopindexModel::SCE_EDIT_PRODUCT,
        'delete-product' => WapShopindexModel::SCE_DELETE_PRODUCT,
        '_model' => '\admin\modules\site\models\WapShopindexModel',
    ];

    public function actionIndex() {
        return $this->render('index');
    }
}
