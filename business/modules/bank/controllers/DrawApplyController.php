<?php
namespace business\modules\bank\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\bank\models\DrawApplyModel;

class DrawApplyController extends Controller{

    protected $access = [
        'index' => ['!50', 'get'],
        'create' => ['!50', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'create' => DrawApplyModel::SCE_CREATE_APPLY,
        '_model' => DrawApplyModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
