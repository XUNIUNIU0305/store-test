<?php
namespace business\modules\temp\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\temp\models\ExchangeModel;

class ExchangeController extends Controller{

    protected $access = [
        'index' => ['!50', 'get'],
        'query' => ['!50', 'post'],
        'exchange' => ['!50', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'query' => ExchangeModel::SCE_EXCHANGE_QUERY,
        'exchange' => ExchangeModel::SCE_EXCHANGE,
        '_model' => '\business\modules\temp\models\ExchangeModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
