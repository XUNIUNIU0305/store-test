<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-24
 * Time: 上午10:04
 */

namespace business\modules\data\models;


use business\models\handler\BusinessAreaHandler;
use business\models\handler\CustomUserHandler;
use business\models\handler\MembraneOrderHandler;
use business\models\handler\OrderHandler;
use business\models\handler\ShoppingCartHandler;
use business\models\parts\Area;
use business\models\parts\AreaLevel;
use business\modules\data\models\objects\BusinessArea;
use business\modules\data\models\traits\AutoSplitDateTrait;
use business\modules\data\models\traits\BusinessAreaTrait;
use business\modules\data\models\traits\UserAreaTrait;
use common\models\Model;
use common\models\parts\custom\CustomUser;

class HomeModel extends Model
{
    const SCE_CONVERSION_RATE = 'conversion_rate';
    const SCE_TOTAL_CONSUME = 'total_consume';
    const SCE_SHOP_CART = 'shopping_cart';
    const SCE_IS_CUSTOMIZATION = 'is_customization';
    const SCE_TIME_AMOUNT = 'time_amount';
    const SCE_STORE = 'store';
    const SCE_AREA_LEVEL = 'area_level';
    const SCE_SELF_AREA = 'self_area';
    const SCE_USER_LEVEL = 'user_level';

    public $area_id;
    public $start;
    public $end;
    public $by = 'day';

    use UserAreaTrait;
    use AutoSplitDateTrait,
        BusinessAreaTrait;

    public function init()
    {
        $this->start = $this->start ? date('Y-m-d H:i:s', strtotime($this->start)) : date('Y-m-d H:i:s', strtotime('-1 week'));
        $this->end = $this->end ? date('Y-m-d H:i:s', strtotime($this->end)) : date('Y-m-d') . '23:59:59';
    }

    public function scenarios()
    {
        return [
            self::SCE_CONVERSION_RATE => [
                'start',
                'end'
            ],
            self::SCE_TOTAL_CONSUME => [
                'start',
                'end',
                'by'
            ],
            self::SCE_SHOP_CART => [
                'start',
                'end',
                'by'
            ],
            self::SCE_IS_CUSTOMIZATION => [
                'start',
                'end',
                'by'
            ],
            self::SCE_TIME_AMOUNT => [
                'start',
                'end',
                'by'
            ],
            self::SCE_STORE => [
                'start',
                'end',
                'by'
            ],
            self::SCE_AREA_LEVEL => [],
            self::SCE_SELF_AREA => [
                'area_id'
            ],
            self::SCE_USER_LEVEL => []
        ];
    }

    public function rules()
    {
        return [
            [
                ['start', 'end'],
                'date',
                'format' => 'php:Y-m-d H:i:s',
                'message' => 9002
            ],
            [
                ['by'],
                'in',
                'range' => ['hour', 'day', 'week', 'month']
            ],
            [
                ['area_id'],
                'integer',
                'message' => 9002
            ]
        ];
    }

    /**
     * 区域级别
     * @return array
     */
    public function areaLevel()
    {
        return AreaLevel::getLevelList();
    }

    /**
     * 查询本身区域
     * @return array|bool
     */
    public function selfArea()
    {
        try {
            $area = new BusinessArea(['id' => $this->area_id]);
            $mainArea = $this->getMainArea();
            $items = [];
            if($area->level == $mainArea->level){
                $items = BusinessAreaHandler::findAreaByLevel($mainArea, $area->level + 1);
            } elseif($area->level > $mainArea->level){
                $children = BusinessAreaHandler::findAreaByLevel($mainArea, $area->level);
                foreach ($children as $item){
                    if($item['id'] == $area->id){
                        $items = BusinessAreaHandler::findAreaByLevel($area, $area->level + 1);
                        break;
                    }
                }
            } else {
                $items = BusinessAreaHandler::findParentLevel($mainArea, $area->level + 1);
            }
            $hasChild = false;
            if($item = current($items)){
                if($item['level'] < Area::LEVEL_FIFTH)
                    $hasChild = true;
            }
            return compact('items', 'hasChild');
        } catch (\Exception $e){
            $this->addError('', 13380);
            return false;
        }
    }

    /**
     * 用户级别
     * @return array
     */
    public function userLevel()
    {
        return CustomUser::$levelLabels;
    }

    /**
     * 转化率
     * @return array|bool
     */
    public function conversionRate()
    {
        try {
            $uid = $this->getUserId();
            //总账号数
            if (($totalAccountNum = count($uid)) === 0) {
                $this->addError('conversionRate', 13380);
                return false;
            }
            $order = $this->getOrder($uid);
            $membraneOrder = $this->getMembraneOrder($uid);
            //总消费人数
            $totalConsumeNum = count(array_unique(array_merge(array_column($order, 'custom_user_id'), array_column($membraneOrder, 'custom_user_id'))));

            $allOrder = $this->getAllOrder($uid);
            $allMembraneOrder = $this->getAllMembraneOrder($uid);

            //创建过订单的用户数
            $createOrderNum = count(array_unique(array_merge(array_column($allOrder, 'custom_user_id'), array_column($allMembraneOrder, 'custom_user_id'))));

            $closeOrder = $this->getCloseOrder($uid);
            $closeMembraneOrder = $this->getCloseMembraneOrder($uid);
            //有关闭订单的账户数
            $closeOrderNum = count(array_unique(array_merge(array_column($closeOrder, 'custom_user_id'), array_column($closeMembraneOrder, 'custom_user_id'))));

            $userArr = array_merge(array_column($order, 'custom_user_id'), array_column($membraneOrder, 'custom_user_id'));
            //二次消费人数
            $secondConsumeNum = $threeConsumeNum = 0;
            $res = array_count_values($userArr);
            foreach ($res as $key => $val) {
                if ($val >= 2)
                    $secondConsumeNum++;
                if ($val >= 3)
                    $threeConsumeNum++;
            }

            return [
                'payNum' => $totalConsumeNum,
                'totalConversionRate' => round($totalConsumeNum / $totalAccountNum * 100, 2),      //总转化率
                'createNum' => $createOrderNum,
                'createRate' => round($createOrderNum / $totalAccountNum * 100, 2),       //下单率
                'finishNum' => $closeOrderNum,
                'finishRate' => round($closeOrderNum / $totalAccountNum * 100, 2),          //完成率
                'secondConsumeNum' => $secondConsumeNum,
                'secondConsumeRate' => round($secondConsumeNum / $totalAccountNum * 100, 2), //二次消费率
                'threeConsumeNum' => $threeConsumeNum,
                'threeConsumeRate' => round($threeConsumeNum / $totalAccountNum * 100, 2)       //三次消费
            ];
        } catch (\Exception $e) {
            $this->addError('conversion', 13380);
            return false;
        }
    }

    /**
     * @param $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOrder($uid)
    {
        return OrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
    }

    public function getAllOrder($uid)
    {
        return OrderHandler::queryOrderBy($uid, $this->start, $this->end);
    }

    private function getCloseOrder($uid)
    {
        return OrderHandler::queryCloseOrderBy($uid, $this->start, $this->end);
    }

    /**
     * @param $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMembraneOrder($uid)
    {
        return MembraneOrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
    }

    public function getAllMembraneOrder($uid)
    {
        return MembraneOrderHandler::queryOrderBy($uid, $this->start, $this->end);
    }

    private function getCloseMembraneOrder($uid)
    {
        return MembraneOrderHandler::queryCloseOrderBy($uid, $this->start, $this->end);
    }

    /**
     * 总消费额
     * @return array|bool
     */
    public function totalConsume()
    {
        try {
            $uid = $this->getUserId();
            $dateItems = $this->autoSplitDate();
            $items = [];
            foreach ($dateItems as $key => $date) {
                $start = $date['date'];
                if(!isset($dateItems[$key+1])) break;
                $end = $dateItems[$key + 1]['date'];
                $total = OrderHandler::queryTotalFeeBy($uid, $start, $end) + MembraneOrderHandler::queryTotalFeeBy($uid, $start, $end);
                $items[] = compact('date', 'total');
            }
            $items = [
                'items' => $items,
                'total' => sprintf('%.2f', array_sum(array_column($items, 'total'))) ?? 0
            ];
            return $items;
        } catch (\Exception $e) {
            $this->addError('totalConsume', 13380);
            return false;
        }
    }

    /**
     * 购物车内金额
     * @return bool|array
     */
    public function shoppingCart()
    {
        try{
            $uid = $this->getUserId();
            $dateItems = $this->autoSplitDate();
            $items = [];
            foreach ($dateItems as $key=>$date){
                $start = $date['date'];
                if(!isset($dateItems[$key+1])) break;
                $end = $dateItems[$key + 1]['date'];
                $total = ShoppingCartHandler::queryTotalFeeBy($uid, $start, $end);
                $items[] = compact('date', 'total');
            }
            return [
                'items' => $items,
                'total' => sprintf('%.2f', array_sum(array_column($items, 'total')))
            ];
        }catch (\Exception $e){
            $this->addError('shopping cart', 13380);
            return false;
        }
    }

    /**
     * 定制与非定制商品
     * @return array|bool
     */
    public function isCustomization()
    {
        try{
            $uid = $this->getUserId();
            $dateItems = $this->autoSplitDate();
            $items = [];
            foreach($dateItems as $key=>$date){
                $start = $date['date'];
                if(!isset($dateItems[$key+1])) break;
                $end = $dateItems[$key + 1]['date'];
                $normal = OrderHandler::queryNormalTotalFeeBy($uid, $start, $end);
                $customization = OrderHandler::queryCustomizationTotalFeeBy($uid, $start, $end);
                $customization += MembraneOrderHandler::queryTotalFeeBy($uid, $start, $end);
                $items[] = compact('date', 'normal', 'customization');
            }
            return [
                'items' => $items,
                'normal_total' => sprintf('%.2f', array_sum(array_column($items, 'normal'))),
                'customization_total' => sprintf('%.2f', array_sum(array_column($items,'customization')))
            ];
        }catch (\Exception $e){
            $this->addError('is-custom', 13380);
            return false;
        }
    }

    /**
     * 下单金额与时间分布
     * @return array|bool
     */
    public function timeAmount()
    {
        try {
            $uid = $this->getUserId();
            $dateItems = $this->autoSplitDate();
            $items = [];
            foreach ($dateItems as $key=>$date){
                $start = $date['date'];
                if(!isset($dateItems[$key+1])) break;
                $end = $dateItems[$key + 1]['date'];
                $order = OrderHandler::queryTimeTotalFeeBy($uid, $start, $end);
                $order += MembraneOrderHandler::queryTimeTotalBy($uid, $start, $end);
                $items[] = compact('date', 'order');
            }
            return compact('items');
        } catch (\Exception $e){
            $this->addError('time-amount', 13380);
            return false;
        }
    }

    /**
     * 门店邀请数据
     * @return array|bool
     */
    public function store()
    {
        try{
            $uid = $this->getInviteUserId();
            $dateItems = $this->autoSplitDate();
            $items = [];
            foreach ($dateItems as $key=>$date){
                $start = $date['date'];
                if(!isset($dateItems[$key+1])) break;
                $end = $dateItems[$key + 1]['date'];
                $total = OrderHandler::queryTotalFeeBy($uid, $start, $end) + MembraneOrderHandler::queryTotalFeeBy($uid, $start, $end);
                $inviteNum = CustomUserHandler::countInviteNumBy($this->getFifthAreaId(), $start, $end);
                $openedNum = CustomUserHandler::countOpenedUserNumBy($this->getFifthAreaId(), $start, $end);
                $items[] = compact('date','total', 'inviteNum', 'openedNum');
            }
            return compact('items');
        } catch (\Exception $e){
            $this->addError('time-amount', 13380);
            return false;
        }
    }
}