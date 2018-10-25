<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/12
 * Time: 14:12
 */

namespace supply\models;

use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderCustomizationAR;
use common\components\handler\Handler;
use common\components\handler\OSSImageHandler;
use common\models\Model;
use common\models\parts\MembraneOrder;
use common\models\parts\OrderCustomization;
use common\models\parts\trade\PaymentMethodList;
use common\validators\order\NoValidator;
use custom\components\handler\OrderHandler;
use yii\data\ActiveDataProvider;
use common\models\parts\Order;

class CustomModel extends Model
{
    const SCE_GET_LIST = 'get_list';
    const SCE_GET_VIEW = 'get_view';
    const SCE_NOTE = 'send_note';
    const SCE_HOLD_ORDER = 'hold_order';
    const SCE_REJECT_ORDER = 'reject_order';
    const SCE_SHIP_ORDER = 'ship_order';
    const SCE_EXPORT_LIST = 'export_order';
    const SCE_PRINT = 'print_order';

    public $pay_date_start;         //付款时间
    public $pay_date_end;           //付款时间
    public $update_date_start;      //修改时间
    public $update_date_end;        //修改时间
    public $upload_date_start;      //上传时间
    public $upload_date_end;        //上传时间
    public $reject_date_start;      //拒绝时间
    public $reject_date_end;        //拒绝时间
    public $send_date_start;        //发货时间
    public $send_date_end;          //发货时间
    public $close_date_start;
    public $close_date_end;
    public $buy_person;             //收货人
    public $address;                //地址
    public $buy_account;            //购买帐号
    public $express_number;         //物流单号
    public $express_corporation;    //物流公司
    public $page = 1;               //当前页
    public $page_size = 10;         //分页数
    public $status;
    public $close;

    public $order_number;           //订单号

    public $text;                   //留言内容

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [
                'pay_date_start',
                'pay_date_end',
                'update_date_start',
                'update_date_end',
                'upload_date_start',
                'upload_date_end',
                'reject_date_start',
                'reject_date_end',
                'send_date_start',
                'send_date_end',
                'close_date_start',
                'close_date_end',
                'order_number',
                'address',
                'buy_person',
                'buy_account',
                'express_number',
                'express_corporation',
                'status',
                'page',
                'page_size',
                'close'
            ],
            self::SCE_EXPORT_LIST => [
                'pay_date_start',
                'pay_date_end',
                'update_date_start',
                'update_date_end',
                'upload_date_start',
                'upload_date_end',
                'reject_date_start',
                'reject_date_end',
                'send_date_start',
                'send_date_end',
                'close_date_start',
                'close_date_end',
                'order_number',
                'address',
                'buy_person',
                'buy_account',
                'express_number',
                'express_corporation',
                'status',
                'page',
                'page_size',
                'close'
            ],
            self::SCE_NOTE => [
                'order_number',
                'text'
            ],
            self::SCE_GET_VIEW => [
                'order_number'
            ],
            self::SCE_HOLD_ORDER => [
                'order_number'
            ],
            self::SCE_REJECT_ORDER => [
                'order_number',
                'text'
            ],
            self::SCE_SHIP_ORDER => [
                'express_corporation',
                'express_number',
                'order_number'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'pay_date_start',
                    'pay_date_end',
                    'update_date_start',
                    'update_date_end',
                    'upload_date_start',
                    'upload_date_end',
                    'reject_date_start',
                    'reject_date_end',
                    'send_date_start',
                    'send_date_end',
                    'close_date_start',
                    'close_date_end'
                ],
                'date',
                'format' => 'php:Y-m-d',
                'message' => 9002
            ],
            [
                ['order_number', 'express_corporation', 'page_size', 'page'],
                'integer',
                'message' => 9002
            ],
            [
                ['text', 'address', 'buy_account', 'buy_person', 'express_number'],
                'string',
                'message' => 9002
            ],
            [
                ['status'],
                'in',
                'range' => array_keys(OrderCustomization::$status),
                'message' => 9002
            ],
            [
                ['express_number', 'express_corporation'],
                'required',
                'message' => 9001,
                'on' => [self::SCE_SHIP_ORDER]
            ],
            [
                ['text'],
                'required',
                'message' => 9001,
                'on' => [self::SCE_REJECT_ORDER, self::SCE_NOTE]
            ],
            [
                ['order_number'],
                'required',
                'message' => 9001,
                'on' => [self::SCE_NOTE, self::SCE_GET_VIEW, self::SCE_HOLD_ORDER, self::SCE_REJECT_ORDER, self::SCE_SHIP_ORDER]
            ],
            [
                ['order_number'],
                NoValidator::class,
                'supplierId' => \Yii::$app->user->id,
                'on' => [self::SCE_NOTE, self::SCE_GET_VIEW, self::SCE_HOLD_ORDER, self::SCE_REJECT_ORDER, self::SCE_SHIP_ORDER],
                'message' => 10070
            ],
            [
                ['close'],
                'in',
                'range' => [0, 1],
                'message' => 9002
            ]
        ];
    }

    /**
     * 查询分页数据
     * @return array
     */
    public function getList()
    {
        $query = OrderAR::find()->alias('a')
            ->select(['a.id'])
            ->leftJoin(OrderCustomizationAR::tableName() . ' b', 'b.order_number = a.order_number')
            ->leftJoin(CustomUserAR::tableName() . ' c', 'c.id = a.custom_user_id')
            ->where([
                'is_customization' => Order::CUSTOM_STATUS_IS,
                'b.status' => $this->status ?? OrderCustomization::STATUS_DEFAULT,
                'supply_user_id' => \Yii::$app->getUser()->getId()
            ])->asArray();

        if($this->status == OrderCustomization::STATUS_SEND){
            if($this->close){
                $query->andWhere(['a.status' => Order::STATUS_CLOSED]);
            } else {
                $query->andWhere(['<', 'a.status', Order::STATUS_CLOSED]);
            }
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->page_size,
                'page' => $this->page - 1
            ]
        ]);

        $query->andFilterWhere([
            'express_corporation_id' => $this->express_corporation
        ]);

        $query->andFilterWhere(['>', 'a.pay_datetime', $this->pay_date_start])
            ->andFilterWhere(['>', 'b.upload_date', $this->upload_date_start])
            ->andFilterWhere(['>', 'b.update_date', $this->update_date_start])
            ->andFilterWhere(['>', 'b.reject_date', $this->reject_date_start])
            ->andFilterWhere(['>', 'a.deliver_datetime', $this->send_date_start])
            ->andFilterWhere(['>', 'a.close_datetime', $this->close_date_start])
            ->andFilterWhere(['like', 'a.receive_consignee', $this->buy_person])
            ->andFilterWhere(['like', 'a.order_number', $this->order_number])
            ->andFilterWhere(['like', 'a.receive_address', $this->address])
            ->andFilterWhere(['like', 'a.express_number', $this->express_number])
            ->andFilterWhere(['like', 'c.account', $this->buy_account]);

        if($this->pay_date_end)
            $query->andWhere(['<', 'a.pay_datetime', $this->pay_date_end . ' 24:00:00']);
        if($this->upload_date_end)
            $query->andWhere(['<', 'b.upload_date', $this->upload_date_end . ' 24:00:00']);
        if($this->update_date_end)
            $query->andWhere(['<', 'b.update_date', $this->update_date_end . ' 24:00:00']);
        if($this->reject_date_end)
            $query->andWhere(['<', 'b.reject_date', $this->reject_date_end . ' 24:00:00']);
        if($this->send_date_end)
            $query->andWhere(['<', 'a.deliver_datetime', $this->send_date_end . ' 24:00:00']);
        if($this->close_date_end)
            $query->andWhere(['<', 'a.close_datetime', $this->close_date_end . ' 24:00:00']);

        return [
            'items' => array_map(function ($id) {
                return Handler::getMultiAttributes(new Order(['id' => $id]), [
                    'order_no' => 'orderNo',
                    'customer_account' => 'customerAccount',
                    'total_fee' => 'totalFee',
                    'items_fee' => 'itemsFee',
                    'coupon_rmb' => 'couponRmb',
                    'refund_rmb' => 'refundRmb',
                    'status',
                    'express_corporation' => 'expressCorpName',
                    'express_number' => 'expressNo',
                    'create_time' => 'createTime',
                    'pay_time' => 'payTime',
                    'deliver_time' => 'deliverTime',
                    'receive_time' => 'receiveTime',
                    'close_time' => 'closeTime',
                    'cancel_time' => 'cancelTime',
                    'consignee',
                    'address',
                    'mobile',
                    'postal_code' => 'postalCode',
                    'product' => 'items',
                    'customization',
                    'custom_user' => 'customUser',
                    'pay_method' => 'payMethod',
                    '_func' => [
                        'payMethod' => function($id){
                            return PaymentMethodList::queryMethodName($id);
                        },
                        'customization' => function ($item) {
                            return Handler::getMultiAttributes($item, [
                                'notes',
                                'custom_status' => 'status',
                                'uploadDate',
                                'acceptDate',
                                'shipDate',
                                '_func' => [
                                    'notes' => function ($notes) {
                                        return array_map(function ($note) {
                                            return Handler::getMultiAttributes($note, [
                                                'text',
                                                'type'
                                            ]);
                                        }, $notes);
                                    }
                                ]
                            ]);
                        },
                        'customUser' => function($account){
                            return [
                                'email' => $account->email
                            ];
                        },
                        'items' => function ($items) {
                            return Handler::getMultiAttributes(current($items), [
                                'title',
                                'attributes' => 'SKUAttributes',
                                'price',
                                'count',
                                'total_fee' => 'totalFee',
                                'custom_id' => 'customId',
                                'bar_code' => 'barCode',
                                'image',
                                'comments',
                                '_func' => [
                                    'image' => function ($image) {
                                        $ossImageHandlerObj = OSSImageHandler::load($image);
                                        $ossSize = $ossImageHandlerObj->resize(90,90);
                                        return $ossSize->apply() ? $ossSize->image->path : '';
                                    },

                                ],
                            ]);
                        }
                    ]
                ]);
            }, $provider->models),
            'page' => $this->page,
            'page_size' => $this->page_size,
            'total_count' => $provider->totalCount
        ];
    }

    public function exportOrder()
    {
        try {
            $this->page_size = 9999;
            $items = $this->getList()['items'] ?? [];

            $excel = new \PHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle($this->getTitle());
            $sheet
                ->setCellValue('A1', '订单编号')
                ->setCellValue('B1', '定制订单状态')
                ->setCellValue('C1', '主订单状态')
                ->setCellValue('D1', '实付金额')
                ->setCellValue('E1', '商品金额')
                ->setCellValue('F1', '优惠金额')
                ->setCellValue('G1', '付款时间')
                ->setCellValue('H1', '订单生成时间')
                ->setCellValue('I1', '买家名称')
                ->setCellValue('J1', '买家电话')
                ->setCellValue('K1', '收货地址')
                ->setCellValue('L1', '物流公司')
                ->setCellValue('M1', '运单号')
                ->setCellValue('N1', '买家邮箱')
                ->setCellValue('O1', '商品数量')
                ->setCellValue('P1', '用户定制特殊备注')
                ->setCellValue('Q1', '关闭时间  ')
                ->setCellValue('R1', '取消订单时间')
                ->setCellValue('S1', '确认收货时间')
                ->setCellValue('T1', '定制信息上传时间')
                ->setCellValue('U1', '接单时间')
                ->setCellValue('V1', '发货时间')
                ->setCellValue('W1', '商品属性')
                ->setCellValue('X1', '商品名称')
                ->setCellValue('Y1', '订单备注')
                ->setCellValue('Z1', '支付方式')
                ->setCellValue('AA1', '退款金额')
            ;
            $sheet->getStyle('A1:Z1')->getFont()->setName('微软雅黑')->setSize(12)->setBold(true);
            $l = 2;

            foreach ($items as $item){
                $product = $item['product'];
                $attributes = '';
                foreach ($product['attributes'] as $attribute){
                    foreach ($attribute as $key=>$val){
                        $attributes .= $val;
                    }
                    $attributes .= ':';
                }
                $sheet
                    ->setCellValueExplicit('A' . $l, $item['order_no'], \PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue('B' . $l, MembraneOrder::$status[$item['customization']['custom_status']])
                    ->setCellValue('C' . $l, $this->getOrderStatus($item['status']))
                    ->setCellValue('D' . $l, $item['total_fee'])
                    ->setCellValue('E' . $l, $item['items_fee'])
                    ->setCellValue('F' . $l, $item['coupon_rmb'])
                    ->setCellValue('G' . $l, $item['pay_time'])
                    ->setCellValue('H' . $l, $item['create_time'])
                    ->setCellValue('I' . $l, $item['consignee'])
                    ->setCellValueExplicit('J' . $l, $item['mobile'], 's')
                    ->setCellValue('K' . $l, $item['address'])
                    ->setCellValue('L' . $l, $item['express_corporation'] ? $item['express_corporation'] : '')
                    ->setCellValueExplicit('M' . $l, $item['express_number']?$item['express_number']:'', 's')
                    ->setCellValue('N' . $l, $item['custom_user']['email'])
                    ->setCellValue('O' . $l, '1')
                    ->setCellValue('P' . $l, $this->getCustomNote($item['customization']['notes']))
                    ->setCellValue('Q' . $l, $item['close_time']?$item['close_time']:'')
                    ->setCellValue('R' . $l, $item['cancel_time']?$item['cancel_time']:'')
                    ->setCellValue('S' . $l, $item['receive_time']?$item['receive_time']:'')
                    ->setCellValue('T' . $l, $item['customization']['uploadDate'])
                    ->setCellValue('U' . $l, $item['customization']['acceptDate'])
                    ->setCellValue('V' . $l, $item['customization']['shipDate'])
                    ->setCellValue('W' . $l, $attributes)
                    ->setCellValue('X' . $l, $product['title'])
                    ->setCellValue('Y' . $l, $product['comments'])
                    ->setCellValue('Z' . $l, $item['pay_method'])
                    ->setCellValue('AA' . $l, $item['refund_rmb'])
                ;
                $l++;
            }
            //按照指定格式生成excel文件
            $excelWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            // Redirect output to a client’s web browser (Excel2007)
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-excel");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            header('Content-Disposition:attachment;filename="订单数据.xlsx"');
            header("Content-Transfer-Encoding:binary");
            $excelWriter->save('php://output');
            exit;
//            return true;
        } catch (\Exception $e){
            $this->addError('', 1190);
            return false;
        }
    }

    private function getTitle()
    {
        if($this->status == OrderCustomization::STATUS_SEND){
            if($this->close){
                return '已完成订单';
            } else {
                return '已发货订单';
            }
        }
        return OrderCustomization::$status[$this->status];
    }

    private function getOrderStatus($status)
    {
        return ['未支付','未发货','已发货','已确认收货','已取消','已关闭'][$status];
    }

    private function getCustomNote($notes)
    {
        foreach ($notes as $note) {
            if ($note['type'] == OrderCustomization::NOTE_TYPE_DEFAULT) {
                return current($note);
            }
        }
        return '';
    }

    /**
     * 订单详情
     * @return array|bool
     */
    public function getView()
    {
        try {
            $custom = new OrderCustomization(['order_number' => $this->order_number]);
            $pics = $custom->pics;
            $noteItems = $custom->notes;
            $supplyNotes = [];
            $notes = [];
            foreach ($noteItems as $note) {
                if ($note['type'] == OrderCustomization::NOTE_TYPE_DEFAULT)
                    $notes[] = $note;
                elseif ($note['type'] == OrderCustomization::NOTE_TYPE_SUPPLY)
                    $supplyNotes[] = $note;
            }

            $order = Handler::getMultiAttributes($custom->order, [
                'order_no' => 'orderNo',
                'customer_account' => 'customerAccount',
                'total_fee' => 'totalFee',
                'items_fee' => 'itemsFee',
                'refund_rmb' => 'refundRmb',
                'coupon_rmb' => 'couponRmb',
                'coupon_info' => 'ticket',
                'status',
                'express_corporation' => 'expressCorpName',
                'express_number' => 'expressNo',
                'create_time' => 'createTime',
                'pay_time' => 'payTime',
                'deliver_time' => 'deliverTime',
                'receive_time' => 'receiveTime',
                'close_time' => 'closeTime',
                'cancel_time' => 'cancelTime',
                'consignee',
                'address',
                'mobile',
                'postal_code' => 'postalCode',
                'items',
                'pay_method' => 'payMethod',
                '_func' => [
                    'payMethod' => function($id){
                        return PaymentMethodList::queryMethodName($id);
                    },
                    'ticket' => function($ticket){
                        if($ticket){
                            $coupon = $ticket->coupon;
                            return [
                                'name' => $coupon->name,
                                'supplier' => $coupon->supplier ? $coupon->supplier->brandName : '',
                                'consumption_limit' => $coupon->consumptionLimit,
                                'discount' => $coupon->price,
                            ];
                        }else{
                            return [
                                'name' => '',
                                'supplier' => '',
                                'consumption_limit' => '',
                                'discount' => '',
                            ];
                        }
                    },
                    'items' => function($items){
                        return array_map(function($item){
                            return Handler::getMultiAttributes($item, [
                                'title',
                                'attributes' => 'SKUAttributes',
                                'price',
                                'count',
                                'total_fee' => 'totalFee',
                                'custom_id' => 'customId',
                                'bar_code' => 'barCode',
                                'image',
                                'comments',
                                '_func' => [
                                    'image' => function ($image) {
                                        return $image->path;
                                    },

                                ],
                            ]);
                        }, $items);
                    }
                ]
            ]);
            $order['status'] = $custom->getStatus();
            $custom = Handler::getMultiAttributes($custom, [
                'id',
                'carBrandName',
                'carBrandImage',
                'carTypeName',
                'upload_date' => 'uploadDate',
                'update_date' => 'updateDate',
                'reject_date' => 'rejectDate',
                'status'
            ]);
            $upload_url = \Yii::$app->params['OSS_PostHost'];
            return compact('order', 'custom', 'pics', 'notes', 'supplyNotes', 'upload_url');
        } catch (\Exception $e) {
            $this->addError('', 1091);
            return false;
        }
    }

    /**
     * 发送留言
     * @return array|bool
     */
    public function sendNote()
    {
        try {
            $model = new OrderCustomization(['order_number' => $this->order_number]);
            $model->sendNote([
                'text' => $this->text,
                'type' => OrderCustomization::NOTE_TYPE_SUPPLY,
                'user_id' => \Yii::$app->user->id
            ]);
            return [];
        } catch (\Exception $e) {
            $this->addError('notes', 1173);
            return false;
        }
    }

    /**
     * 接单
     * @return array|bool
     */
    public function holdOrder()
    {
        try {
            $model = new OrderCustomization(['order_number' => $this->order_number]);
            $model->holdOrder();
            return [];
        } catch (\Exception $e) {
            $this->addError('hold_order', 1174);
            return false;
        }
    }

    /**
     * 拒单
     * @return array|bool
     */
    public function rejectOrder()
    {
        try {
            $order = new Order(['orderNumber' => $this->order_number]);
            if($order->getSupplierId() != \Yii::$app->user->id){
                $this->addError('reject_order', 1176);
                return false;
            }
            if(!$customization = $order->getCustomization()){
                $this->addError('reject_order', 1177);
                return false;
            }
            $customization->sendNote([
                'text' => $this->text,
                'type' => OrderCustomization::NOTE_TYPE_SUPPLY,
                'user_id' => \Yii::$app->user->id
            ]);
            $customization->rejectOrder();
            OrderHandler::cancel($order);
            return true;
        } catch (\Exception $e){
            $this->addError('reject_order', 1175);
            return false;
        }
    }

    /**
     * 发货
     * @return array|bool
     */
    public function shipOrder()
    {
        try {
            $model = new OrderCustomization(['order_number' => $this->order_number]);
            $model->ship($this->express_corporation, $this->express_number);
            return [];
        } catch (\Exception $e) {
            $this->addError('ship', 1094);
            return false;
        }
    }
}
