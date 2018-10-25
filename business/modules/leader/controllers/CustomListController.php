<?php
namespace business\modules\leader\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\leader\models\CustomListModel;

class CustomListController extends Controller{

    protected $access = [
        'index' => ['20', 'get'],
        'list' => ['20', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => CustomListModel::SCE_GET_CUSTOM_LIST,
        '_model' => '\business\modules\leader\models\CustomListModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
