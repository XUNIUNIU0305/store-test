<?php
namespace common\components\basic;

use Yii;
use yii\di\ServiceLocator;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;

abstract class LoggedUserAbstract extends ServiceLocator{

    /**
     * 初始化
     * 合并通用组件和角色自定义组件
     */
    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        $this->setComponents(array_merge($this->getCommonComponents(), $this->getUserComponents()));
    }

    /**
     * 获取通用组件
     *
     * @return array
     */
    final protected function getCommonComponents(){
        return [];
    }

    /**
     * 获取角色自定义组件
     *
     * @return array
     */
    abstract protected function getUserComponents();
}
