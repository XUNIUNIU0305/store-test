<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-28
 * Time: 上午9:29
 */

namespace business\modules\data\controllers;

use business\modules\data\models\ConversionModel;

class ConversionController extends Controller
{
    protected $access = [
        'index' => ['200', 'get'],
        'list' => ['200', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'list' => ConversionModel::SCE_GET_LIST,
        '_model' => ConversionModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}