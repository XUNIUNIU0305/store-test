<?php
namespace api\controllers;

use Yii;
use api\models\WechatModel;

class WechatController extends Controller{

    protected $access = [
        'verify' => [null, 'get'],
    ];

    public function actionVerify(){
        $wechatModel = new WechatModel([
            'scenario' => WechatModel::SCE_SCAN_HANDLE,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($redirectUrl = $wechatModel->process()){
            $this->redirect($redirectUrl);
        }else{
            return $this->failure($wechatModel->errorCode);
        }
    }
}
