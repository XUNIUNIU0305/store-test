<?php
namespace business\modules\account\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\account\models\IndexModel;

class IndexController extends Controller{

    protected $access = [
        'identity' => ['@', 'get'],
        'achievement' => ['@', 'get'],
        'position' => ['@', 'get'],
        'chart' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'identity' => IndexModel::SCE_GET_IDENTITY,
        'achievement' => IndexModel::SCE_GET_ACHIEVEMENT,
        'position' => IndexModel::SCE_GET_POSITION,
        'chart' => IndexModel::SCE_GET_CHART,
        '_model' => 'business\modules\account\models\IndexModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
