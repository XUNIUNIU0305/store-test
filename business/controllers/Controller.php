<?php
namespace business\controllers;

use Yii;
use yii\web\ForbiddenHttpException;

class Controller extends \common\controllers\Controller{

    protected $pages = [];

    public function behaviors(){
        if(empty($this->access))return [];
        list($guest, $loggedIn, $level, $requestMethod) = $this->getAllowRules();
        if(!$onlyActions = array_merge($guest, $loggedIn, $this->generateLevelActions($level))){
            return [
                'verbs' => [
                    'class' => 'yii\filters\VerbFilter',
                    'actions' => $requestMethod,
                ],
            ];
        }
        $totalRules = array_merge(
            $this->generateRules($guest, 'guest'),
            $this->generateRules($loggedIn, 'loggedIn'),
            $this->generateRules($level, 'level')
        );
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'only' => $onlyActions,
                'rules' => $totalRules,
                'denyCallback' => function($rule, $action){
                    if($action->id == 'index' || in_array($action->id, $this->pages)){
                        Yii::$app->user->loginRequired();
                    }else{
                        throw new ForbiddenHttpException;
                    }
                }
            ],
        ];
    }

    protected function getAllowRules(){
        $guest = [];
        $loggedIn = [];
        $level = [];
        $requestMethod = [];
        foreach($this->access as $action => $rule){
            if($rule[0] == '?'){
                $guest[] = $action;
            }elseif($rule[0] == '@'){
                $loggedIn[] = $action;
            }elseif($rule[0]){
                $level[$rule[0]][] = $action;
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
            $level,
            $requestMethod,
        ];
    }

    protected function generateLevelActions(array $level){
        return array_reduce($level, function($actions, $rules){
            return array_merge($actions, $rules);
        }, []);
    }

    protected function generateRules($actions, $type){
        switch($type){
            case 'guest':
            case 'loggedIn':
                $role = $type == 'guest' ? '?' : '@';
                return $actions ? [[
                    'actions' => $actions,
                    'allow' => true,
                    'roles' => [$role],
                ]] : [];
                break;

            case 'level':
                $rules = [];
                foreach($actions as $role => $action){
                    $rules[] = [
                        'actions' => $action,
                        'allow' => true,
                        'roles' => [$role],
                    ];
                }
                return $rules;
                break;

            default:
                return [];
        }
    }
}
