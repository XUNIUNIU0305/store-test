<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-30
 * Time: 上午10:23
 */

namespace business\modules\data\controllers;


use business\modules\data\models\CustomizationModel;

class CustomizationController extends Controller
{
    protected $access = [
        'index' => ['200', 'get'],
        'search' => ['200', 'get'],
        'product-search' => ['200', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search' => CustomizationModel::SCE_SEARCH,
        'product-search' => CustomizationModel::SCE_PRODUCT_SEARCH,
        '_model' => CustomizationModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}