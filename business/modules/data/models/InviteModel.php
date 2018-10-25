<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-30
 * Time: 下午9:21
 */

namespace business\modules\data\models;


use business\models\handler\AuthorizationHandler;
use business\models\handler\BusinessAreaHandler;
use business\models\handler\CustomUserHandler;
use business\models\handler\MembraneOrderHandler;
use business\models\handler\OrderHandler;
use business\models\parts\Area;
use business\modules\data\models\traits\AutoSplitDateTrait;
use business\modules\data\models\traits\UserAreaTrait;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use common\models\parts\Product;

class InviteModel extends Model
{
    const SCE_SEARCH = 'search';

    public $source = 1;
    public $start;
    public $end;
    public $by;

    use UserAreaTrait,
        AutoSplitDateTrait;

    public function init()
    {
        try{
            $this->start = $this->start ? date('Y-m-d H:i:s', strtotime($this->start)) : date('Y-m-d 00:00:00', strtotime('-1 week'));
            $this->end = $this->end ? date('Y-m-d H:i:s', strtotime($this->end)) : date('Y-m-d 23:59:59');
        } catch (\Exception $e){
            $this->addError('datetime', 13381);
        }
    }

    public function scenarios()
    {
        return [
            self::SCE_SEARCH => [
                'source',
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
                ['source'],
                'in',
                'range' => [1, 2],
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

    /**
     * 数据列表
     * @return array|bool
     */
    public function search()
    {
        try{
            $uid = $this->getUserId();
            $users = AuthorizationHandler::queryUserBy($uid, $this->start, $this->end, $this->source);
            $uid = array_column($users, 'custom_user_id');
            $userNum = count($uid);
            if(!$userNum){
                return [
                    'passTime' => 0,
                    'averFee' => 0,
                    'averNum' => 0,
                    'unitPrice' => 0,
                    'items' => [],
                    'hotProducts' => [],
                    'hotArea' => [],
                    'users' => []
                ];
            }
            $time = 0;
            foreach ($users as $user){
                $time += ($user['authorized_unixtime'] - $user['authorize_apply_unixtime']);
            }
            //平均审核通过时间
            $passTime = $userNum ? $this->parseTime($time / $userNum) : 0;
            //客单价
            $activeOrder = OrderHandler::queryActiveOrderBy($uid);
            $activeMembraneOrder = MembraneOrderHandler::queryActiveOrderBy($uid);
            $total = array_sum(array_merge(array_column($activeOrder, 'total_fee'), array_column($activeMembraneOrder, 'total_fee')));
            $orderNum = count($activeOrder) + count($activeMembraneOrder);
            $unitPrice = $orderNum ? round($total / $orderNum) : 0;

            //平均消费金额
            $orderUsers = CustomUserHandler::querySumOrderTotalFeeBy($uid);
            $num = count($orderUsers);
            $averFee = $num ? array_sum(array_column($users, 'total')) / $num : 0;
            //平均邀请人数
            $days = $this->parseDayDiff();
            $averNum = $days ? round($userNum / $this->parseDayDiff()) : 0;

            //销售额
            $order = OrderHandler::queryCloseOrderBy($uid);
            $membraneOrder = MembraneOrderHandler::queryCloseOrderBy($uid);
            $dateItems = $this->autoSplitDate();
            $items = [];
            foreach ($dateItems as $key=>$date){
                if(!isset($dateItems[$key+1])) break;
                $start = $date['date'];
                $end = $dateItems[$key+1]['date'];
                $total = 0; $inviteNum = 0; $passNum = 0;
                foreach ($order as $item){
                    if($start <= $item['close_datetime'] && $end >= $item['close_datetime']){
                        $total += $item['total_fee'];
                    }
                }
                foreach ($membraneOrder as $item){
                    if($start <= $item['finish_date'] && $end >= $item['finish_date']){
                        $total += $item['total_fee'];
                    }
                }
                foreach ($users as $user){
                    if($user['pay_datetime'] >= $start && $user['pay_datetime'] <= $end){
                        $inviteNum++;
                    }
                    if($user['account_valid_datetime'] >= $start && $user['account_valid_datetime'] <= $end)
                        $passNum++;
                }
                $items[] = compact('date', 'total', 'inviteNum', 'passNum');
            }
            //热门商品
            $hotProduct = OrderHandler::queryTopNormalProductBy($uid, null, null, null, 12);
            //热门地区
            $mainArea = $this->getMainArea();
            $hotArea = [];
            if($mainArea->level < Area::LEVEL_FIFTH){
                $areaItems = BusinessAreaHandler::findAreaByLevel($mainArea, $mainArea->level + 1);
                foreach ($areaItems as $item){
                    $aid = BusinessAreaHandler::findAreaByLevel($item, Area::LEVEL_FIFTH);
                    $tmpUid = CustomUserHandler::findUserIdBy(array_column($aid, 'id'));
                    $tmpUid = array_intersect($tmpUid, $uid);
                    if($product = OrderHandler::queryTopOneProduct($tmpUid, $this->start, $this->end)){
                        $tmp = new Product(['id' => $product['id']]);
                        $product = array_merge($product, [
                            'title' => $tmp->getTitle()
                        ]);
                    }
                    $hotArea[] = [
                        'area' => $item,
                        'total' => OrderHandler::queryTotalFeeBy($tmpUid) + MembraneOrderHandler::queryTotalFeeBy($tmpUid),
                        'product' => $product
                    ];
                }
                usort($hotArea, function($a, $b){
                    return $a['total'] > $b['total'] ? -1 : 1;
                });
            }
            //账号排行
            $tmp = [];
            foreach ($order as $item){
                $key = $item['custom_user_id'];
                if(isset($tmp[$key])){
                    $tmp[$key] += $item['total_fee'];
                } else {
                    $tmp[$key] = $item['total_fee'];
                }
            }
            foreach ($membraneOrder as $item){
                $key = $item['custom_user_id'];
                if(isset($tmp[$key])){
                    $tmp[$key] += $item['total_fee'];
                } else {
                    $tmp[$key] = $item['total_fee'];
                }
            }
            $users = [];
            foreach ($tmp as $key=>$val){
                $users[] = [
                    'id' => $key,
                    'total' => $val
                ];
            }
            usort($users, function($a, $b){
                return $a['total'] > $b['total'] ? -1 : 1;
            });

            return [
                'passTime' => $passTime,
                'averFee' => $averFee,
                'averNum' => $averNum,
                'unitPrice' => $unitPrice,
                'items' => $items,
                'hotProducts' => $this->queryProducts($hotProduct),
                'hotArea' => $hotArea,
                'users' => array_map(function($item){
                    $user = new CustomUser(['id' => $item['id']]);
                    $address = $user->getDefaultAddress();
                    return [
                        'id' => $user->id,
                        'account' => $user->getAccount(),
                        'mobile' => $user->getMobile(),
                        'name' => $address->getConsignee(),
                        'addr' => $address->getDetail(),
                        'area' => implode(' - ', [$address->getProvince(true), $address->getCity(true), $address->getDistrict(true)]),
                        'total' => $item['total']
                    ];
                }, $users)
            ];
        }catch (\Exception $e){
            $this->addError('', 13380);
            return false;
        }
    }

    /**
     * 解析时间
     * @param $time
     * @return string
     */
    protected function parseTime($time)
    {
        if($time < 60)
            return $time . '秒';
        $time = round($time / 60);
        if($time < 60)
            return $time . '分钟';
        $time = round($time / 60);
        return $time . '小时';
    }

    private function parseDayDiff()
    {
        $diff = (new \DateTime($this->end))->diff(new \DateTime($this->start));
        return $diff->days;
    }

    private function queryProducts($items)
    {
        $id = array_column($items, 'product_id');
        $products = OrderHandler::queryProducts($id);
        $res = [];
        foreach ($items as $key=>$item){
            $productId = $item['product_id'];
            $sku = OrderHandler::queryHotSkuBy($productId);
            $res[] = [
                'id' => $productId,
                'title' => $products[$productId]['title'] ?? '',
                'sku' => unserialize($sku['attributes']) ?? [],
                'price' => $sku['price'],
                'total' => $item['total']
            ];
        }
        return $res;
    }
}