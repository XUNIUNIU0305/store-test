<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/4
 * Time: 下午3:33
 */

namespace custom\modules\account\controllers;


use common\controllers\Controller;
use custom\modules\account\models\ArticleModel;

use Yii;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller
{

    protected $access = [
        'list' => ['@', 'get'],
        'get-detail'=>['@','get'],
        'get-open-detail'=>[null,'get'],
        'wechat'=>[null,'get'],
        'edit'=>['@','post'],
        'create'=>['@','post'],
    ];

    protected $actionUsingDefaultProcess = [
        'list'=>ArticleModel::SCE_LIST,
        'get-detail' => [
            'scenario'=>ArticleModel::SCE_DETAIL,
            'convert'=>false,
        ],
        'get-open-detail' => [
            'scenario'=>ArticleModel::SCE_OPEN_DETAIL,
            'convert'=>false,
        ],
        'edit'=>ArticleModel::SCE_EDIT,
        'create'=>ArticleModel::SCE_CREATE,
        '_model' => '\custom\modules\account\models\ArticleModel',
    ];


    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('index');
    }

    public function actionDetail(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('detail');
    }

    public function actionWechat(){
        $this->layout='wechat';
        $articleModel = new ArticleModel([
            'scenario' => ArticleModel::SCE_LOG,
            'attributes' => Yii::$app->request->get(),
        ]);
        if ($articleModel->log() === false){
            throw new NotFoundHttpException();
        }
        return $this->render('wechat');
    }

    public function actionQrcode(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        $articleModel = new ArticleModel([
            'scenario' => ArticleModel::SCE_QR_CODE,
            'attributes' => Yii::$app->request->get(),
        ]);
        return $articleModel->getQrCode();
    }
}