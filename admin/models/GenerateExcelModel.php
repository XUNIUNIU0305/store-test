<?php
namespace admin\models;

use Yii;
use common\models\Model;
use common\models\parts\Order;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\CustomUserAR;
use common\models\parts\district\District;
use common\components\handler\ExcelHandler;
use common\ActiveRecord\DistrictDistrictAR;

class GenerateExcelModel extends Model{

    const SCE_GET_ORDERS = 'get_orders';
    const SCE_REFUND_ORDER = 'get_refund_order';
    const SCE_JIANGSU = 'get_jiangsu_district_achievement';

    public $from;
    public $to;

    public function scenarios(){
        return [
            self::SCE_GET_ORDERS => [
                'from',
                'to',
                'status',
            ],
            self::SCE_REFUND_ORDER => [],
            self::SCE_JIANGSU => [],
        ];
    }

    public function rules(){
        return [
            [
                ['to'],
                'default',
                'value' => $this->from,
            ],
            [
                ['from', 'to'],
                'required',
                'message' => 9001,
            ],
            [
                ['from', 'to'],
                'date',
                'type' => 'datetime',
                'format' => 'Y-m-d',
                'timeZone' => 'Asia/Shanghai',
                'message' => 9002,
            ],
        ];
    }

    public function getJiangsuDistrictAchievement(){
        $districts = Yii::$app->RQ->AR(new DistrictDistrictAR)->column([
            'select' => ['id'],
            'where' => ['district_province_id' => 10],
        ]);
        $result = [];
        foreach($districts as $districtId){
            $district = new \common\models\parts\district\District([
                'districtId' => $districtId,
            ]);
            $userIds = Yii::$app->RQ->AR(new CustomUserAR)->column([
                'select' => ['id'],
                'where' => ['district_district_id' => $districtId],
            ]);
            if(empty($userIds)){
                $result[] = [
                    $district->city->name,
                    $district->name,
                    0,
                ];
            }else{
                $achievement = Yii::$app->RQ->AR(new OrderAR)->sum([
                    'where' => [
                        'custom_user_id' => $userIds,
                        'status' => [1,2,3,5],
                    ],
                    'andWhere' => ['>', 'create_datetime', '2017-07-01 00:00:00'],
                ], 'total_fee');
                if(!$achievement)$achievement = 0;
                $result[] = [
                    $district->city->name,
                    $district->name,
                    $achievement,
                ];
            }
        }
        ExcelHandler::output($result, [
            '城市',
            '地区',
            '金额',
        ], '江苏7月销售');
    }

    public function getRefundOrder(){
        $refund = Yii::$app->RQ->AR(new \common\ActiveRecord\OrderRefundAR)->all([
            'select' => ['code', 'order_item_id', 'quantity', 'create_time', 'supply_refund_money_time'],
            'where' => [
                'status' => 5,
                'type' => 2,
            ],
        ]);
        $excelRows = [];
        foreach($refund as $one){
            $item = new \common\models\parts\ItemInOrder([
                'id' => $one['order_item_id'],
            ]);
            $skuString = '';
            foreach($item->SKUAttributes as $sku){
                $skuString .= $sku['attribute'] . ':' . $sku['option'] . ' ';
            }
            $excelRows[] = [
                $one['code'],
                $item->title,
                $skuString,
                $item->price,
                $one['quantity'],
                date('Y-m-d H:i:s', $one['create_time']),
                date('Y-m-d H:i:s', $one['supply_refund_money_time']),
                $item->order->orderNo,
                '已退款',
            ];
        }
        ExcelHandler::output($excelRows, [
            '售后单号',
            '商品名',
            '属性',
            '平台单价',
            '数量',
            '申请时间',
            '退款时间',
            '关联订单',
            '状态',
        ], '已退款商品');
    }

    public function getOrders(){
        ini_set('memory_limit', '4096M');
        $dateFrom = $this->from . ' 00:00:00';
        $dateTo = $this->to . ' 23:59:59';
        $timeQueryList = [
            'create_datetime',
            'pay_datetime',
            'deliver_datetime',
            'receive_datetime',
            'cancel_datetime',
        ];
        $ordersId = Yii::$app->RQ->AR(new OrderAR)->column([
            'select' => ['id'],
            'where' => [
                'between', 'pay_datetime', $dateFrom, $dateTo,
            ],
            'andWhere' => [
                'status' => [
                    Order::STATUS_UNDELIVER,
                    Order::STATUS_DELIVERED,
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_CLOSED,
                ],
            ],
        ]);
        $excelTitle = "订单信息 （{$this->from} 至 {$this->to}）";
        $title = [
            '订单号', '品牌名', '供应商公司', '订单总金额', '订单商品总金额',
            '订单优惠券金额', '订单退款金额', '物流单号', '订单状态',
            '创建时间', '支付时间', '发货时间', '确认收货时间',
            '取消订单时间', '收货人姓名', '收货人地址', '收货人手机', '下单账号',
            '商品名称', '商品SKU', '商品数量', '商品单价', '商品总价',
        ];
        $rows = [];
        foreach($ordersId as $orderId){
            $order = new Order(['id' => $orderId]);
            foreach($order->items as $k => $item){
                switch($order->status){
                case Order::STATUS_UNPAID:
                    $status = '未支付';
                    break;

                case Order::STATUS_UNDELIVER:
                    $status = '未发货';
                    break;

                case Order::STATUS_DELIVERED:
                    $status = '已发货';
                    break;

                case Order::STATUS_CONFIRMED:
                    $status = '已收货';
                    break;

                case Order::STATUS_CANCELED:
                    $status = '已取消';
                    break;

                case Order::STATUS_CLOSED:
                    $status = '已关闭';
                    break;

                default:
                    $status = '未知';
                    break;
                }
                $skuArr = $item->skuAttributes;
                $sku = [];
                foreach($skuArr as $v){
                    $sku[] = $v['attribute'] . ': ' . $v['option'];
                }
                if(!$k){
                    $rows[] = [
                        $order->orderNo,
                        $order->supplier->brandName,
                        $order->supplier->companyName,
                        $order->totalFee,
                        $order->itemsFee,
                        $order->couponRmb,
                        $order->refundRmb,
                        $order->expressNo ? : '',
                        $status,
                        $order->createTime,
                        $order->payTime ? : '',
                        $order->deliverTime ? : '',
                        $order->receiveTime ? : '',
                        $order->cancelTime ? : '',
                        $order->consignee,
                        $order->address,
                        $order->mobile,
                        $order->customerAccount,
                        $item->title,
                        implode('| ', $sku),
                        $item->count,
                        $item->price,
                        $item->totalFee,
                    ];
                }else{
                    $rows[] = [
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $item->title,
                        implode('| ', $sku),
                        $item->count,
                        $item->price,
                        $item->totalFee,
                    ];
                }
            }
        }
        if(empty($rows)){
            return ['result' => '无订单信息'];
        }else{
            ExcelHandler::output($rows, $title, $excelTitle);
        }
    }

    public static function generateAreaInfo(){
        $userIds = Yii::$app->RQ->AR(new CustomUserAR)->column([
            'select' => ['id'],
            'where' => ['status' => 0],
        ]);
        $thirdArea = Yii::$app->RQ->AR(new \common\ActiveRecord\BusinessAreaAR)->all([
            'select' => ['id', 'name'],
            'where' => ['level' => 3],
        ]);
        $thirdAreaId = array_column($thirdArea, 'id');
        $thirdAreaName = array_column($thirdArea, 'name', 'id');
        $thirdAreaMoney = array_fill_keys($thirdAreaId, 0);
        foreach($userIds as $userId){
            $customUser = new \common\models\parts\custom\CustomUser(['id' => $userId]);
            $thirdId = $customUser->area->parent->parent->id;
            $userOrderMoney = Yii::$app->RQ->AR(new OrderAR)->sum([
                'where' => [
                    'custom_user_id' => $userId,
                    'status' => [1, 2, 3],
                ],
                'andWhere' => ['>=', 'create_datetime', '2017-06-29 00:00:00'],
            ], 'total_fee');
            $thirdAreaMoney[$thirdId] += $userOrderMoney;
        }
        $excel = [];
        foreach($thirdAreaMoney as $id => $money){
            $excel[] = [$thirdAreaName[$id], $money];
        }
        ExcelHandler::output($excel, ['运营商名称', '购买金额']);
    }
}
