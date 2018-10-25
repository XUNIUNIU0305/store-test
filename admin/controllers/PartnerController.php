<?php
namespace admin\controllers;

use Yii;
use yii\web\Response;
use mobile\modules\member\models\LoginModel;
use admin\models\PartnerModel;
use yii\web\NotFoundHttpException;
use common\controllers\Controller;

class PartnerController extends Controller{

    public $layout = false;

    protected $access = [
        'index' => [null, 'get'],
        'apply' => [null, 'post'],
        'success' => [null, 'get'],
        'fail' => [null, 'get'],
        'promoter' => [null, 'get'],
        'captcha' => [null, 'post'],
        'code' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'apply' => PartnerModel::SCE_APPLY,
        'promoter' => PartnerModel::SCE_GET_PROMOTER,
        'captcha' => PartnerModel::SCE_SEND_CAPTCHA,
        'code' => PartnerModel::SCE_GET_REGISTERCODE,
        '_model' => '\admin\models\PartnerModel',
    ];

    public function actionIndex(){
        if(LoginModel::saveWechatCode()){
            $model = new PartnerModel([
                'scenario' => PartnerModel::SCE_DISPLAY_PAGE,
                'attributes' => Yii::$app->request->get(),
            ]);
            if($model->process()){
                return $this->render('index');
            }else{
                if($model->errorCode == 5273){
                    return $this->render('unavailable');
                }else{
                    throw new NotFoundHttpException;
                }
            }
        }else{
            $this->redirect(LoginModel::generateLoginUrl());
        }
    }

    public function actionSuccess(){
        $PartnerModel = new PartnerModel([
            'scenario' => PartnerModel::SCE_GET_REGISTERCODE,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($PartnerModel->validate()){
            return $this->render('success');
        }else{
            throw new NotFoundHttpException;
        }
    }

    public function actionFail(){
        return $this->render('fail');
    }
}
