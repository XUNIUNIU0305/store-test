<?php
namespace business\modules\leader\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\leader\models\PersonModel;

class PersonController extends Controller{

    protected $access = [
        'add-user' => ['239', 'post'],
        'list' => ['239', 'get'],
        'remove' => ['239', 'post'],
        'reset' => ['239', 'post'],
        'remark' => ['239', 'get'],
        'position' => ['239', 'get'],
        'modify' => ['239', 'post'],
        'index' => ['239', 'get'],
        'achievement' => ['239', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'add-user' => PersonModel::SCE_ADD_USER,
        'list' => PersonModel::SCE_LIST_USER,
        'remove' => PersonModel::SCE_REMOVE_USER,
        'reset' => PersonModel::SCE_RESET_USER,
        'remark' => PersonModel::SCE_GET_USER_REMARK,
        'position' => PersonModel::SCE_GET_USER_POSITION,
        'modify' => PersonModel::SCE_MODIFY_USER,
        'achievement' => PersonModel::SCE_GET_USER_ACHIEVEMENT,
        '_model' => '\business\modules\leader\models\PersonModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
