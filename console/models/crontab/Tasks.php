<?php
namespace console\models\crontab;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

class Tasks extends Object{

    const TIME_MINUTE = 0;
    const TIME_HOUR = 1;
    const TIME_DAYOFMONTH = 2;
    const TIME_MONTH = 3;
    const TIME_DAYOFWEEK = 4;

    public $tasks = [];

    private $_tasks;
    private $_tasksRunTime;
    private $_timeFormatter;

    public function init(){
        $this->_timeFormatter = new TimeFormatter;
        if(!is_array($this->tasks))throw new InvalidConfigException('param [tasks] must be an array');
        if(($this->_tasksRunTime = $this->formatTasksRunTime($this->tasks, false)) === false)throw new InvalidConfigException('unavailable time configuration');
        if(($this->_tasks = $this->formatOptionsAndParams($this->tasks, false)) === false)throw new InvalidConfigException('unavailable options/params configuration');
    }

    /**
     * 获取指定时间所需运行的任务列表
     *
     * @param string $fullDate 时间；未定义则默认当前时间
     *
     * @return array
     */
    public function getTasks(string $fullDate = null){
        if(!$runTime = $this->formatDate(is_null($fullDate) ? Yii::$app->time->fullDate : $fullDate, false))return false;
        $tasks = [];
        foreach($this->_tasksRunTime as $taskName => $taskTime){
            if(in_array($runTime[self::TIME_MINUTE], $taskTime[self::TIME_MINUTE]) &&
                in_array($runTime[self::TIME_HOUR], $taskTime[self::TIME_HOUR]) &&
                in_array($runTime[self::TIME_DAYOFMONTH], $taskTime[self::TIME_DAYOFMONTH]) &&
                in_array($runTime[self::TIME_MONTH], $taskTime[self::TIME_MONTH]) &&
                in_array($runTime[self::TIME_DAYOFWEEK], $taskTime[self::TIME_DAYOFWEEK])
            ){
                $tasks[$taskName] = $this->_tasks[$taskName];
            }
        }
        return $tasks;
    }

    protected function formatOptionsAndParams(array $tasks, $return = 'throw'){
        $formatedResult = [];
        foreach($tasks as $taskName => $taskConfig){
            foreach(['options', 'params'] as $configName){
                if(isset($taskConfig[$configName])){
                    if(is_array($taskConfig[$configName])){
                        $formatedResult[$taskName][$configName] = $taskConfig[$configName];
                    }else{
                        return Yii::$app->EC->callback($return, "unavailable configuration of task's {$configName}");
                    }
                }else{
                    $formatedResult[$taskName][$configName] = [];
                }
            }
        }
        return $formatedResult;
    }

    protected function formatDate(string $fullDate, $return = 'throw'){
        $dateAndTime = explode(' ', $fullDate);
        if(count($dateAndTime) == 2 && $dateAndTime[1]){
            $splitTime = explode(':', $dateAndTime[1]);
            if(count($splitTime) == 2){
                $fullDate .= ':00';
            }elseif(count($splitTime) != 3){
                return Yii::$app->EC->callback($return, 'unavailable time');
            }
            if(!($unixTime = strtotime($fullDate)) || (date('Y-m-d H:i:s', $unixTime) != $fullDate))return Yii::$app->EC->callback($return, 'unavailable time');
            return [
                self::TIME_MINUTE => date('i', $unixTime),
                self::TIME_HOUR => date('H', $unixTime),
                self::TIME_DAYOFMONTH => date('d', $unixTime),
                self::TIME_MONTH => date('m', $unixTime),
                self::TIME_DAYOFWEEK => date('w', $unixTime),
            ];
        }else{
            return Yii::$app->EC->callback($return, 'unavailable time');
        }
    }

    protected function formatTasksRunTime(array $tasks, $return = 'throw'){
        $formatedTime = [];
        foreach($tasks as $taskName => $taskConfig){
            if(!$runTime = $taskConfig['time'] ?? false)return Yii::$app->EC->callback($return, 'miss configuration [time]');
            foreach($runTime as $key => $time){
                switch($key){
                    case self::TIME_MINUTE:
                    case self::TIME_HOUR:
                    case self::TIME_DAYOFMONTH:
                    case self::TIME_MONTH:
                    case self::TIME_DAYOFWEEK:
                        $result = $this->formatTime($time, $key, false);
                        break;

                    default:
                        return Yii::$app->EC->callback($return, 'undefined time configuration');
                        break;

                }
                if($result){
                    $formatedTime[$taskName][$key] = $result;
                }else{
                    return Yii::$app->EC->callback($return, 'unavailable time');
                }
            }
        }
        return $formatedTime;
    }

    protected function formatTime(string $time, int $type, $return = 'throw'){
        switch($type){
            case self::TIME_MINUTE:
                $timeAvailable = [
                     0,  1,  2,  3,  4,  5,  6,  7,  8,  9,
                    10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
                    20, 21, 22, 23, 24, 25, 26, 27, 28, 29,
                    30, 31, 32, 33, 34, 35, 36, 37, 38, 39,
                    40, 41, 42, 43, 44, 45, 46, 47, 48, 49,
                    50, 51, 52, 53, 54, 55, 56, 57, 58, 59,
                ];
                break;

            case self::TIME_HOUR:
                $timeAvailable = [
                     0,  1,  2,  3,  4,  5,  6,  7,  8,  9,
                    10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
                    20, 21, 22, 23,
                ];
                break;

            case self::TIME_DAYOFMONTH:
                $timeAvailable = [
                         1,  2,  3,  4,  5,  6,  7,  8,  9,
                    10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
                    20, 21, 22, 23, 24, 25, 26, 27, 28, 29,
                    30, 31,
                ];
                break;

            case self::TIME_MONTH:
                $timeAvailable = [
                         1,  2,  3,  4,  5,  6,  7,  8,  9,
                    10, 11, 12,
                ];
                break;

            case self::TIME_DAYOFWEEK:
                $timeAvailable = [
                     0,  1,  2,  3,  4,  5,  6,
                ];
                break;

            default:
                return Yii::$app->EC->callback($return, 'undefined time type');
                break;
        }
        $this->_timeFormatter->setAvailableTime($timeAvailable);
        return $this->_timeFormatter->format($time, $return);
    }

}
