<?php
namespace custom\controllers;

use common\models\temp\PostModel;
use Yii;
use common\controllers\Controller;
use custom\models\IndexModel;

class IndexController extends Controller{

    public $layout = 'header_footer_index';

    protected $access = [
        'index' => [null, 'get'],
        'api-hostname' => [null, 'get'],
        'userinfo' => [null, 'get'],
        'captcha' => [null, 'get'],
//        'error' => [null, 'get'],
        'carousel' => [null, 'get'],
        'brand' => [null, 'get'],
        'floor' => [null, 'get'],
        'post' => [null, 'get']
    ];

    public function actionPost()
    {
        $model = new PostModel([
            'scenario' => PostModel::SCE_LIST
        ]);
        if($res = $model->process()){
            return $this->success($res);
        }
        return $this->failure($model->getErrorCode());
    }

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionApiHostname(){
        return $this->success(['hostname' => Yii::$app->params['API_Hostname']]);
    }

    public function actionUserinfo(){
        return $this->success(IndexModel::getUserInfo());
    }


    public function actionCarousel()
    {
        return $this->success(IndexModel::getCarousel());
    }


    public function actionBrand(){
        return $this->success(IndexModel::getBrand());
    }

    public function actionFloor(){
        return $this->success(IndexModel::getFloor());
    }

    /**
     * 静态action
     * 获取验证码，错误提示页面
     */
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
                'foreColor' => 0xcccccc,
                'backColor' => 0xffffff,
                'fontFile' => '@common/assets/font/edited.ttf',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }






}
