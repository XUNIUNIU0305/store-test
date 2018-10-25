<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/15
 * Time: 16:06
 */

namespace wechat\controllers;

use common\models\Model;
use yii\base\Exception;
use yii\web\Response;
use yii\web\HttpException;
use wechat\models\HomeModel;
use Yii;

class IndexController extends Controller
{
    protected $access=[
        'index'=>[null,'get'],
        'get-user-status'=>[null,'get'],
        'balance' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess=[
        'get-user-status'   =>HomeModel::SCE_GET_USER_STATUS,
        'balance'           => HomeModel::SCE_GET_BALANCE,
        '_model'            => HomeModel::class
    ];

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'main';
        return $this->render('index');
    }

    /**
     * Author:JiangYi
     * Date:2017/5/24
     * Desc:获取api接口地址
     * @return \common\controllers\json
     */
    public function actionApiHostname(){
        return $this->success(['hostname' => Yii::$app->params['API_Hostname']]);
    }

    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:默认方法配置
     * @return Response
     */
    public function actionError()
    {
        if (($exception = \Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, \Yii::t('yii', 'Page not found.'));
        }
        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        $response = \Yii::$app->getResponse();
        $response->data = [
            'code'  => $code,
            'msg'   => $exception->getMessage()
        ];
        $response->format = Response::FORMAT_JSON;
        return $response;
    }

}