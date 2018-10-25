<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-11
 * Time: 上午11:49
 */

namespace mobile\modules\lottery\models;


use mobile\modules\lottery\models\lottery\Chance;
use common\models\Model;
use mobile\modules\lottery\models\lottery\ChanceItem;
use common\ActiveRecord\LotteryChanceItemAR;

class GameModel extends Model
{
    const SCE_ARMS = 'arms';
    const SCE_OPEN = 'open';

    /**
     * 计划ID
     * @var int
     */
    public $plan_id;

    public function scenarios()
    {
        return [
            self::SCE_ARMS => [],
            self::SCE_OPEN => [
                'plan_id'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['plan_id'],
                'integer',
                'min' => 1,
                'message' => 9002
            ],
            [
                ['plan_id'],
                'required',
                'message' => 9001
            ]
        ];
    }

    /**
     * 获取可用工具
     */
    public function arms()
    {
        return Chance::getArmsByUid(\Yii::$app->user->id);
    }

    /**
     * 打开礼包
     * @return int|false
     */
    public function open()
    {
        try {
            $chance = ChanceItem::openItem($this->plan_id, \Yii::$app->user->id);
            //发送奖品
            if($chance->result === LotteryChanceItemAR::RESULT_WINNING){
                $chance->AutoAwardPrize();
            }
            return $chance->id;
        } catch (\Exception $e){
            $this->addError('plan_id', 10080);
            return false;
        }
    }
}