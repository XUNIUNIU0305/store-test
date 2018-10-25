<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-12
 * Time: 下午1:24
 */

namespace mobile\modules\lottery\models\lottery;

use common\ActiveRecord\LotteryChanceItemAR as ItemAR;
use common\ActiveRecord\LotteryChanceAR as ChanceAR;
use common\ActiveRecord\LotteryChancePrizeAR as PrizeAR;
use common\models\parts\trade\WalletAbstract;
use custom\models\parts\trade\Wallet;


class ChanceItem extends \common\models\lottery\ChanceItem
{
    /**
     * 打开礼包
     * @param $plan_id
     * @param $uid
     * @return static
     * @throws \RuntimeException
     */
    public static function openItem($plan_id, $uid)
    {
        /** @var ItemAR $item */
        $item = ItemAR::find()
            ->alias('a')
            ->select(['a.*'])
            ->where(['a.status' => ItemAR::STATUS_DEFAULT])
            ->leftJoin(ChanceAR::tableName() . 'b', 'b.id = a.chance_id')
            ->andWhere(['b.plan_id' => $plan_id])
            ->andWhere(['b.custom_user_id' => $uid])
            ->limit(1)->one();
        if(!$item) throw new \RuntimeException('抽奖机会已用完');

        $item->status = ItemAR::STATUS_OPENED;
        $item->open_date = date('Y-m-d H:i:s');
        $item->update(false);
        return new static([
            'ar' => $item
        ]);
    }

    /**
     * 查询最近中奖
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryNotify()
    {
        return ItemAR::find()
            ->alias('a')
            ->select(['b.account', 'c.name', 'a.id'])
            ->where(['a.status' => ItemAR::STATUS_OPENED])
            ->andWhere(['a.result' => ItemAR::RESULT_WINNING])
            ->leftJoin(ChanceAR::tableName() . ' b', 'a.chance_id = b.id')
            ->leftJoin(PrizeAR::tableName() . ' c', 'c.chance_item_id = a.id')
            ->limit(50)
            ->asArray()->all();
    }

    /**
     * 发奖
     * @throws \Exception
     */
    public function AutoAwardPrize()
    {
        if(!$prize = $this->getPrize()) throw new \RuntimeException('奖品不存在');

        switch ($prize->type){
            case PrizeAR::TYPE_DEFAULT:
                break;
            case PrizeAR::TYPE_VOUCHER:{
                //发送代金券
                $transaction = \Yii::$app->db->beginTransaction();
                try{
                    $customerWallet = new Wallet([
                        'userId' => \Yii::$app->user->id,
                        'receiveType' => WalletAbstract::RECEIVE_VOUCHER,
                    ]);
                    $adminWallet = new \admin\models\parts\trade\Wallet;
                    if(!$adminWallet->pay($prize, $customerWallet)) throw new \Exception('发送代价券失败');
                    $transaction->commit();
                    break;
                }catch(\Exception $e){
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
    }
}
