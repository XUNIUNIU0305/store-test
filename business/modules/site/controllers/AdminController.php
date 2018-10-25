<?php
namespace business\modules\site\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\site\models\AdminModel;

class AdminController extends Controller{

    protected $access = [
        'list' => ['249', 'get'],
        'set' => ['249', 'post'],
        'remove' => ['249', 'post'],
        'index' => ['249', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => AdminModel::SCE_GET_ADMIN_LIST,
        'set' => AdminModel::SCE_SET_ADMIN,
        'remove' => AdminModel::SCE_REMOVE_ADMIN,
        '_model' => '\business\modules\site\models\AdminModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
