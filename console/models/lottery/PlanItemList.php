<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午4:01
 */

namespace console\models\lottery;


use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\LotteryChanceAR;
use common\ActiveRecord\LotteryPlanAR;
use common\ActiveRecord\LotteryPlanProductAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSKUAR;
use common\models\parts\Order;
use yii\base\Object;

class PlanItemList extends Object
{
    /**
     * @var LotteryPlanProductAR[]
     */
    public $items;

    /**
     * @var Plan
     */
    public $plan;

    /**
     * 查询消费记录
     */
    public function queryUserChance()
    {
        $res = [];
        foreach ($this->items as $item){
            if($item->brand_id){
                $column = ProductAR::find()
                    ->select(['id'])
                    ->where(['supply_user_id' => $item->brand_id])
                    ->column();
                $condition = $column;
            } elseif ($item->product_id) {
                $condition = $item->product_id;
            } else {
                throw new \RuntimeException('计划商品数据错误');
            }

            $res[$item->id] = $this->queryOrder($condition);
        }
        $tmp = static::queryFix();
        foreach ($tmp as $key=>$val){
            $res[$key] = $val;
        }
        return static::parseItems($res, $this->plan->money_limit);
    }

    /**
     * 整理数据
     * @param $items
     * @param $money_limit
     * @return array
     */
    public static function parseItems($items, $money_limit)
    {
        $res = [];
        foreach ($items as $key=>$value){
            foreach ($value as $item){
                $item['num'] = floor($item['fee'] / $money_limit);
                $item['account'] = static::queryUser($item['custom_user_id']);
                $item['item_id'] = $key;
                $res[] = $item;
            }
        }

        $rel = [];
        foreach ($res as $item){
            $uid = $item['custom_user_id'];
            $rel[$uid] = isset($rel[$uid]) ? $rel[$uid] + $item['fee'] : $item['fee'];
        }
        foreach ($res as &$item){
            $item['total_fee'] = $rel[$item['custom_user_id']];
        }
        return $res;
    }

    /**
     * 生成用户消费记录
     * @return array
     */
    public function generateChance()
    {
        $items = $this->queryUserChance();
        $res = [];
        foreach ($items as $item){
            if($item['num'] <= 0) continue;
            //生成用户消费记录
            $chance = new LotteryChanceAR();
            $chance->custom_user_id = $item['custom_user_id'];
            $chance->account = $item['account'];
            $chance->plan_id = $this->plan->id;
            $chance->plan_item_id = $item['item_id'];
            $chance->total_fee = $item['fee'];
            $chance->plan_total_fee = $item['total_fee'];
            $chance->chance = $item['num'];
            $chance->insert(false);
            $res[] = new Chance(['ar' => $chance]);
        }

        $this->updateFix();
        return $res;
    }

    /**
     * 用户暂存
     * @var array
     */
    static $userList = [];

    /**
     * 查询用户账号
     * @param $id
     * @return mixed|static
     */
    public static function queryUser($id)
    {
        if(isset(static::$userList[$id])){
            return static::$userList[$id];
        }
        return static::$userList[$id] = CustomUserAR::find()
            ->select(['account'])
            ->where(['id' => $id])
            ->scalar();
    }

    public static $status = [
        Order::STATUS_UNDELIVER,
        Order::STATUS_DELIVERED,
        Order::STATUS_CONFIRMED,
        Order::STATUS_CLOSED
    ];

    /**
     * 查询用户消费
     * @param $product_id
     * @return array|\yii\db\ActiveRecord[]
     */
    private function queryOrder($product_id)
    {
        return $query = OrderAR::find()
            ->alias('a')
            ->select(['a.custom_user_id', 'sum(a.total_fee) fee'])
            ->where(['>=', 'a.create_datetime', $this->plan->start_date])
            ->andWhere(['<=', 'a.create_datetime', $this->plan->end_date])
            ->andWhere(['a.status' => static::$status])
            ->leftJoin(OrderItemAR::tableName() . ' b', 'b.order_id = a.id')
            ->leftJoin(ProductSKUAR::tableName() . ' c', 'c.id = b.product_sku_id')
            ->andWhere(['c.product_id' => $product_id])
            ->groupBy('custom_user_id')
            ->asArray()->all();
    }

    /**
     * 查询错误抽奖
     * @return array
     */
    public static function queryFix()
    {
        $items = LotteryPlanProductAR::find()
            ->alias('a')
            ->select(['a.id', 'a.product_id', 'a.brand_id', 'b.end_date'])
            ->leftJoin(LotteryPlanAR::tableName() . 'b', 'a.plan_id = b.id')
            ->where(['b.fixed' => 0])
            ->andWhere(['b.status' => LotteryPlanAR::STATUS_FINISH])
            ->asArray()->all();

        $res = [];
        $out = [];
        foreach ($items as $item) {
            if(isset($item['product_id'])){
                $condition = $item['product_id'];
            }elseif(isset($item['brand_id'])){
                $column = ProductAR::find()
                    ->select(['id'])
                    ->where(['supply_user_id' => $item['brand_id']])
                    ->column();
                $condition = $column;
            } else {
                throw new \RuntimeException('计划商品错误');
            }
            $tmp = OrderAR::find()
                ->alias('a')
                ->select(['a.custom_user_id', 'a.total_fee as fee', 'a.id'])
                ->where(['>=', 'a.pay_datetime', $item['end_date']])
                ->andWhere(['<=', 'a.create_datetime', $item['end_date']])
                ->andWhere(['a.status' => static::$status])
                ->leftJoin(OrderItemAR::tableName() . ' b', 'b.order_id = a.id')
                ->leftJoin(ProductSKUAR::tableName() . ' c', 'c.id = b.product_sku_id')
                ->andWhere(['c.product_id' => $condition])
//                ->groupBy('custom_user_id')
                ->asArray()->all();

            $tmp2 = [];
            foreach ($tmp as $value){
                if(in_array($value['id'], $out)) continue;
                $out[] = $value['id'];
                $key = $value['custom_user_id'];
                if(isset($tmp2[$key])){
                    $tmp2[$key] = [
                        'custom_user_id' => $key,
                        'fee' => $value['fee'] + $tmp2[$key]['fee']
                    ];
                } else {
                    $tmp2[$key] = [
                        'custom_user_id' => $key,
                        'fee' => $value['fee']
                    ];
                }
            }
            $res[$item['id']] = array_values($tmp2);
        }
        return array_filter($res);
    }

    /**
     * 更新修复状态
     * @return int
     */
    private function updateFix()
    {
        return LotteryPlanAR::updateAll(['fixed' => 1], [
            'status' => LotteryPlanAR::STATUS_FINISH,
            'fixed' => 0
        ]);
    }
}