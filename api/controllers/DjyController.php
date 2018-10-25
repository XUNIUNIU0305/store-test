<?php
namespace api\controllers;

use Yii;
use api\controllers\Controller;
use api\models\DjyModel;
use business\modules\leader\models\AreaModel;

class DjyController extends Controller{

    protected $access = [
        'total-fee' => [null, 'get'],
        'total-sku' => [null, 'get'],
        'top-area-fee-list' => [null, 'get'],
        'top-area-fee' => [null, 'get'],
        'top-area-sku' => [null, 'get'],
        'quaternary-area-fee-list' => [null, 'get'],
        'quaternary-area-fee' => [null, 'get'],
        'quaternary-area-sku' => [null, 'get'],
        'store-fee-list' => [null, 'get'],
        'commander' => [null, 'get'],
        'order-list' => [null, 'get'],
        'area-list' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'total-fee' => DjyModel::SCE_GET_TOTAL_FEE,
        'total-sku' => DjyModel::SCE_GET_TOTAL_SKU,
        'top-area-fee-list' => DjyModel::SCE_GET_TOP_AREA_FEE_LIST,
        'top-area-fee' => DjyModel::SCE_GET_TOP_AREA_FEE,
        'top-area-sku' => DjyModel::SCE_GET_TOP_AREA_SKU,
        'quaternary-area-fee-list' => DjyModel::SCE_GET_QUATERNARY_AREA_FEE_LIST,
        'quaternary-area-fee' => DjyModel::SCE_GET_QUATERNARY_AREA_FEE,
        'quaternary-area-sku' => DjyModel::SCE_GET_QUATERNARY_AREA_SKU,
        'store-fee-list' => DjyModel::SCE_GET_STORE_FEE_LIST,
        'commander' => DjyModel::SCE_GET_COMMANDER,
        'order-list' => DjyModel::SCE_GET_ORDER_LIST,
        'area-list' => DjyModel::SCE_GET_AREA_LIST,
        '_model' => DjyModel::class,
    ];
}
