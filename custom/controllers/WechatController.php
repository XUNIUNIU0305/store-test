<?php
namespace custom\controllers;

use Yii;
use common\controllers\Controller;
use custom\models\WechatModel;

class WechatController extends Controller{

    protected $access = [
        'user' => [null, 'get'],
        'login-url' => ['?', 'get'],
        'bind-url' => ['@', 'post'],
        'user-info' => ['@', 'get'],
        'unbind-account' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'bind-url' => WechatModel::SCE_BIND_URL,
        'unbind-account' => WechatModel::SCE_UNBIND_WECHAT_ACCOUNT,
        '_model' => WechatModel::class,
    ];

    public function actionUser(){
        $wechatModel = new WechatModel([
            'scenario' => WechatModel::SCE_USER_HANDLE,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($redirectUrl = $wechatModel->process()){
            $this->redirect($redirectUrl);
        }else{
            return $this->failure($wechatModel->errorCode);
        }
    }

    public function actionLoginUrl(){
        return $this->success(WechatModel::getLoginUrl());
    }

    public function actionUserInfo(){
        return $this->success(WechatModel::getWechatUserInfo());
    }
}
