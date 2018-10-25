<?php
namespace supply\models;

use common\ActiveRecord\CustomUserAR;
use common\components\handler\OSSImageHandler;
use common\models\parts\Express;
use common\models\parts\trade\PaymentMethodList;
use Yii;
use common\models\Model;
use common\models\parts\Order;
use common\components\handler\Handler;
use common\ActiveRecord\ExpressCorporationAR;
use common\models\RapidQuery;
use PHPExcel;

class OrderModel extends Model
{

    const SCE_GET_LIST = 'get_list';
    const SCE_EXPORT_ORDRE = 'export_order';
    const SCE_SET_DELIVERED = 'set_delivered';
    const SCE_DETAIL = 'detail';
    const SCE_GET_EXPRESS = 'get_express';

    public $status;
    public $current_page;
    public $page_size;
    public $order_id;
    public $express_corporation;
    public $express_number;

    //==================搜索条件==================

    //订单号
    public $order_no;
    //购买人
    public $account;
    //物流号、物流公司
    public $corporation,$number;
    //收货人、收货地址
    public  $receive_consignee,$receive_address;

    //时间
    public $create_time,$pay_time,$deliver_time,$cancel_time,$close_time;

    //导出
    public $export_type;

    //定义状态文本数组
    private $status_text = [1=>'未处理', 2=>'已处理', 3=>'已收货', 4=>'已取消',5=>'已关闭'];

    //定义订单状态文本数组
    private $order_status_text = ['未支付','未发货','已发货','已确认收货','已取消','已关闭'];


    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [
                'order_no',
                'status',
                'current_page',
                'page_size',
                'corporation',
                'number',
                'receive_consignee',
                'receive_address',
                'create_time',
                'pay_time',
                'deliver_time',
                'cancel_time',
                'close_time',
                'account',
            ],

            self::SCE_EXPORT_ORDRE => [
                'export_type',
                'status',
                'corporation',
                'number',
                'receive_consignee',
                'receive_address',
                'create_time',
                'pay_time',
                'deliver_time',
                'cancel_time',
                'close_time',
                'account',
                'order_no',
            ],
            self::SCE_SET_DELIVERED => [
                'order_id',
                'express_corporation',
                'express_number',
            ],
            self::SCE_DETAIL => [
                'order_id'
            ],

            self::SCE_GET_EXPRESS => [
                'order_id'
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['status'],
                'default',
                'value' => 0,
            ],
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],

            [
                ['export_type'],
                'in',
                'range' => [0,1,2],//0导出订单，1导出全部，2导出商品
                'message' => 1130,
            ],
            [
                [
                    'status',
                    'current_page',
                    'page_size',
                    'order_id',
                    'express_corporation',
                    'express_number'
                ],
                'required',
                'message' => 9001,
            ],
            [
                ['status'],
                'in',
                'range' => Order::getStatuses(),
                'message' => 1081,
            ],
            [
                [
                    'current_page',
                    'page_size',
                    'express_corporation'
                ],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['order_id'],
                'integer',
                'min' => 1000000000,
                'max' => 9999999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['order_id'],
                'common\validators\order\NoValidator',
                'supplierId' => Yii::$app->user->id,
                'message' => 1091,
            ],
            [
                ['express_corporation'],
                'exist',
                'targetClass' => ExpressCorporationAR::className(),
                'targetAttribute' => 'id',
                'message' => 1092,
            ],
            [
                ['express_number'],
                'string',
                'length' => [
                    1,
                    20
                ],
                'tooShort' => 1093,
                'tooLong' => 1093,
                'message' => 1093,
            ],
        ];
    }

    public function setDelivered()
    {
        $order = new Order([
            'orderNumber' => $this->order_id,
            'expressCorporation' => $this->express_corporation,
            'expressNumber' => $this->express_number,
        ]);
        if ($order->setStatus(Order::STATUS_DELIVERED))
        {
            return true;
        }
        else
        {
            $this->addError('setDelivered', 1094);
            return false;
        }
    }

    /**
     *====================================================
     * 获取订单列表
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getList()
    {
        $searchData = $this->searchCondition();
        $status = $this->status === 0 ? null : (int)$this->status;
        $data = Yii::$app->SupplyUser->order->provideOrders($status, $this->current_page, $this->page_size, $searchData);
        $orders = array_map(function ($order){
            return new Order(['id' => $order['id']]);
        }, $data->models);

        return [
            'orders' => array_map(function ($order){
                return self::getOrderInfo($order);
            }, $orders),
            'count' => $data->count,
            'total_count' => $data->totalCount,
        ];
    }

    public static function getExpressCorporationList()
    {
        $express = (new RapidQuery(new ExpressCorporationAR))->all([
            'select' => [
                'id',
                'name',
                'code'
            ],
            'orderBy' => ['sort' => SORT_ASC],
        ]);
        return $express;
    }

    public static function getOrderQuantity()
    {
        $quantity = Yii::$app->SupplyUser->order->quantity;
        return [
            'unpaid' => $quantity[ORDER::STATUS_UNPAID] ?? 0,
            'undeliver' => $quantity[ORDER::STATUS_UNDELIVER] ?? 0,
            'delivered' => $quantity[ORDER::STATUS_DELIVERED] ?? 0,
            'confirmed' => $quantity[ORDER::STATUS_CONFIRMED] ?? 0,
            'canceled' => $quantity[ORDER::STATUS_CANCELED] ?? 0,
            'closed' => $quantity[ORDER::STATUS_CLOSED] ?? 0,
        ];
    }


    /**
     *====================================================
     * 导出订单
     * @author shuang.li
     *====================================================
     */
    public function exportOrder()
    {
        set_time_limit(0);
        //获取指定条件下的订单数据
        $this->page_size = 9999999;
        if (empty($this->status))
        {
            //全部订单 生成5个sheet
            self::createExcel(5);
        }
        else
        {
            //对应查询条件下的订单生成一个sheet
            self::createExcel(1);
        }
    }
    public function createExcel($sheetNum = null){

        $objPHPExcel = new PHPExcel();
        //导出商品信息
        if (!empty($this->export_type) && $this->export_type == 2){
            $objPHPExcel->setActiveSheetIndex(0);
            $objSheet = $objPHPExcel->getActiveSheet();
            $objSheet->setTitle($this->status_text[$this->status]);

            $orders = self::getList()['orders'];
            $objSheet->setCellValue('A1', '订单编号')
                ->setCellValue('B1', '订单状态')
                ->setCellValue('C1', '付款时间')
                ->setCellValue('D1', '商品名称')
                ->setCellValue('E1', '单价')
                ->setCellValue('F1', '备注')
                ->setCellValue('G1', '数量')
                ->setCellValue('H1', '属性')
                ->setCellValue('I1', '关闭时间')
                ->setCellValue('J1', '取消订单时间')
                ->setCellValue('K1', '确认收货时间')
                ->setCellValue('L1', '发货时间')
                ->setCellValue('M1', '物流公司')
                ->setCellValue('N1', '支付方式')
            ;
            $j = 2;
            foreach ($orders as $order){
                foreach ($order['items'] as $item){
                    $attributes = array_map(function($attribute){
                        return implode('-',$attribute);

                    },$item['attributes']);
                    $attributes = implode(';',$attributes);
                    $objSheet->setCellValueExplicit('A' . $j, $order['order_no'], \PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue('B' . $j, $this->order_status_text[$order['status']])
                        ->setCellValue('C' . $j, $order['pay_time'])
                        ->setCellValue('D' . $j, $item['title'])
                        ->setCellValue('E'.$j,$item['price'])
                        ->setCellValue('F'.$j,$item['comments'])
                        ->setCellValue('G'.$j,$item['count'])
                        ->setCellValue('H' . $j, $attributes)
                        ->setCellValue('I'.$j, $order['close_time'])
                        ->setCellValue('J'.$j, $order['cancel_time'])
                        ->setCellValue('K'.$j, $order['receive_time'])
                        ->setCellValue('L'.$j, $order['deliver_time'])
                        ->setCellValue('M'.$j, $order['express_corporation'])
                        ->setCellValue('N'.$j, $order['pay_method'])
                    ;
                    $j++;
                }
            }
            $objSheet->getStyle('A1:Z1')->getFont()->setName('微软雅黑')->setSize(12)->setBold(true);
        }
        else
        {
            for ($i = 1; $i <= $sheetNum; $i++)
            {
                $order = null;
                if ($i > 1)
                {
                    $objPHPExcel->createSheet();//创建内置表
                }
                //设置当前活动sheet
                $objPHPExcel->setActiveSheetIndex($i - 1);
                //获取当前活动sheet
                $objSheet = $objPHPExcel->getActiveSheet();

                if ($sheetNum == 5){
                    //给当前活动sheet设置名称
                    $objSheet->setTitle($this->status_text[$i]);
                    //获取指定状态的订单数据
                    $this->status = $i;
                }else{
                    $objSheet->setTitle($this->status_text[$this->status]);
                }

                $order = self::getList()['orders'];
                $objSheet->setCellValue('A1', '订单编号')
                    ->setCellValue('B1', '订单状态')
                    ->setCellValue('C1', '实付金额')
                    ->setCellValue('D1', '商品金额')
                    ->setCellValue('E1', '优惠金额')
                    ->setCellValue('F1', '付款时间')
                    ->setCellValue('G1', '订单生成时间')
                    ->setCellValue('H1', '买家名称')
                    ->setCellValue('I1', '买家电话')
                    ->setCellValue('J1', '收货地址')
                    ->setCellValue('K1', '运单号')
                    ->setCellValue('L1', '买家邮箱')
                    ->setCellValue('M1', '商品数量')
                    ->setCellValue('N1', '关闭时间')
                    ->setCellValue('O1', '取消订单时间')
                    ->setCellValue('P1', '确认收货时间')
                    ->setCellValue('Q1', '发货时间')
                    ->setCellValue('R1', '物流公司')
                    ->setCellValue('S1', '支付方式')
                    ->setCellValue('T1', '退款金额')
                ;
                //设置订单标题字体和大小
                $objSheet->getStyle('A1:Z1')->getFont()->setName('微软雅黑')->setSize(12)->setBold(true);
                $j = 2;
                foreach ($order as $k => $v)
                {
                    $count = 0;
                    for ($c =0;$c<count($v['items']);$c++){
                        $count +=$v['items'][$c]['count'];
                    }
                    //导出订单信息 export_type = ''
                    $objSheet->setCellValueExplicit('A' . $j, $v['order_no'], \PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue('B' . $j, $this->order_status_text[$v['status']])
                        ->setCellValue('C' . $j, $v['total_fee'])
                        ->setCellValue('D' . $j, $v['items_fee'])
                        ->setCellValue('E' . $j, $v['coupon_rmb'])
                        ->setCellValue('F' . $j, $v['pay_time'])
                        ->setCellValue('G' . $j, $v['create_time'])
                        ->setCellValue('H' . $j, $v['consignee'])
                        ->setCellValueExplicit('I' . $j, $v['mobile'], \PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue('J' . $j, $v['address'])
                        ->setCellValueExplicit('K' . $j, $v['express_number'], \PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue('L' . $j, '暂无')
                        ->setCellValue('M' . $j,$count)
                        ->setCellValue('N'.$j, $v['close_time'])
                        ->setCellValue('O'.$j, $v['cancel_time'])
                        ->setCellValue('P'.$j, $v['receive_time'])
                        ->setCellValue('Q'.$j, $v['deliver_time'])
                        ->setCellValue('R'.$j, $v['express_corporation'])
                        ->setCellValue('S'.$j, $v['pay_method'])
                        ->setCellValue('T'.$j, $v['refund_rmb'])
                    ;

                    //导出订单和商品信息 导出全部 export_type = 1
                    if (!empty($this->export_type) && $this->export_type == 1){
                        $orderNoStart = $j;
                        $j++;
                        $goodStartEnd = $j;
                        $objSheet->setCellValue('B' . $j, '商品名称')
                            ->setCellValue('D' . $j, '单价')
                            ->setCellValue('E' . $j, '备注')
                            ->setCellValue('H' . $j, '数量')
                            ->setCellValue('I' . $j, '属性');
                        $objSheet->getStyle('B' . $j . ':Z' . $j)->getFont()->setName('微软雅黑')->setSize(12)->setBold(true);
                        $n = 1;
                        foreach ($v['items'] as $v)
                        {
                            $attributes = array_map(function($attribute){
                                return implode('-',$attribute);

                            },$v['attributes']);
                            $attributes = implode(';',$attributes);
                            $m = null;
                            $m = $j + $n;
                            $objSheet->setCellValue('B' . $m, $v['title'])
                                ->setCellValue('D' . $m, $v['price'])
                                ->setCellValue('E' . $m, $v['comments'])
                                ->setCellValue('H' . $m, $v['count'])
                                ->setCellValue('I' . $m, $attributes);
                            $objSheet->mergeCells('B' . $m . ':C' . $m);
                            $objSheet->mergeCells('E' . $m . ':G' . $m);
                            $objSheet->mergeCells('I' . $m . ':J' . $m);
                            $n++;
                        }

                        $j += $n;
                        $orderNoEnd = $j - 1;
                        $objSheet->mergeCells('A' . $j . ':Z' . $j);
                        $objSheet->mergeCells('A' . $orderNoStart . ':A' . $orderNoEnd);
                        $objSheet->mergeCells('B' . $goodStartEnd . ':C' . $goodStartEnd);
                        $objSheet->mergeCells('E' . $goodStartEnd . ':G' . $goodStartEnd);
                        $objSheet->mergeCells('I' . $goodStartEnd . ':J' . $goodStartEnd);
                    }
                    $j++;
                }
            }
        }

        $objSheet->getStyle('A1:O' . $j)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //设置垂直居中 和水平居中
        $objSheet->getStyle('A1:O' . $j)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        //按照指定格式生成excel文件
        $excelWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=\"订单数据.xlsx\"");
        header("Content-Transfer-Encoding:binary");
        $excelWriter->save('php://output');
        exit;
    }

    private function explodeTime($time){
        if(strpos($time,',') !== false){
            list($startTime,$endTime) = explode(',',$time);
            return [strtotime($startTime.' 00:00:00'),strtotime($endTime . ' 23:59:59')];
        }
        $this->addError('implodeTime',1161);
        return false;

    }


    /**
     *====================================================
     * 获取订单详情
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function detail(){
        return self::getOrderInfo(new Order(['orderNumber' => $this->order_id]));
    }

    /**
     *====================================================
     * 获取订单详情
     * @param $order
     * @return array
     * @author shuang.li
     *====================================================
     */
    private function getOrderInfo($order){
        $emptyFunc = function ($data){
            return empty($data) ? '' : $data;
        };
        return Handler::getMultiAttributes($order, [
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
            'pay_method' => 'payMethod',
            'deliver_time' => 'deliverTime',
            'receive_time' => 'receiveTime',
            'close_time' => 'closeTime',
            'cancel_time' => 'cancelTime',
            'consignee',
            'address',
            'mobile',
            'postal_code' => 'postalCode',
            'items',
            '_func' => [
                'expressCorpName' => $emptyFunc,
                'expressNo' => $emptyFunc,
                'createTime' => $emptyFunc,
                'payTime' => $emptyFunc,
                'deliverTime' => $emptyFunc,
                'receiveTime' => $emptyFunc,
                'closeTime' => $emptyFunc,
                'cancelTime' => $emptyFunc,
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
                'items' => function ($items)
                {
                    return array_map(function ($item)
                    {
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
                                'image' => function ($image)
                                {
                                    $ossImageHandlerObj = OSSImageHandler::load($image);
                                    $ossSize = $ossImageHandlerObj->resize(90,90);
                                    return $ossSize->apply() ? $ossSize->image->path : '';
                                },

                            ],
                        ]);
                    }, $items);
                },
            ],
        ]);
    }


    /**
     *====================================================
     * 搜索条件
     * @author shuang.li
     *====================================================
     */
    protected function searchCondition(){
        $searchData = ['and','is_customization=0'];
        if(!empty($this->order_no)) {
            array_push($searchData,'order_number='.$this->order_no);
        }

        if(!empty($this->account)) {
            if($id = Yii::$app->RQ->AR(new CustomUserAR())->scalar([
                'select'=>['id'],
                'where'=>['account'=>$this->account]
            ])){
                array_push($searchData,'custom_user_id='.$id);
            }else{
                array_push($searchData,'custom_user_id=0');
            }
        }

        if(!empty($this->corporation)) {
            array_push($searchData,'express_corporation_id='.$this->corporation);
        }

        if(!empty($this->number)) {
            array_push($searchData,'express_number='.$this->number);
        }

        if(!empty($this->receive_consignee)) {
            $searchData[] = ['like','receive_consignee',$this->receive_consignee];
        }

        if(!empty($this->receive_address)) {
            $searchData[] = ['like','receive_address',$this->receive_address];
        }

        if(!empty($this->create_time)) {
            $time = self::explodeTime($this->create_time);
            $searchData[] = ['between', 'create_unixtime', $time[0], $time[1]];
        }

        if(!empty($this->pay_time)) {
            $time = self::explodeTime($this->pay_time);
            $searchData[] = ['between', 'pay_unixtime', $time[0], $time[1]];
        }

        if(!empty($this->deliver_time)) {
            $time = self::explodeTime($this->deliver_time);
            $searchData[] = ['between', 'deliver_unixtime', $time[0], $time[1]];
        }

        if(!empty($this->cancel_time)) {
            $time = self::explodeTime($this->cancel_time);
            $searchData[] = ['between', 'cancel_unixtime', $time[0], $time[1]];
        }

        if(!empty($this->close_time)) {
            $time = self::explodeTime($this->close_time);
            $searchData[] = ['between', 'close_unixtime', $time[0], $time[1]];
        }
        return $searchData;
    }


    /**
     *====================================================
     * 获取物流信息
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function getExpress(){
        $express = new Express([
            'order' => new Order(['orderNumber' => $this->order_id]),
        ]);
        if(!$detail = $express->detail){
            $this->addError('getExpress', 3212);
            return false;
        }
        return $detail;
    }
}
