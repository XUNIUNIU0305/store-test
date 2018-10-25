<?php
namespace admin\modules\site\controllers;

use admin\controllers\Controller;
use admin\modules\site\models\BusinessModel;

class BusinessController extends Controller{

    protected $access = [
        'list'=>['@', 'get'],
        'leader'=>['@', 'get'],
        'commissar'=>['@', 'get'],
        'add' => ['@', 'post'],
        'edit' => ['@', 'post'],
        'remove' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => BusinessModel::SCE_LIST_BUSINESS,
        'leader' => BusinessModel::SCE_LEADER_BUSINESS,
        'commissar' => BusinessModel::SCE_COMMISSAR_BUSINESS,
        'add' => BusinessModel::SCE_ADD_BUSINESS,
        'edit' => BusinessModel::SCE_MODIFY_BUSINESS,
        'remove' => BusinessModel::SCE_REMOVE_BUSINESS,
        '_model' => '\admin\modules\site\models\BusinessModel',
    ];


    //业务区域管理首页
    public function actionIndex(){
        return $this->render('index');
    }
}
