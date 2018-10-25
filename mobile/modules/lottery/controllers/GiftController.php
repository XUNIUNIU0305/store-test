<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午1:57
 */

namespace mobile\modules\lottery\controllers;


use mobile\modules\lottery\models\GiftModel;

class GiftController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'view' => ['@', 'get'],
        'notify' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'view' => GiftModel::SCE_VIEW,
        'notify' => GiftModel::SCE_NOTIFY,
        '_model' => GiftModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}