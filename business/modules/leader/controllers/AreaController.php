<?php
namespace business\modules\leader\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\leader\models\AreaModel;

class AreaController extends Controller{

    protected $access = [
        'index' => ['239', 'get'],
        'list' => ['20', 'get'],
        'level' => ['20', 'get'],
        'add' => ['249', 'post'],
        'role' => ['239', 'get'],
        'appoint' => ['249', 'post'],
        'modify' => ['249', 'post'],
        'chart' => ['20', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => AreaModel::SCE_GET_AREA_LIST,
        'add' => AreaModel::SCE_ADD_AREA,
        'role' => AreaModel::SCE_GET_ROLE,
        'appoint' => AreaModel::SCE_APPOINT_USER,
        'modify' => AreaModel::SCE_MODIFY_AREA,
        'chart' => AreaModel::SCE_GET_AREA_CHART,
        '_model' => '\business\modules\leader\models\AreaModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionLevel(){
        return $this->success(AreaModel::getAreaLevelList());
    }
}
