<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-14
 * Time: 下午3:17
 */

namespace mobile\modules\member\controllers;


use custom\modules\account\models\AuthModel;
use common\models\parts\custom\CustomUser;

class AuthController extends Controller
{
    protected $access = [
        'index'=>['@','get'],
        'submit'=>['@','post'],
        'info'=>['@','get'],
    ];

    protected $actionUsingDefaultProcess = [
        'submit'=> AuthModel::SCE_SUBMIT,
        'info'=> AuthModel::SCE_AUTH_INFO,
        '_model'=> AuthModel::class
    ];

    /**
     * 递交审核信息
     * @return string
     */
    public function actionIndex()
    {
        throw new \yii\web\NotFoundHttpException;
        return $this->render('index');
    }
}
