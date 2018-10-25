<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-12
 * Time: 下午4:17
 */

namespace mobile\modules\lottery\models\lottery;

use common\ActiveRecord\LotteryChanceItemAR;
use common\ActiveRecord\LotteryChanceAR;
use common\ActiveRecord\LotteryChancePrizeAR;
use common\models\lottery\Object;
use common\ActiveRecord\LotteryPlanProductAR;

/**
 * Class User
 * @package mobile\modules\lottery\models\lottery
 * @property $id
 */
class User extends Object
{
    /**
     * 获取用户中奖记录
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getWinning()
    {
        return LotteryChanceItemAR::find()
            ->alias('a')
            ->select(['a.id', 'a.open_date', 'b.plan_id', 'c.name'])
            ->where(['a.status' => LotteryChanceItemAR::STATUS_OPENED])
            ->andWhere(['a.result' => LotteryChanceItemAR::RESULT_WINNING])
            ->leftJoin(LotteryChanceAR::tableName() . ' b', 'b.id = a.chance_id')
            ->andWhere(['b.custom_user_id' => $this->id])
            ->leftJoin(LotteryChancePrizeAR::tableName() . 'c', 'c.chance_item_id = a.id')
            ->orderBy('a.open_date desc')
            ->asArray()->all();
    }

    public function getArms()
    {
        $chance = Chance::getInstanceByUid($this->id);
        foreach ($chance as &$value){
            $value = $value->getAttributes([
                'created', 'plan_item_id', 'chance', 'plan_id'
            ]);
        }
        $itemId = array_values(array_unique(array_column($chance, 'plan_item_id')));
        $itemAttributes = $this->queryItemIndexById($itemId);
        foreach ($chance as &$value){
            $value['name'] = $itemAttributes[$value['plan_item_id']];
            unset($value['plan_item_id']);
        }
        return $chance;
    }

    /**
     * 查询商品名称
     * @param $itemId
     * @return array
     */
    private function queryItemIndexById($itemId)
    {
        return LotteryPlanProductAR::find()
            ->select(['name', 'id'])
            ->where(['id' => $itemId])
            ->indexBy('id')
            ->column();
    }
}