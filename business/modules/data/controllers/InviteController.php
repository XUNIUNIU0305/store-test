<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-30
 * Time: 下午9:19
 */

namespace business\modules\data\controllers;


use business\modules\data\models\InviteModel;

class InviteController extends Controller
{
    protected $access = [
        'index' => ['200', 'get'],
        'search' => ['200', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search' => InviteModel::SCE_SEARCH,
        '_model' => InviteModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}