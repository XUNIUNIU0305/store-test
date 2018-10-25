<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-29
 * Time: 下午4:24
 */

namespace business\modules\data\controllers;


use business\modules\data\models\CartModel;

class CartController extends Controller
{
    protected $access = [
        'search' => ['200', 'get'],
        'accurate-search' => ['200', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search' => CartModel::SCE_SEARCH,
        'accurate-search' => CartModel::SCE_ACCURATE_SEARCH,
        '_model' => CartModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}