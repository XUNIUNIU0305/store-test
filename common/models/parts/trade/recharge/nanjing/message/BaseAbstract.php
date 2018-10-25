<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\components\amqp;
use yii\di\ServiceLocator;
use yii\base\InvalidConfigException;
use yii\log\DbTarget;
use common\ActiveRecord\NanjingCallbackLogAR;
use common\components\amqp\Message;
use common\models\parts\trade\recharge\nanjing\data\NanjingCallback;
use common\components\amqp\AmqpTaskAbstract;

abstract class BaseAbstract extends AmqpTaskAbstract{

    public $callback;
    public $callbackPlain;
    public $originalPlain;
    public $sleepTime = -1;
    protected $instance;

    public function init(){
        if($this->callback instanceof NanjingCallback){
            $this->callbackPlain = $this->callback->Plain;
            $this->originalPlain = $this->callback->OriginalPlain;
            $this->callback = false;
        }
        if(empty($this->callbackPlain) || empty($this->originalPlain))throw new InvalidConfigException('plain message missing');
        $instanceConfig = $this->getInstanceConfig();
        if(!empty($instanceConfig)){
            $this->instance = new ServiceLocator;
            $this->instance->setComponents($instanceConfig);
        }
        $this->sleepTime += 1;
        Yii::$app->db->queryMaster = true;
    }

    public function run(){
        if($this->sleepTime > 0 && $this->sleepTime < 100){
            sleep($this->sleepTime);
        }
        if($this->sleepTime >= 100){
            Yii::error($this->originalPlain, static::className());
            return true;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($this->runExtra() == false)throw new \Exception;
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            $message = new Message($this);
            Yii::$app->amqp->publish($message);
            Yii::error($e, __METHOD__);
        }
        return true;
    }

    abstract protected function runExtra() : bool;

    protected function getInstanceConfig() : array{
        return [];
    }
}
