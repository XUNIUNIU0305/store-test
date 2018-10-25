<?php
namespace common\controllers;

use Yii;
use common\traits\ErrorMessageTrait;
use yii\web\ForbiddenHttpException;

class Controller extends \yii\web\Controller{

    use ErrorMessageTrait;

    /**
     * 配置需要执行默认处理流程的操作
     * $actionUsingDefaultProcess = [
     *     actionName => [
     *         'scenario' => MODEL::SCENARIO,
     *         'method' => 'get' || 'post',
     *         'model' => 'ModelName with full namespace',
     *         'convert' => Boolean,
     *         'queryMaster' => Boolean
     *     ],
     *     ...
     *     '_model' => modelName
     * ];
     * 模型类可由键名_model全局配置，也可以每个操作单独配置
     * actionName配置数组内model名称优先级高于_model配置
     */
    protected $actionUsingDefaultProcess = [];

    /**
     * 配置控制器访问条件
     * $access = [
     *     actionName => [userType, allowMethod],
     * ];
     *
     * actionName => String
     * userType => ? | @ | null
     * allowMethod => Array | get | post
     */
    protected $access = [];

    /**
     * inherit
     */
    public function behaviors(){
        if(empty($this->access))return [];
        list($guest, $loggedIn, $requestMethod) = $this->getAllowRules();
        if(!$onlyActions = array_merge($guest, $loggedIn)){
            return [
                'verbs' => [
                    'class' => 'yii\filters\VerbFilter',
                    'actions' => $requestMethod,
                ],
            ];
        }
        $guestRule = $guest ? [
            'actions' => $guest,
            'allow' => true,
            'roles' => ['?']
        ] : null;
        $loggedInRule = $loggedIn ? [
            'actions' => $loggedIn,
            'allow' => true,
            'roles' => ['@']
        ] : null;
        $totalRules = [];
        is_null($guestRule) or $totalRules[] = $guestRule;
        is_null($loggedInRule) or $totalRules[] = $loggedInRule;
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'ruleConfig' => [
                    //邀请门店强制提交信息
                    //'class' => (Yii::$app->id == 'app-custom' || Yii::$app->id == 'app-mobile') ? 'common\models\parts\partner\AccessRule' : 'yii\filters\AccessRule',
                    'class' => 'yii\filters\AccessRule',
                ],
                'only' => $onlyActions,
                'rules' => $totalRules,
                'denyCallback' => function($rule, $action){
                    //邀请门店强制提交信息
                    //if(Yii::$app->id == 'app-custom' || Yii::$app->id == 'app-mobile'){
                        //if(!Yii::$app->user->isGuest){
                            //if(Yii::$app->id == 'app-custom'){
                                //$this->redirect('/account/auth');
                            //}else{
                                //$this->redirect('/member/auth');
                            //}
                            //return;
                        //}
                    //}
                    if($action->id == 'index'){
                        Yii::$app->user->loginRequired();
                    }else{
                        throw new ForbiddenHttpException;
                    }
                }
            ],
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => $requestMethod,
            ],
        ];
    }

    /**
     * 获取访问规则
     *
     * @return array
     */
    protected function getAllowRules(){
        $guest = [];
        $loggedIn = [];
        $requestMethod = [];
        foreach($this->access as $action => $rule){
            if($rule[0] == '?'){
                $guest[] = $action;
            }elseif($rule[0] == '@'){
                $loggedIn[] = $action;
            }
            if(isset($rule[1])){
                $requestMethod[$action] = (array)$rule[1];
            }else{
                $requestMethod[$action] = ['get'];
            }
        }
        return [
            $guest,
            $loggedIn,
            $requestMethod,
        ];
    }

    /**
     * 返回JSON
     *
     * @param $code integer 响应状态
     * @param $param array 响应数据
     * @param $convert 是否转换数字字符串
     *
     * @return json
     */
    protected function returnJson($code, $param, $convert){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($convert){
            $param = $this->convertNumericType($param);
        }
        return ['status' => $code, 'data' => $param];
    }

    /**
     * 返回成功状态
     *
     * @param $param array 数据
     *
     * @return json
     */
    protected function success(array $param, $convert = true){
        return $this->returnJson(200, $param, $convert);
    }

    /**
     * 返回错误状态
     *
     * @param $code 错误码
     *
     * @return json
     */
    protected function failure($code, $convert = true){
        if(!$code = (int)$code)throw new \Exception('need a non-zero integer');
        return $this->returnJson($code, ['errMsg' => $this->getErrMsg($code)], $convert);
    }

    /**
     * 将数字字符串转换成整数或浮点数
     *
     * @return array
     */
    protected function convertNumericType($param){
        return array_map(function($v){
            if(is_numeric($v)){
                return ($v + 0);
            }else if(is_array($v)){
                return $this->convertNumericType($v);
            }else{
                return $v;
            }
        }, $param);
    }

    /**
     * 改写【创建操作】
     * 添加默认处理流程
     *
     * @return Object|null
     */
    public function createAction($id){
        $action = parent::createAction($id);
        if(is_null($action)){
            if(isset($this->actionUsingDefaultProcess[$id])){
                return new \yii\base\InlineAction($id, $this, 'defaultProcess');
            }
        }
        return $action;
    }

    /**
     * 默认处理流程
     *
     * 实例化模型类 => 设置场景、属性 => 执行Model::process() => 返回Json数据
     *
     * @return Json
     */
    public function defaultProcess(){
        $actionConfig = $this->actionUsingDefaultProcess[$this->action->id];
        if(is_string($actionConfig)){
            $scenario = $actionConfig;
            if((!$method = $this->access[$this->action->id][1] ?? null) || !in_array($method, ['get', 'post']))throw new \Exception('undefined request method');
            if(!$modelName = $this->actionUsingDefaultProcess['_model'] ?? null)throw new \Exception('undefined model class');
            $convert = true;
            $queryMaster = false;
        }elseif(is_array($actionConfig)){
            if(!$scenario = $actionConfig['scenario'] ?? null)throw new \Exception('undefined model scenario');
            if((!$method = $actionConfig['method'] ?? ($this->access[$this->action->id][1] ?? null)) || !in_array($method, ['get', 'post']))throw new \Exception('undefined request method');
            if(!$modelName = $actionConfig['model'] ?? ($this->actionUsingDefaultProcess['_model'] ?? null))throw new \Exception('undefined model class');
            $convert = $actionConfig['convert'] ?? true;
            $queryMaster = (bool)($actionConfig['queryMaster'] ?? false);
        }else{
            throw new \Exception('unavailable configuration');
        }
        if($queryMaster){
            Yii::$app->db->queryMaster = true;
        }
        $model = (new \ReflectionClass($modelName))->newInstance([
            'scenario' => $scenario,
            'attributes' => Yii::$app->request->$method(),
        ]);
        if(($processResult = $model->process()) !== false){
            return $this->success($processResult === true ? [] : $processResult, $convert);
        }else{
            return $this->failure($model->errorCode);
        }
    }
}
