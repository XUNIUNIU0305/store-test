<?php
namespace api\controllers;

use Yii;
use api\models\BankModel;

class BankController extends Controller{

    protected $access = [
        'list' => [null, 'get'],
        'code' => [null, 'get'],
        'id-type' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'code' => BankModel::SCE_GET_BANK_CODE,
        '_model' =>BankModel::class,
    ];

    public function actionList(){
        return $this->success(BankModel::getList());
    }

    public function actionIdType(){
        return $this->success(BankModel::getIdType(), false);
    }
}
