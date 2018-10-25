<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 15:27
 */

namespace custom\modules\membrane\controllers;

use custom\modules\membrane\models\ProductModel;

class ProductController extends Controller
{
    protected $access = [
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'index' => ProductModel::SCE_PRODUCTS,
        '_model' => ProductModel::class
    ];
}