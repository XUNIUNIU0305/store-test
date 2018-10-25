<?php
namespace business\controllers;

use Yii;
use business\models\IndexModel;

class IndexController extends Controller{

    public $layout = 'index';

    protected $access = [
        'index' => [null, 'get'],
        'login' => ['?', 'post'],
        'api-hostname' => [null, 'get'],

    ];

    protected $actionUsingDefaultProcess = [
        'login' => IndexModel::SCE_LOGIN,
        '_model' => '\business\models\IndexModel',
    ];

    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            return $this->render('index');
        }else{
            $this->redirect('/main');
        }
    }

    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:添加获取api路径方法
     * @return \common\controllers\json
     */
    public function actionApiHostname(){
        return $this->success(['hostname' => Yii::$app->params['API_Hostname']]);
    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'transparent' => true,
                'width' => 70,
                'height' => 40,
                'padding' => 0,
                'offset' => 0,
                'minLength' => 4,
                'maxLength' => 4,
                'foreColor' => 0xcccddd,
                'backColor' => 0xcccdde,
                'fontFile' => '@common/assets/font/edited.ttf',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
