<?php
/**
 * User: JiangYi
 * Date: 2017/5/22
 * Time: 10:48
 * Desc:
 */

namespace mobile\modules\member\controllers;




use mobile\models\AuthorizeModel;
use mobile\modules\member\models\LoginModel;
use Yii;

class LoginController extends  Controller
{
    public $layout="member";
    public $access=[
        'index'=>[null,'get'],
        'wechat'=>[null,'get'],
        'logout'=>[null,'get'],
        'check-login'=>[null,'post'],
        'is-return'=>[null,'get'],

    ];

    public $actionUsingDefaultProcess=[
        'check-login'=>LoginModel::SCE_MOBILE_LOGIN,
        'is-return'=>LoginModel::SCE_IS_RETURN,
        '_model'=>'mobile\modules\member\models\LoginModel',
    ];


    /**
     * Author:JiangYi
     * Date:2017/5/23
     * Desc:登录页面视图输出
     * @return bool|string
     */
    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            if(LoginModel::saveWechatCode()){
                return $this->render('index');
            }else{
                $this->redirect(LoginModel::generateLoginUrl());
            }
        }else{
            $this->redirect('/member/index');
        }
    }

    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:注销退出登录
     */
    public function actionLogout(){
        if(Yii::$app->user->isGuest){
            Yii::$app->response->redirect('/index');
            return true;
        }
        $loginModel = new LoginModel([
            'scenario' => LoginModel::SCE_SIGN_OUT,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($result = $loginModel->process()){

            if($result === true){
                return $this->success([]);
            }else{
                $this->redirect($result);
            }
        }else{
            return $this->failure($loginModel->getErrorCode());
        }
    }

    /**
     * Author:JiangYi
     * Date:2017/5/23
     * Desc: 微信一键登录
     * @return bool
     */
    public function actionWechat(){
        $authorize = new AuthorizeModel([
            'scenario' => AuthorizeModel::SCE_AUTHORIZE,
            'attributes' => Yii::$app->request->get(),
        ]);
        if ($result = $authorize->process()) {
            if (is_string($result)) {
                //转至授权页面
                @header("Location:$result");exit();
            } elseif ($result===true) {
                //授权成功，并且成功登录
                @header("Location:" . Yii::$app->user->returnUrl);exit();
            }
        }
        //登录失败转至首页
        @header("Location:/member/login/index?status=0");exit();
    }

}
