<?php
namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use common\models\parts\custom\CustomUser;
use custom\modules\account\models\GpubsPickingUpModel;

class GpubsPickingUpController extends Controller{

    public function beforeAction($action){
        if(parent::beforeAction($action)){
            if(Yii::$app->user->isGuest)return false;
            if((new \custom\models\parts\temp\UserProductOrderLimit\UserProductLimit)->validateProductLimit(Yii::$app->user->identity->account))return true;
            if(Yii::$app->CustomUser->CurrentUser->level != CustomUser::LEVEL_COMPANY)return false;
            return true;
        }else{
            return false;
        }
    }

    protected $access = [
        'index' => ['@', 'get'],
        'detail-list' => ['@', 'get'],
        'detail-info' => ['@', 'get'],
        'pick-up' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'detail-list' => GpubsPickingUpModel::SCE_GET_DETAIL_LIST,
        'detail-info' => GpubsPickingUpModel::SCE_GET_DETAIL_INFO,
        'pick-up' => GpubsPickingUpModel::SCE_PICK_UP,
        '_model' => GpubsPickingUpModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
