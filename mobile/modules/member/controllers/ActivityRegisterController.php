<?php
namespace mobile\modules\member\controllers;

use Yii;
use mobile\modules\member\models\ActivityRegisterModel;
use mobile\models\AuthorizeModel;

class ActivityRegisterController extends Controller{

    protected $access = [
        'index' => [null, 'get'],
        'member-register' => ['?', 'post'],
        'send-captcha' => ['?', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'member-register' => ActivityRegisterModel::SCE_SIGN_UP,
        'send-captcha' => ActivityRegisterModel::SCE_SEND_CAPTCHA,
        '_model' => ActivityRegisterModel::class,
    ];

    public function actionIndex(){
        $activityRegisterModel = new ActivityRegisterModel([
            'scenario' => ActivityRegisterModel::SCE_RENDER,
            'attributes' => Yii::$app->request->get(),
        ]);
        if(!$activityRegisterModel->process())throw new \yii\web\NotFoundHttpException;
        if(Yii::$app->user->isGuest){
            $authorizeModel = new AuthorizeModel([
                'scenario' => AuthorizeModel::SCE_AUTHORIZE,
                'attributes' => Yii::$app->request->get(),
            ]);
            if($result = $authorizeModel->process()){
                if($result === true){
                    return $this->redirect('/');
                }elseif(is_string($result)){
                    return $this->redirect($result);
                }else{
                    throw new \yii\web\NotFoundHttpException;
                }
            }else{
                return $this->render('index');
            }
        }else{
            return $this->redirect('/');
        }
    }
}
