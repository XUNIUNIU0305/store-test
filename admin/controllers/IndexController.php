<?php
namespace admin\controllers;


use Yii;

use admin\models\IndexModel;
use yii\helpers\Url;

class IndexController extends \common\controllers\Controller
{

    public $layout = 'global';

    protected $access = [
        'index' => [null, 'get'],
        'login' => ['?', 'post'],
        'logout' => ['@', 'get'],
        'api-hostname' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'login' => IndexModel::SCE_LOGIN,
        '_model' => '\admin\models\IndexModel',
    ];

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->render('index');
        } else {
            $this->redirect(Url::toRoute('/main'));
        }
    }

    public function actionLogout()
    {
        IndexModel::logout();
        Yii::$app->user->loginRequired();
    }

    public function actionApiHostname()
    {
        return $this->success(['hostname' => Yii::$app->params['API_Hostname']]);
    }

    public function actions()
    {
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
                'backColor' => 0x5f5f5f,
                'fontFile' => '@common/assets/font/edited.ttf',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
