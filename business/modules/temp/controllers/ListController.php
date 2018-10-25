<?php
namespace business\modules\temp\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\temp\models\ListModel;

class ListController extends Controller{

    protected $access = [
        'index' => ['!50', 'get'],
        'list' => ['!50', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => ListModel::SCE_GET_LIST,
        '_model' => '\business\modules\temp\models\ListModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
