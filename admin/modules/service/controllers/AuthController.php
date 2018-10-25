<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/27
 * Time: 上午10:55
 */

namespace admin\modules\service\controllers;


use admin\controllers\Controller;
use admin\modules\service\models\AuthModel;

class AuthController extends Controller
{
    protected $access = [
        'index'=>['@','get'],
        'get-detail'=>['@','get'],
        'get-list'=>['@','get'],
        'pass'=>['@','post'],
        'reject'=>['@','post'],
        'cancel'=>['@','post'],
        'refund'=>['@','post'],
        'void'=>['@','get'],
    ];
    protected $actionUsingDefaultProcess = [
        'get-list'=>[
            'scenario'=>AuthModel::SCE_LIST,
            'convert'=>false,
        ],
        'get-detail'=>AuthModel::SCE_DETAIL,
        'pass'=>AuthModel::SCE_PASS,
        'reject'=>AuthModel::SCE_REJECT,
        'cancel'=>AuthModel::SCE_CANCEL,
        'refund'=>AuthModel::SCE_REFUND_NUMBER,
        '_model'=>'admin\modules\service\models\AuthModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionDetail(){
        return $this->render('detail');
    }

    public function actionVoid(){
        return $this->render('void');
    }
}