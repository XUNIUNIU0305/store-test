<?php
namespace business\modules\bank\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\bank\models\DrawListModel;

class DrawListController extends Controller{

    protected $access = [
        'index' => ['!50', 'get'],
        'list' => ['!50', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => [
            'scenario' => DrawListModel::SCE_GET_LIST,
            'convert' => false,
        ],
        '_model' => DrawListModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
