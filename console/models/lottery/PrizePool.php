<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-9
 * Time: 下午3:37
 */

namespace console\models\lottery;

use yii\base\Object;
use yii\base\UserException;

class PrizePool extends Object
{
    /**
     * @var Prize[]
     */
    public $pool;

    /**
     * 抽奖用户
     * @var Chance[]
     */
    public $chance;

    public $plan;

    public static function getPoolByPlan(Plan $plan, array $chance)
    {
        return new static([
            'pool' => Prize::getListByPlanId($plan->id),
            'chance' => $chance,
            'plan' => $plan
        ]);
    }

    /**
     * 刷新用户队列
     * 生成打乱后队列
     * @return Chance[]
     */
    private function upsetChance()
    {
        $queue = [];
        foreach ($this->chance as $chance){
            if($chance->hasChance())
                $queue[] = $chance;
        }
        shuffle($queue);
        return $queue;
    }

    /**
     * 抽奖
     * 循环所有用户，随机中奖
     */
    public function lottery()
    {
        while (null !== $prize = array_shift($this->pool)){
            while (!$prize->isEmpty()){
                //验证是否不可能中奖
                $flag = true;
                foreach ($this->chance as $chance){
                    if($prize->prize_limit <= $chance->total_fee){
                        $flag = false;
                        break;
                    }
                }
                if($flag) break;

                $chanceQueue = $this->upsetChance();
                //没有抽奖机会时报错
                if(empty($chanceQueue)){
                    throw new \RuntimeException('中奖几率超过100%, 无法继续运行');
                }
                while (null !== $chance = array_shift($chanceQueue)){
                    //奖池为空时退出
                    if($prize->isEmpty()){
                        break;
                    }
                    //验证中奖额外条件
                    if($prize->prize_limit > $chance->total_fee) continue;
                    $chance->winning($prize);
                    $prize->winn += 1;
                }
            }
        }
    }
}