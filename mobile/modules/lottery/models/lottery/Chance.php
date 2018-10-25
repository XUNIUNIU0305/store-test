<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-11
 * Time: 上午11:54
 */

namespace mobile\modules\lottery\models\lottery;

use common\ActiveRecord\LotteryChanceAR;
use common\ActiveRecord\LotteryChanceItemAR;
use common\ActiveRecord\LotteryPlanAR;
use yii\web\BadRequestHttpException;

class Chance extends \common\models\lottery\Chance
{
    /**
     * 获取武器数量
     * @param $uid
     * @return array
     */
    public static function getArmsByUid($uid)
    {
        $res = LotteryChanceItemAR::find()
            ->select(['plan_id', 'count(1) num'])
            ->alias('a')
            ->leftJoin(LotteryChanceAR::tableName() . ' b', 'b.id = a.chance_id')
            ->where(['b.custom_user_id' => $uid])
            ->andWhere(['a.status' => LotteryChanceItemAR::STATUS_DEFAULT])
            ->groupBy('plan_id')
            ->asArray()->all();

        $plan = LotteryPlanAR::find()
            ->select(['id'])
            ->column();
        foreach ($plan as &$item){
            $num = 0;
            foreach ($res as $key=>$value){
                if($item === $value['plan_id']){
                    $num += $value['num'];
                    unset($res[$key]);
                }
            }
            $item = [
                'id' => $item,
                'chance' => $num
            ];
        }
        return $plan;
    }

    /**
     * 获取用户工具
     * @param $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getInstanceByUid($uid)
    {
        $res = LotteryChanceAR::find()
            ->where(['custom_user_id' => $uid])
            ->all();
        foreach ($res as &$item){
            $item = new static([
                'ar' => $item
            ]);
        }
        return $res;
    }

    /**
     * @param $plan_id
     * @param $uid
     * @return static
     */
    public static function getInstanceByPlan($plan_id, $uid)
    {
        $ins = LotteryChanceAR::find()
            ->where(['plan_id' => $plan_id])
            ->andWhere(['custom_user_id' => $uid])
            ->limit(1)->one();
        if($ins === null) throw new \RuntimeException('抽奖机会不存在');
        return new static([
            'ar' => $ins
        ]);
    }
}