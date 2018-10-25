<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use console\models\crontab\Runner;
use console\models\crontab\Tasks;
use yii\helpers\Console;

/**
 * 定时任务入口
 */
class CrontabController extends Controller{

    const AMQP_RUN_TIME = 600;

    public $time;

    private $_tasks;

    public function init(){
        parent::init();
        $this->_tasks = require(dirname(__DIR__) . '/models/crontab/TasksConfig.php');
    }

    /**
     * 查看是否可以使用多进程（复刻进程）
     */
    public function actionIndex(){
        $runner = new Runner;
        $this->stdout('Run Task With MultiProcess: ' . ($runner->canMultiProcess ? 'yes' : 'no') . PHP_EOL);
    }

    /**
     * 运行Crontab任务
     */
    public function actionRun(){
        $runner = new Runner;
        $tasks = new Tasks(['tasks' => $this->_tasks]);
        try{
            $runner->run($tasks);
        }catch(\Exception $e){
            Yii::error($e, __METHOD__);
        }
        return 0;
    }

    public function actionAmqpTest(){
        return 0;
        echo 'Amqp mode: ' . Yii::$app->amqp->getMode() . PHP_EOL;
        $message = 'hello world';
        try{
            echo "Message: [{$message}]" . PHP_EOL;
            Yii::$app->amqp->amqp->publish($message);
            echo "Published!" . PHP_EOL;
            sleep(1);
            if($receiveMessage = Yii::$app->amqp->amqp->get()){
                echo "Received message: [{$receiveMessage['body']}]" . PHP_EOL;
                Yii::$app->amqp->ack($receiveMessage['delivery_tag']);
                if($receiveMessage['body'] == $message){
                    echo 'run success' . PHP_EOL;
                    return 0;
                }else{
                    throw new \Exception('bad message');
                }
            }
            throw new \Exception('no message');
        }catch(\Exception $e){
            echo 'Error!' . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
        }
        return 0;
    }
    
    /**
     * 任务队列处理
     */
    public function actionAmqp(){
        $deadTime = Yii::$app->time->unixTime + self::AMQP_RUN_TIME;
        while(time() < $deadTime){
            if($message = Yii::$app->amqp->get()){
                $messageBody = $message['body'];
                try{
                    $refObj = new \ReflectionClass($messageBody['class']);
                    //var_dump($messageBody['class']);
                    unset($messageBody['class']);
                    $obj = $refObj->newInstance($messageBody);
                    if(($result = $obj->run()) === true){
                        Yii::info($message['body'], __METHOD__);
                        $transactionStatus = true;
                    }else{
                        Yii::warning(array_merge($message['body'], ['__result' => $result]), __METHOD__);
                        $transactionStatus = $this->getTransactionStatus();
                    }
                    //var_dump($result);
                }catch(\Exception $e){
                    Yii::error($e, __METHOD__);
                    //var_dump($e->getMessage());exit;
                    $transactionStatus = $this->getTransactionStatus();
                }
                Yii::$app->amqp->ack($message['delivery_tag']);
                if(!$transactionStatus)return 0;
            }else{
                sleep(1);
            }
        }
        return 0;
    }

    private function getTransactionStatus(){
        try{
            $transaction = Yii::$app->db->createCommand('SELECT * FROM `information_schema`.`INNODB_TRX`')->queryAll();
        }catch(\Exception $e){
            return true;
        }
        if($transaction){
            Yii::error($transaction, __METHOD__);
            return false;
        }else{
            return true;
        }
    }

    /**
     * 打印出Crontab任务列表
     */
    public function actionList(){
        if(is_null($this->time)){
            $tasks = array_keys($this->_tasks);
            $this->stdout('All tasks:' . PHP_EOL, Console::BOLD);
            if(empty($tasks)){
                $this->stdout('None.' . PHP_EOL);
            }else{
                foreach($tasks as $task){
                    $this->stdout($task . PHP_EOL);
                }
            }
            $this->stdout('Warning: this list do NOT verify the configuration of task!' . PHP_EOL, Console::FG_RED);
        }else{
            try{
                $tasksSetting = new Tasks(['tasks' => $this->_tasks]);
            }catch(\Exception $e){
                $this->stdout('Unavailable task configuration!' . PHP_EOL, Console::BOLD);
                return 0;
            }
            if(($tasks = $tasksSetting->getTasks($this->time)) === false){
                $this->stdout('Unavailable time option!' . PHP_EOL, Console::BOLD);
                return 0;
            }
            $this->stdout("The tasks running at [{$this->time}]:" . PHP_EOL, Console::BOLD);
            foreach($tasks as $taskName => $taskConfig){
                $this->stdout($taskName);
                foreach(['options', 'params'] as $configName){
                    $this->stdout('  ' . strtoupper($configName) . ':');
                    if(empty($taskConfig[$configName])){
                        $this->stdout('None.');
                    }else{
                        $this->stdout($configName == 'options' ? '{ ' : '[ ');
                        $configLength = count($taskConfig[$configName]);
                        $i = 1;
                        foreach($taskConfig[$configName] as $configKey => $configValue){
                            if($configName == 'options'){
                                $this->stdout("{$configKey}: {$configValue}");
                            }else{
                                $this->stdout($configValue);
                            }
                            if($i < $configLength){
                                $this->stdout(', ');
                                $i++;
                            }
                        }
                        $this->stdout($configName == 'options' ? ' }' : ' ]');
                    }
                }
                $this->stdout(PHP_EOL);
            }
            $this->stdout('Warning: this list do NOT verify the result of running task, you must run the task and check it manually!' . PHP_EOL, Console::FG_RED);
        }
        return 0;
    }

    public function options($actionID){
        return [
            'time',
        ];
    }

    public function optionAliases(){
        return [
            't' => 'time',
        ];
    }
}
