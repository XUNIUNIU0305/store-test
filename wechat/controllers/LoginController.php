<?php
/**
 * User: JiangYi
 * Date: 2017/5/22
 * Time: 10:48
 * Desc:
 */

namespace wechat\controllers;

use wechat\models\AuthorizeModel;
use wechat\models\LoginModel;
use Yii;

class LoginController extends  Controller
{
    public $access=[
        'index'=>[null,'get'],
        'wechat'=>[null,'get'],
        'logout'=>[null,'get']
    ];

    /**
     * Author:JiangYi
     * Date:2017/5/23
     * Desc:登录页面视图输出
     * @return bool|string
     */
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionLogin()
    {
        if(!Yii::$app->getUser()->isGuest)
            return $this->success([]);
        $loginModel = new LoginModel([
            'scenario' => LoginModel::SCE_LOGIN,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $loginModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($loginModel->getErrorCode());
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
     * @return string
     */
    public function actionWechat(){
        $authorize = new AuthorizeModel([
            'scenario' => AuthorizeModel::SCE_AUTHORIZE,
            'attributes' => Yii::$app->request->get(),
        ]);
        if ($result = $authorize->process()) {
            if (is_string($result)) {
                //转至授权页面
                return $this->redirect($result);
            } elseif ($result===true) {
                //授权成功，并且成功登录
                return $this->redirect(['', '#' => '/member']);
            }
        }
        //登录失败转至首页
        return $this->redirect(['', '#'=>'/login/fail']);
    }

}
