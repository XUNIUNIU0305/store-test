<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-30
 * Time: 下午7:52
 */

namespace business\modules\data\controllers;


use business\modules\data\models\BuyModel;

class BuyController extends Controller
{
    protected $access = [
        'index' => ['200', 'get'],
        'search' => ['200', 'get'],
        'area-search' => ['200', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search' => BuyModel::SCE_SEARCH,
        'area-search' => BuyModel::SCE_AREA_SEARCH,
        '_model' => BuyModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}