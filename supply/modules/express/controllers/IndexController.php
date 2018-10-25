<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 上午11:05
 */

namespace supply\modules\express\controllers;


use supply\modules\express\models\IndexModel;

class IndexController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'page' => ['@', 'get'],
        'search' => ['@', 'get'],
        'add' => ['@', 'post'],
        'delete' => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'page' => IndexModel::SCE_PAGE,
        'search' => IndexModel::SCE_SEARCH,
        'add' => IndexModel::SCE_ADD,
        'delete' => IndexModel::SCE_DELETE,
        '_model' => IndexModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}