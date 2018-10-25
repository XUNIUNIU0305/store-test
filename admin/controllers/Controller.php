<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17-3-1
 *     Time: 下午3:49
 */

namespace admin\controllers;

use admin\components\handler\AdminUserHandler;
use admin\models\parts\role\AdminAccount;
use admin\models\parts\role\AdminUser;
use Yii;
use common\traits\ErrorMessageTrait;
use yii\web\ForbiddenHttpException;


class Controller extends \common\controllers\Controller
{

    use ErrorMessageTrait;

    /**
     * 配置需要执行默认处理流程的操作
     * $actionUsingDefaultProcess = [
     *     actionName => [modelScenario, requestMethod(, modelName)],
     *     ...
     *     '_model' => modelName
     * ];
     * actionName => String, ignore string 'action'
     * modelScenario => String
     * requestMethod => 'get' | 'post'
     * modelName => String, full class name with namespace
     * 模型类可由键名_model全局配置，也可以每个操作单独配置
     */
    protected $actionUsingDefaultProcess = [];

    /**
     * 配置控制器访问条件
     * $access = [
     *     actionName => [userType, allowMethod],
     * ];
     * actionName => String
     * userType => ? | @ | null
     * allowMethod => Array | get | post
     */
    protected $access = [];

    /**
     * inherit
     */
    public function behaviors()
    {

        $requestMethod=[];
        if(array_key_exists($this->action->id,$this->access)){
           $requestMethod=[$this->action->id=>[$this->access[$this->action->id][1]]];
        }

       // list($guest, $loggedIn, $requestMethod) = $this->getAllowRules();


        //配置默认值
        $rule= [
            'actions' =>[$this->action->id],
            'allow' => 0,
            'roles' => ['@'],
        ];
        if (!Yii::$app->user->getIsGuest()) {
            //获取用户角色
            if((new AdminAccount(['id' => yii::$app->user->id]))->isExistsPermission($this->module->id,Yii::$app->controller->id,$this->action->id)){
                //配置规则
                $rule=[
                    'actions'=>[$this->action->id],
                    'allow'=>1,
                    'roles'=>['@'],
                ];
            }
        }

       //返回规则
       return  [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'only' => [$this->action->id],
                'rules' =>[$rule],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException;
                }
            ],
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => $requestMethod,
            ],
        ];


    }



}
