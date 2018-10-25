<?php
namespace console\models\crontab;

use Yii;
use yii\base\Object;
use Ko\ProcessManager;
use Ko\Process;

class Runner extends Object{

    const MAX_PROCESS = 1000; //最大进程复刻数

    public $multiProcess; //是否启用进程复刻

    private $_multiProcess;

    public function init(){
        if(is_null($this->multiProcess)){
            $this->_multiProcess = extension_loaded('pcntl') && extension_loaded('posix');
        }else{
            $this->_multiProcess = (boolean)$this->multiProcess;
        }
    }

    /**
     * 执行任务
     *
     * @param Object $tasks 任务对象
     * @param string $fullDate 执行指定时间应该执行的所有任务，默认执行当前时间应该执行的所有任务
     * @param mix $return 抛错
     *
     * @return true|mix
     */
    public function run(Tasks $tasks, string $fullDate = null, $return = 'throw'){
        if(($taskList = $tasks->getTasks($fullDate)) === false)return Yii::$app->EC->callback($return, 'unavailable task configuration');
        if($this->_multiProcess){
            if(count($taskList) > self::MAX_PROCESS)return Yii::$app->EC->callback($return, 'too much tasks');
            try{
                $this->runUsingMultiProcess($taskList);
            }catch(\Exception $e){
                return Yii::$app->EC->callback($return, $e);
            }
        }else{
            try{
                $this->runOneByOne($taskList);
            }catch(\Exception $e){
                return Yii::$app->EC->callback($return, $e);
            }
        }
        return true;
    }

    /**
     * 使用多进程执行任务
     */
    protected function runUsingMultiProcess(array $taskList){
        $processManager = new ProcessManager();
        $processManager->setProcessTitle('PHP_CRONTAB_MASTER');
        foreach($taskList as $target => $config){
            $processManager->fork(function(Process &$process)use($target, $config){
                $process->setProcessTitle('PHP_CRONTAB_CHILD_' . str_replace('/', '_', $target));
                $this->runOneByOne([$target => $config]);
                return 0;
            });
        }
        $processManager->wait();
        return true;
    }

    /**
     * 使用foreach循环执行任务
     */
    protected function runOneByOne(array $taskList){
        foreach($taskList as $target => $config){
            try{
                $taskResult = $this->runTask($target, $config['options'], $config['params']);
                if($taskResult === 0){
                    Yii::info($config, $target);
                }else{
                    Yii::warning(array_merge($config, ['result' => $taskResult]), $target);
                }
            }catch(\Exception $e){
                Yii::error($e, $target);
            }
        }
        return true;
    }

    /**
     * 执行指定任务
     *
     * @param string $target 有效的控制器和方法名称 e.g. 'controller/action'
     * @param array $options 任务的选项
     * @param array $params 任务的参数
     * @param mix $return 抛错
     *
     * @return integer|mix
     */
    public function runTask(string $target, array $options = [], array $params = [], $return = 'throw'){
        try{
            return Yii::$app->runAction($target, array_merge($options, $params));
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
    }

    /**
     * 是否可以使用进程复刻
     *
     * @return booolean
     */
    public function getCanMultiProcess(){
        return $this->_multiProcess;
    }
}
