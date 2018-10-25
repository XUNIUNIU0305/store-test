<?php
namespace mobile\modules\gpubs\controllers;

use Yii;
use common\controllers\Controller;
use mobile\modules\gpubs\models\DetailModel;
use common\models\parts\custom\CustomUser;

class DetailController extends Controller{

    public $access = [
        'index' => ['@', 'get'],
        'product-sku' => ['@', 'get'],
    ];

    public $actionUsingDefaultProcess = [
        'product-sku' => DetailModel::SCE_GET_PRODUCT_SKU,
        '_model' => DetailModel::class,
    ];

    public function actionIndex(){
        if(Yii::$app->request->get('id') && !Yii::$app->user->isGuest){
            if(!Yii::$app->request->get('group_id')){
                if(Yii::$app->CustomUser->CurrentUser->level != CustomUser::LEVEL_COMPANY)throw new \yii\web\ForbiddenHttpException;
            }
        }else{
            throw new \yii\web\ForbiddenHttpException;
        }
        return $this->render('index');
    }
}
