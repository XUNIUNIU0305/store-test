<?php
namespace mobile\modules\member\controllers;

use Yii;
use mobile\modules\member\models\FranchiserModel;
use common\controllers\Controller;
use common\models\parts\custom\CustomUser;
class FranchiserController extends Controller{

    public function beforeAction($action){
        if(parent::beforeAction($action)){
            if(Yii::$app->user->isGuest)return false;
            if(Yii::$app->CustomUser->CurrentUser->level != CustomUser::LEVEL_COMPANY)return false;
            return true;
        }else{
            return false;
        }
    }

    public $access = [
        'index' => ['@', 'get'],
        'detail-list' => ['@', 'get'],
        'detail-info' => ['@', 'get'],
        'pick-up' => ['@', 'post'],
    ];

    public $actionUsingDefaultProcess = [
        'detail-list' => FranchiserModel::SCE_GET_DETAIL_LIST,
        'detail-info' => FranchiserModel::SCE_GET_DETAIL_INFO,
        'pick-up' => FranchiserModel::SCE_PICK_UP,
        '_model' => FranchiserModel::class,

    ];

    public function actionIndex(){
        return $this->render('index');
    }

}
