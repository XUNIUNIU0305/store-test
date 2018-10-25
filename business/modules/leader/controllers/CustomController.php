<?php
namespace business\modules\leader\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\leader\models\CustomModel;
use yii\web\ForbiddenHttpException;

class CustomController extends Controller{

    protected $access = [
        'index' => ['20', 'get'],
        'info' => ['20', 'get'],
        'area' => ['249', 'post'],
        'achievement' => ['20', 'get'],
        'chart' => ['20', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'info' => CustomModel::SCE_GET_CUSTOM_INFO,
        'area' => CustomModel::SCE_SET_CUSTOM_AREA,
        'achievement' => CustomModel::SCE_GET_CUSTOM_ACHIEVEMENT,
        'chart' => CustomModel::SCE_GET_CUSTOM_CHART,
        '_model' => 'business\modules\leader\models\CustomModel',
    ];

    public function actionIndex(){
        $customModel = new CustomModel([
            'scenario' => CustomModel::SCE_VALIDATE_ACCOUNT,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($customModel->process()){
            return $this->render('index');
        }else{
            throw new ForbiddenHttpException;
        }
    }
}
