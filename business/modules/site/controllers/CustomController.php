<?php
namespace business\modules\site\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\site\models\CustomModel;

class CustomController extends Controller{

    protected $access = [
        'registercode' => ['239', 'post'],
        'registercode-list' => ['239', 'get'],
        'index' => ['239', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'registercode' => CustomModel::SCE_ADD_REGISTERCODE,
        'registercode-list' => CustomModel::SCE_GET_REGISTERCODE_LIST,
        '_model' => '\business\modules\site\models\CustomModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
