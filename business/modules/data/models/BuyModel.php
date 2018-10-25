<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-30
 * Time: 下午7:54
 */

namespace business\modules\data\models;


use business\models\handler\CustomUserHandler;
use business\models\handler\MembraneOrderHandler;
use business\models\handler\OrderHandler;
use business\modules\data\models\objects\BusinessArea;
use business\modules\data\models\traits\AutoSplitDateTrait;
use business\modules\data\models\traits\BusinessAreaTrait;
use business\modules\data\models\traits\UserAreaTrait;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use common\models\parts\MembraneOrder;
use common\models\parts\Order;

class BuyModel extends Model
{
    const SCE_SEARCH = 'search';
    const SCE_AREA_SEARCH = 'area_search';

    public $user_level;
    public $start;
    public $end;
    public $by;
    public $query = [];

    use AutoSplitDateTrait,
        UserAreaTrait,
        BusinessAreaTrait;

    public $types = [
        1 => '下单',
        2 => '取消',
        3 => '付款'
    ];

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
            self::SCE_SEARCH => [
                'user_level',
                'start',
                'end',
                'by'
            ],
            self::SCE_AREA_SEARCH => [
                'query',
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
            ],
            [
                ['user_level'],
                'each',
                'rule' => [
                    'in',
                    'range' => CustomUser::getLevels(),
                    'message' => 9002
                ],
                'message' => 9002
            ],
            [
                ['query'],
                'validateQuery'
            ]
        ];
    }

    public function validateQuery($attribute)
    {
        $res = true;
        if(is_array($this->$attribute)){
            foreach ($this->$attribute as $query){
                if(!isset($query['area']) || !isset($query['type'])){
                    $res = false;
                    break;
                }
                if(!is_numeric($query['area']) || !in_array($query['type'], [1, 2, 3])){
                    $res = false;
                    break;
                }
            }
        } else {
            $res = false;
        }
        if(!$res){
            $this->addError($attribute, 9002);
        }
    }

    /**
     * 数据列表
     * @return array|bool
     */
    public function search()
    {
        try{
            $area = $this->getFifthAreaId();
            $uid = CustomUserHandler::findUserIdBy($area, $this->user_level);
            $order = OrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
            $membraneOrder = MembraneOrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
            $dateItems = $this->autoSplitDate();
            $res = [];
            foreach ($dateItems as $key=>$date){
                if(!isset($dateItems[$key+1])) break;
                $start = $date['date'];
                $end = $dateItems[$key+1]['date'];
                $items = $payedItems = $cancelItems = [];
                foreach ($order as $item){
                    if($item['pay_datetime'] >= $start && $item['pay_datetime'] <= $end){
                        $data = [
                            'total' => $item['total_fee'],
                            'date' => $item['pay_datetime']
                        ];
                        $items[] = $data;
                        if($item['status'] >= Order::STATUS_UNDELIVER)
                            $payedItems[] = $data;
                        if($item['status'] == Order::STATUS_CANCELED){
                            $cancelItems[] = $data;
                        }
                    }
                }
                foreach ($membraneOrder as $item){
                    if($item['pay_date'] >= $start && $item['pay_date'] <= $end){
                        $data = [
                            'total' => $item['total_fee'],
                            'date' => $item['pay_date']
                        ];
                        $items[] = $data;
                        if($item['status'] >= MembraneOrder::STATUS_PAYED)
                            $payedItems[] = $data;
                        if($item['status'] == MembraneOrder::STATUS_CANCELED)
                            $cancelItems[] = $data;
                    }
                }
                $res[] = [
                    'date' => $date,
                    'items' => $items,
                    'total' => sprintf('%.2f', array_sum(array_column($items, 'total'))),
                    'payedItems' => $payedItems,
                    'payedTotal' => sprintf('%.2f', array_sum(array_column($payedItems, 'total'))),
                    'cancelItems' => $cancelItems,
                    'cancelTotal' => sprintf('%.2f', array_sum(array_column($cancelItems, 'total'))),
                ];
            }
            return $res;
        } catch (\Exception $e){
            $this->addError('', 13380);
            return false;
        }
    }

    /**
     * 按区域查询
     * @return array|bool
     */
    public function areaSearch()
    {
        try{
            $res = [];
            $dateItems = $this->autoSplitDate();
            foreach ($this->query as $query){
                $resItem = [];
                $queryArea = new BusinessArea(['id' => $query['area']]);
                $area = $this->getValidAreaItem($queryArea);
                $uid = CustomUserHandler::findUserIdBy(array_column($area, 'id'));
                switch ($query['type']){
                    case 2:
                        //取消
                        $condition = ['status' => Order::STATUS_CANCELED];
                        $where = ['status' => MembraneOrder::STATUS_CANCELED];
                        break;
                    case 3:
                        //下单
                        $condition = ['status' => OrderHandler::$activeStatus];
                        $where = ['status' => MembraneOrder::$activeStatus];
                        break;
                    default:
                        $condition = $where = [];
                }
                $order = OrderHandler::queryOrderBy($uid, $this->start, $this->end, $condition);
                $membraneOrder = MembraneOrderHandler::queryOrderBy($uid, $this->start, $this->end, $where);
                foreach ($dateItems as $key=>$date){
                    if(!isset($dateItems[$key+1])) break;
                    $start = $date['date'];
                    $end = $dateItems[$key+1]['date'];
                    $items = [];
                    foreach ($order as $item){
                        if($item['pay_datetime'] >= $start && $item['pay_datetime'] <= $end){
                            $items[] = [
                                'total' => $item['total_fee'],
                                'date' => $item['pay_datetime']
                            ];
                        }
                    }
                    foreach ($membraneOrder as $item){
                        if($item['pay_date'] >= $start && $item['pay_date'] <= $end){
                            $items[] = [
                                'total' => $item['total_fee'],
                                'date' => $item['pay_date']
                            ];
                        }
                    }
                    $resItem[] = [
                        'date' => $date,
                        'items' => $items,
                        'total' => sprintf('%.2f', array_sum(array_column($items, 'total')))
                    ];
                }
                $res[] = [
                    'query' => $queryArea->name . '|' . $this->getTypeLabel($query['type']),
                    'list' => $resItem,
                    'total' => sprintf('%.2f', array_sum(array_column($order, 'total_fee')) + array_sum(array_column($membraneOrder, 'total_fee'))),
                ];
            }
            return ['items' => $res];
        } catch (\Exception $e){
            $this->addError('', 13380);
            return false;
        }
    }

    public function getTypeLabel($type)
    {
        return $this->types[$type] ?? '';
    }
}