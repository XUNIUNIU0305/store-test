<?php
namespace custom\modules\temp\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\temp\models\ExchangeModel;

class ExchangeController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => ExchangeModel::SCE_GET_LIST,
        '_model' => '\custom\modules\temp\models\ExchangeModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
