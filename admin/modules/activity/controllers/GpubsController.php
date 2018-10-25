<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-6-21
 * Time: 下午9:07
 */

namespace admin\modules\activity\controllers;

use admin\controllers\Controller;
use admin\modules\activity\models\GpubsModel;

class GpubsController extends Controller
{
    protected $access = [
        'create-gpubs' => ['@', 'get'],
        'detail-gpubs' => ['@', 'get'],
        'modify-gpubs' => ['@', 'get'],
        'create' => ['@', 'post'],
        'create-deliver' => ['@', 'post'],
        'detail' => ['@', 'get'],
        'update' => ['@', 'post'],
        'list' => ['@', 'get'],
        'search' => ['@', 'get'],
        'search-gpubs' => ['@', 'get'],
        'set-status' => ['@', 'post'],
        'set-recomment' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'create' => GpubsModel::SCE_CREATE_GPUBS,
        'create-deliver' => GpubsModel::SCE_CREATE_GPUBS_DELIVER,
        'detail' => GpubsModel::SCE_GET_GPUBS_DETAIL,
        'update' => GpubsModel::SCE_UPDATE_GPUBS,
        'list' => GpubsModel::SCE_GET_GPUBS_LIST,
        'search' => GpubsModel::SCE_SEARCH,
        'search-gpubs' => GpubsModel::SCE_SEARCH_GPUBS,
        'set-status' => GpubsModel::SCE_SET_STATUS,
        'set-recomment' => GpubsModel::SCE_SET_RECOMMENT,
        '_model' => GpubsModel::class,
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateGpubs()
    {
        return $this->render('create-gpubs');
    }

    public function actionDetailGpubs()
    {
        return $this->render('detail-gpubs');
    }

    public function actionModifyGpubs()
    {
        return $this->render('modify-gpubs');
    }
}
