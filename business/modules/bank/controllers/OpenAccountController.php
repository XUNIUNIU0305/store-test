<?php
namespace business\modules\bank\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\bank\models\OpenAccountModel;

class OpenAccountController extends Controller{

    protected $access = [
        'index' => ['!50', 'get'],
        'add-card' => ['!50', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'add-card' => OpenAccountModel::SCE_ADD_CARD,
        '_model' => OpenAccountModel::class,
    ];

    public function actionIndex(){
        if(OpenAccountModel::isNanjingAccountExist()){
            $this->redirect('/bank/card');
        }else{
            return $this->render('index');
        }
    }
}
