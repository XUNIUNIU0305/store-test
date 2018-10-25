<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-28
 * Time: 上午11:24
 */

namespace business\modules\data\models;

use business\models\handler\CustomUserHandler;
use business\models\handler\MembraneOrderHandler;
use business\models\handler\OrderHandler;
use business\modules\data\models\objects\BusinessArea;
use business\modules\data\models\traits\BusinessAreaTrait;
use business\modules\data\models\traits\UserAreaTrait;
use business\modules\data\models\traits\AutoSplitDateTrait;
use common\models\Model;
use common\models\parts\custom\CustomUser;

class ConversionModel extends Model
{
    const SCE_GET_LIST = 'get_list';

    public $area_id = 0;
    public $level;
    public $start;
    public $end;
    public $by = 'day';

    use UserAreaTrait,
        AutoSplitDateTrait,
        BusinessAreaTrait;

    public function init()
    {
        try{
            $this->start = $this->start ? date('Y-m-d H:i:s', strtotime($this->start)) : date('Y-m-d 00:00:00', strtotime('-1 day'));
            $this->end = $this->end ? date('Y-m-d H:i:s', strtotime($this->end)) : date('Y-m-d 23:59:59', strtotime('-1 day'));
        } catch (\Exception $e){
            $this->addError('datetime', 13381);
        }
    }

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [
                'area_id',
                'level',
                'start',
                'end',
                'by'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['area_id'],
                'integer',
                'message' => 9002
            ],
            [
                ['level'],
                'in',
                'range' => [CustomUser::LEVEL_PARTNER, CustomUser::LEVEL_IN_SYSTEM, CustomUser::LEVEL_COMPANY],
                'message' => 9002
            ],
            [
                ['start', 'end'],
                'date',
                'format' => 'php:Y-m-d H:i:s',
                'message' => 9002
            ],
            [
                ['by'],
                'in',
                'range' => ['hour', 'day', 'week', 'month'],
                'message' => 9002
            ]
        ];
    }

    private $dateItems, $order, $membraneOrder;
    /**
     * 获取数据列表
     * @return array|bool
     */
    public function getList()
    {
        try{
            $this->dateItems = $this->autoSplitDate();
            $areaItems = $this->getValidAreaItem(new BusinessArea(['id' => $this->area_id]));
            $uid = CustomUserHandler::findUserIdBy(array_column($areaItems, 'id'));
            if(($userNum = count($uid)) <= 0){
                $this->addError('list', 13382);
                return false;
            }
            //之前有下过单的用户
            $order = OrderHandler::queryOrderBy($uid, null, $this->start);
            $membraneOrder = MembraneOrderHandler::queryOrderBy($uid, null, $this->start);
            $orderUser = array_merge(array_column($order, 'custom_user_id'), array_column($membraneOrder, 'custom_user_id'));
            $users = array_values(array_unique($orderUser));
            //第一次下单用户
            $whereUid = array_values(array_diff($uid, $users));
            $this->order = OrderHandler::queryOrderBy($whereUid, $this->start, $this->end);
            $this->membraneOrder = MembraneOrderHandler::queryOrderBy($whereUid, $this->start, $this->end);
            $res['first'] = $this->parseOrderBy('create_datetime', 'created_date', $userNum);

            //支付订单
            $this->order = OrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
            $this->membraneOrder = MembraneOrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
            $res['payed'] = $this->parseOrderBy('pay_datetime', 'pay_date', $userNum);

            //多次消费
            $second = $three = [];
            $users = array_count_values(array_merge(array_column($this->order, 'custom_user_id'), array_column($this->membraneOrder, 'custom_user_id')));
            $keys = array_keys($users);
            $orderUser = array_count_values($orderUser);
            $m = $this->parseConsume($keys, $orderUser, $users);
            $second['num'] = $m['second'];
            $second['rate'] = sprintf('%.2f', $m['second'] / $userNum * 100);
            $three['num'] = $m['three'];
            $three['rate'] = sprintf('%.2f', $m['three'] / $userNum * 100);
            foreach ($this->dateItems as $key=>$date){
                if(!isset($this->dateItems[$key+1])) break;
                $start = $date['date'];
                $end = $this->dateItems[$key+1]['date'];
                foreach ($this->order as $item){
                    if($item['pay_datetime'] >= $start && $item['pay_datetime'] <= $end){
                        $users[] = $item['custom_user_id'];
                    }
                }
                foreach ($this->membraneOrder as $item){
                    if($item['pay_date'] >= $start && $item['pay_date'] <= $end){
                        $users[] = $item['custom_user_id'];
                    }
                }
                $users = array_count_values($users);
                $keys = array_keys($users);
                $m = $this->parseConsume($keys, $orderUser, $users);
                $second['items'][] = [
                    'date' => $date,
                    'total' => $m['second'],
                    'rate' => sprintf('%.2f', $m['second'] / $userNum * 100)
                ];
                $three['items'][] = [
                    'date' => $date,
                    'total' => $m['three'],
                    'rate' => sprintf('%.2f', $m['three'] / $userNum * 100)
                ];
            }

            $res['second'] = $second;
            $res['three'] = $three;

            //完成订单
            $this->order = OrderHandler::queryCloseOrderBy($uid, $this->start, $this->end);
            $this->membraneOrder = MembraneOrderHandler::queryCloseOrderBy($uid, $this->start, $this->end);
            $res['finish'] = $this->parseOrderBy('close_datetime', 'finish_date', $userNum);

            return $res;
        } catch (\Exception $e){
            $this->addError('getList', 13380);
            return false;
        }
    }

    /**
     * 查询二次三次消费
     * @param $keys
     * @param $orderUser
     * @param $users
     * @return array
     */
    private function parseConsume($keys, $orderUser, $users)
    {
        $allKeys = array_merge(array_keys($orderUser), $keys);
        $res = $second = $three = [];
        foreach ($allKeys as $key){
            $num = 0;
            isset($orderUser[$key]) && $num+=$orderUser[$key];
            isset($users[$key]) && $num+=$users[$key];
            $res[$key] = $num;
        }
        foreach ($res as $uid=>$num){
            if($num>1)
                $second[] = $uid;
            if($num>2)
                $three[] = $uid;
        }
        return [
            'second' => count(array_intersect($keys, $second)),
            'three' => count(array_intersect($keys, $three))
        ];
    }

    /**
     * @param $attribute
     * @param $membraneAttribute
     * @param $userNum
     * @return array
     */
    private function parseOrderBy($attribute, $membraneAttribute, $userNum)
    {
        $res = [];
        foreach ($this->dateItems as $key=>$date){
            if(!isset($this->dateItems[$key+1])) break;
            $start = $date['date'];
            $end = $this->dateItems[$key+1]['date'];
            $users = [];
            foreach ($this->order as $item){
                if($item[$attribute] >= $start && $item[$attribute] <= $end){
                    $users[] = $item['custom_user_id'];
                }
            }
            foreach ($this->membraneOrder as $item){
                if($item->$membraneAttribute >= $start && $item->$membraneAttribute <= $end){
                    $users[] = $item['custom_user_id'];
                }
            }
            $total = count(array_unique($users));
            $res[] = [
                'date' => $date,
                'total' => $total,
                'rage' => sprintf('%.2f', $total / $userNum * 100)
            ];
        }
        $num = count(array_unique(array_merge(array_column($this->order, 'custom_user_id'), array_column($this->membraneOrder, 'custom_user_id'))));
        return [
            'num' => $num,
            'rate' => sprintf('%.2f', $num / $userNum * 100),
            'items' => $res
        ];
    }
}