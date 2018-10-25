<?php
namespace business\modules\bank\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\bank\models\DrawDetailModel;

class DrawDetailController extends Controller{

    protected $access = [
        'index' => ['!50', 'get'],
        'detail' => ['!50', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'detail' => [
            'scenario' => DrawDetailModel::SCE_GET_DETAIL,
            'convert' => false,
        ],
        '_model' => DrawDetailModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
