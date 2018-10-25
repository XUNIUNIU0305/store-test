<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-29
 * Time: 上午10:28
 */

namespace business\modules\data\controllers;


use business\modules\data\models\SalesModel;

class SalesController extends Controller
{
    protected $access = [
        'index' => ['200', 'get'],
        'search' => ['200', 'get'],
        'area-search' => ['200', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search' => SalesModel::SCE_SEARCH,
        'area-search' => SalesModel::SCE_AREA_SEARCH,
        '_model' => SalesModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}