<?php
namespace business\modules\bank\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\bank\models\CardModel;

class CardController extends Controller{

    protected $access = [
        'index' => ['!50', 'get'],
        'binded-card' => ['!50', 'get'],
        'trans-amount' => ['!50', 'post'],
        'activate' => ['!50', 'post'],
        'unbind' => ['!50', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'activate' => CardModel::SCE_ACTIVATE_ACCOUNT,
        'trans-amount' => [
            'scenario' => CardModel::SCE_TRANS_AMOUNT,
            'convert' => false,
        ],
        'unbind' => CardModel::SCE_UNBIND_ACCOUNT,
        '_model' => CardModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionBindedCard(){
        return $this->success(CardModel::getBindedCard(), false);
    }
}
