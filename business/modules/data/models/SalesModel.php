<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-29
 * Time: 上午10:30
 */

namespace business\modules\data\models;

use business\models\handler\BusinessAreaHandler;
use business\models\handler\CustomUserHandler;
use business\models\handler\MembraneOrderHandler;
use business\models\handler\OrderHandler;
use business\models\parts\Area;
use business\modules\data\models\objects\BusinessArea;
use business\modules\data\models\traits\AutoSplitDateTrait;
use business\modules\data\models\traits\BusinessAreaTrait;
use business\modules\data\models\traits\UserAreaTrait;
use common\models\Model;
use common\models\parts\custom\CustomUser;

class SalesModel extends Model
{
    const SCE_SEARCH = 'search';
    const SCE_AREA_SEARCH = 'area_search';

    public $start;
    public $end;
    public $area_id = [Area::LEVEL_UNDEFINED];
    public $level = 1;
    public $user_level;
    public $by = 'day';

    use UserAreaTrait,
        BusinessAreaTrait,
        AutoSplitDateTrait;

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
                'level',
                'user_level',
                'start',
                'end'
            ],
            self::SCE_AREA_SEARCH => [
                'start',
                'end',
                'by',
                'user_level',
                'area_id'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['area_id'],
                'each',
                'rule' => [
                    'integer'
                ],
                'message' => 9002
            ],
            [
                ['user_level'],
                'each',
                'rule' => [
                    'in',
                    'range' => CustomUser::getLevels()
                ],
                'message' => 9002
            ],
            [
                ['by'],
                'in',
                'range' => ['hour', 'day', 'week', 'month'],
                'message' => 9002
            ],
            [
                ['start', 'end'],
                'date',
                'format' => 'php:Y-m-d H:i:s',
                'message' => 9002
            ],
            [
                ['level'],
                'in',
                'range' => Area::$levels,
                'message' => 9002
            ]
        ];
    }

    /**
     * 各级销售
     * @return array|bool
     */
    public function search()
    {
        try{
            $mainArea = $this->getMainArea();
            if($this->level >= $mainArea->level){
                //获取同级/下级
                $areaItems = BusinessAreaHandler::findAreaByLevel($mainArea, $this->level);
            } else {
                //获取上级
                $areaItems[] = BusinessAreaHandler::findParentLevel($mainArea, $this->level);
            }
            $items = [];
            foreach ($areaItems as $val){
                $area = BusinessAreaHandler::findAreaByLevel($val, Area::LEVEL_FIFTH);
                $uid = CustomUserHandler::findUserIdBy(array_column($area, 'id'), $this->user_level);
                $total = OrderHandler::queryTotalFeeBy($uid, $this->start, $this->end);
                $total += MembraneOrderHandler::queryTotalFeeBy($uid, $this->start, $this->end);
                $items[] = [
                    'id' => $val['id'],
                    'level' => $val['level'],
                    'name' => $val['name'],
                    'total' => $total
                ];
            }
            $total = sprintf('%.2f', array_sum(array_column($items, 'total')));
            return compact('items', 'total');
        } catch (\Exception $e){
            $this->addError('search', 13380);
            return false;
        }
    }

    /**
     * 精准查询
     * @return array|bool
     */
    public function areaSearch()
    {
        try {
            $dateItems = $this->autoSplitDate();
            $res = [];
            foreach ($this->area_id as $id){
                $modelArea = new BusinessArea(['id' => $id]);
                $area = $this->getValidAreaItem($modelArea);
                $uid = CustomUserHandler::findUserIdBy(array_column($area, 'id'), $this->user_level);
                $items = []; $total = 0;
                foreach ($dateItems as $k=>$date){
                    if(!isset($dateItems[$k+1])) break;
                    $start = $date['date'];
                    $end = $dateItems[$k+1]['date'];
                    $totalFee = OrderHandler::queryTotalFeeBy($uid, $start, $end);
                    $totalFee += MembraneOrderHandler::queryTotalFeeBy($uid, $start, $end);
                    $items[] = [
                        'date' => $date,
                        'total' => $totalFee
                    ];
                    $total += $totalFee;
                }
                $res[] = [
                    'id' => $modelArea->id,
                    'name' => $modelArea->name,
                    'level' => $modelArea->level,
                    'total' => sprintf('%.2f', $total),
                    'items' => $items
                ];
            }
            return ['items' => $res];
        } catch (\Exception $e){
            $this->addError('area_search', 13380);
            return false;
        }
    }
}