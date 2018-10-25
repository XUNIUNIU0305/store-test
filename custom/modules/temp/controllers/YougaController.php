<?php
namespace custom\modules\temp\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\temp\models\YougaModel;

class YougaController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'zodiac' => ['@', 'get'],
        'number' => ['@', 'get'],
        'order' => ['@', 'post'],
        'selected' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'number' => YougaModel::SCE_GET_NUMBER,
        'order' => YougaModel::SCE_ORDER_NUMBER,
        'selected' => YougaModel::SCE_GET_SELECTED_NUMBER,
        '_model' => '\custom\modules\temp\models\YougaModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionZodiac(){
        return $this->success(YougaModel::getZodiacList());
    }
}
