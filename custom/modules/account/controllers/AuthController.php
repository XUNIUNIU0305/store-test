<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/26
 * Time: 上午11:58
 */

namespace custom\modules\account\controllers;


use common\controllers\Controller;
use common\models\parts\custom\CustomUser;
use custom\modules\account\models\AuthModel;
use Yii;

class AuthController extends Controller
{
    protected $access = [
        'index'=>['@','get'],
        'submit'=>['@','post'],
        'info'=>['@','get'],
    ];
    protected $actionUsingDefaultProcess = [
        'submit'=>AuthModel::SCE_SUBMIT,
        'info'=>AuthModel::SCE_AUTH_INFO,
        '_model'=>'custom\modules\account\models\AuthModel',
    ];

    //递交审核信息
    public function actionIndex(){
        //邀请门店提交信息
        //if(Yii::$app->CustomUser->CurrentUser->level != CustomUser::LEVEL_PARTNER){
            //$this->redirect('/account');
        //}
        throw new NotFoundHttpException;
        return $this->render('index');
    }
}
