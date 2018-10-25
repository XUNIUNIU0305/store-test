<?php
namespace admin\modules\activity\controllers;

use admin\controllers\Controller;
use admin\modules\activity\models\GpubsManagementModel;

class GpubsManagementController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'types' => ['@', 'get'],
        'statuses' => ['@', 'get'],
        'statistics' => ['@', 'get'],
        'list' => ['@', 'get'],
        'force-establish' => ['@', 'post'],
        'secondary-areas' => ['@', 'get'],
        'tertiary-areas' => ['@', 'get'],
        'quaternary-areas' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'statistics' => GpubsManagementModel::SCE_GET_STATISTICS,
        'list' => GpubsManagementModel::SCE_GET_LIST,
        'force-establish' => GpubsManagementModel::SCE_FORCE_ESTABLISH,
        'secondary-areas' => GpubsManagementModel::SCE_GET_SECONDARY_AREAS,
        'tertiary-areas' => GpubsManagementModel::SCE_GET_TERTIARY_AREAS,
        'quaternary-areas' => GpubsManagementModel::SCE_GET_QUATERNARY_AREAS,
        '_model' => GpubsManagementModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionTypes(){
        return $this->success(GpubsManagementModel::getType());
    }

    public function actionStatuses(){
        return $this->success(GpubsManagementModel::getGpubsGroupStatuses());
    }

    public function actionTopAreas(){
        return $this->success(GpubsManagementModel::getTopAreas());
    }
}
