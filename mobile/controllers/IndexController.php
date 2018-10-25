<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/15
 * Time: 16:06
 */

namespace mobile\controllers;


use common\models\temp\PostModel;
use mobile\models\HomeModel;
use Yii;

class IndexController extends Controller
{
    public $layout = 'global';

    protected $access=[
        'index'=>[null,'get'],
        'carousel' => [null, 'get'],
        'post' => [null, 'get'],
        'error'=>[null,'get'],
        'get-user-status'=>[null,'get'],
    ];

    protected $actionUsingDefaultProcess=[
        'get-user-status'=>HomeModel::SCE_GET_USER_STATUS,
        'carousel' => HomeModel::SCE_GET_CAROUSEL,
        '_model'=>'\mobile\models\HomeModel',
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


    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:微信端首页
     * @return string
     */
    public function actionIndex(){
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
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

}
