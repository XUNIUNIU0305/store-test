<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-6-22
 * Time: 下午5:58
 */

namespace admin\modules\info\controllers;

use admin\controllers\Controller;
use admin\modules\info\models\GpubsDetailModel;

class GpubsDetailController extends Controller
{
    protected $access = [
        'list' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => GpubsDetailModel::SCE_DETAIL_LIST,
        '_model' => '\admin\modules\info\models\GpubsDetailModel',
    ];

    //订单列表
    public function actionIndex()
    {
        return $this->render('index');
    }
}