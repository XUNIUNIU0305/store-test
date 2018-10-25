<?php
namespace api\controllers;

use Yii;
use api\models\NotifyModel;

class NotifyController extends Controller{

    protected $access = [
        'alipay' => [null, 'post'],
    ];

    public function actionAlipay(){
        Yii::$app->db->queryMaster = true;
        $notifyModel = new NotifyModel([
            'scenario' => NotifyModel::SCE_ALIPAY_HANDLE,
        ]);
        if($notifyModel->process()){
            echo 'success';
        }else{
            echo 'fail';
        }
    }

    public function actionWxpay(){
        Yii::$app->db->queryMaster = true;
        $notifyModel = new NotifyModel([
            'scenario' => NotifyModel::SCE_WXPAY_HANDLE,
        ]);
        if($notifyModel->process()){
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        }else{
            echo '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        }
    }

    public function actionAbchina(){
        Yii::$app->db->queryMaster = true;
        $notifyModel = new NotifyModel([
            'scenario' => NotifyModel::SCE_ABCHINA_HANDLE,
        ]);
        if($notifyModel->process()){
            echo 'success';
        }else{
            throw new \yii\web\ForbiddenHttpException;
        }
    }
}
