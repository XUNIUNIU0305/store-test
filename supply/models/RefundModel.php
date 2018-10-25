<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 10:32
 */

namespace supply\models;


use common\ActiveRecord\ExpressCorporationAR;
use common\ActiveRecord\OrderRefundAR;
use common\components\handler\Handler;
use common\components\handler\OrderRefundHandler;
use common\models\Model;
use common\models\parts\ItemInOrder;
use common\models\parts\Order;
use common\models\parts\order\OrderRefund;
use Yii;


class RefundModel extends Model
{

    const SCE_GET_REFUND_LIST = "get_refund_list";//获取退换单列表
    const SCE_SUPPLY_AGREE_REFUND = "supply_agree_refund";//商户同意用户退换
    const SCE_SUPPLY_SEND_BACK = "supply_send_back";//商户发回换货
    const SCE_AGREE_REFUND_MONEY = "agree_refund_money";//商户同意退款
    const SCE_GET_REFUND_ORDER_INFO = "get_refund_order_info";//获取退货订单信息


    public $refund_code;//退换单号
    public $refund_type;//退换单类型
    public $refund_status;//退换单状态

    public $shipping_company;//物流 公司
    public $shipping_code;//物流编号

    public $page_size;//每页显示数量
    public $current_page;//当前页



    private $item_id;

    public function scenarios()
    {
        return [
            self::SCE_GET_REFUND_LIST => ['current_page', 'page_size', 'refund_code', 'refund_type', 'refund_status'],
            self::SCE_SUPPLY_AGREE_REFUND => ['refund_code'],
            self::SCE_SUPPLY_SEND_BACK => ['refund_code', 'shipping_company', 'shipping_code'],
            self::SCE_AGREE_REFUND_MONEY => ['refund_code'],
            self::SCE_GET_REFUND_ORDER_INFO => ['refund_code'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['shipping_company', 'shipping_code'],
                'required',
                'message' => 9001,
            ],
            [
                ['refund_code'],
                'required',
                'message' => 9001,
                'on' => [self::SCE_SUPPLY_AGREE_REFUND, self::SCE_SUPPLY_SEND_BACK, self::SCE_AGREE_REFUND_MONEY, self::SCE_GET_REFUND_ORDER_INFO],
            ],
            [
                ['refund_code'],
                'default',
                'value' => '',
                'on' => [self::SCE_GET_REFUND_LIST]
            ],
            [
                ['refund_code'],
                'exist',
                'targetClass' => OrderRefundAR::className(),
                'targetAttribute' => ['refund_code' => 'code', 'SupplyUserId' => 'supply_user_id'],
                'message' => 1131,//订单信息不存在
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
                ['refund_type'],
                'default',
                'value' => null,
            ],
            [
                ['refund_status'],
                'default',
                'value' => OrderRefund::REFUND_STATUS_AGREE,//处理客服已处理退换
            ],
            [
                ['refund_status'],
                'in',
                'range' => [
                    OrderRefundHandler::SEARCH_TYPE_EXEC_FOR_SUPPLY,
                    OrderRefund::REFUND_STATUS_NEW,
                    OrderRefund::REFUND_STATUS_AGREE,
                    OrderRefund::REFUND_STATUS_REJECT,
                    OrderRefund::REFUND_STATUS_SUPPLY_AGREE,
                    OrderRefund::REFUND_STATUS_BACK,
                    OrderRefund::REFUND_STATUS_RECEIVE_CONFIRM,
                    OrderRefund::REFUND_STATUS_REFUND_MONEY,
                    OrderRefund::REFUND_STATUS_SENDED,
                    OrderRefund::REFUND_STATUS_FINISHED,
                    OrderRefund::REFUND_STATUS_CANCEL,
                ],
                'message' => 9002,
            ],
            [
                ['shipping_company'],
                'exist',
                'targetClass' => ExpressCorporationAR::className(),
                'targetAttribute' => ['shipping_company' => 'id'],
                'message' => 1130
            ],

        ];
    }

    //获取订单项信息
    private function getOrderItem()
    {
        $items = new ItemInOrder(['id' => $this->item_id]);
        $emptyFunc = function ($data) {
            return empty($data) ? '' : $data;
        };
        return Handler::getMultiAttributes($items, [
            'id',
            'title',
            'price',
            'count',
            'image',
            'attributes' => 'SKUAttributes',
            'product_id' => 'item',
            '_func' => [
                'title' => $emptyFunc,
                'image' => function ($image) {
                    return $image->path;
                }
            ]
        ]);

    }



    //获取退货货单相关信息
    public function getRefundOrderInfo()
    {
        $order = new OrderRefund(['code' => $this->refund_code]);
        //订购项
        $item = $order->getGoodsItem();
        //获取退订单商品相关信息
        $this->item_id=$item->id;
        $itemInfo=$this->getOrderItem();
        //设置退换数量
        $itemInfo['count']=$order->getQuantity();
        //商户信息
        $supplier = $item->getSupplier();
        return [
            'refund_code' => $order->getCode(),
            'order_code' => $item->getOrder()->getOrderNo(),
            'create_time'=>$order->getCreateTime(),
            'refund_rmb' => $order->getRefundRmb(),
            'service_agree_time'=>$order->getServiceAgreeTime(),
            'service_reject_time'=>$order->getServiceRejectTime(),
            'supply_agree_time'=>$order->getSupplyAgreeTime(),
            'customer_send_back_time'=>$order->getCustomerSendBackTime(),
            'supply_receive_confirm_time' => $order->getSupplyReceiveConfirmTime(),
            'supply_refund_money_time'=>$order->getSupplyRefundMoneyTime(),
            'supply_refund_send_time'=>$order->getSupplyRefundSendTime(),
            'finished_time'=>$order->getFinishedTime(),
            'cancel_time' => $order->getCancelTime(),
            'refund_status' => $order->getStatus(),
            'refund_type' => $order->getType(),
            'images' => $order->getImages(),
            'reason' => $order->getReason(),
            'cancel_reason' => $order->getCancelReason(),
            'order_info'=>$itemInfo,
            'customer_image'=>$order->getImages(OrderRefund::IMAGE_TYPE_CUSTOMER),//客户图片
            'service_image'=>$order->getImages(OrderRefund::IMAGE_TYPE_SERVICE),//客服图片
            'plat_suggestion' => $order->getPlatSuggestion(),
            'supply' => [
                'address' => (($province=$supplier->getProvince(true))?$province->getName()." ":"").(($city=$supplier->getCity(true))?$city->getName()." ":"").(($district=$supplier->getDistrict(true))?$district->getName():"").$supplier->getAddress(),
                'mobile' => $supplier->getMobile(),
                'telephone' => $supplier->getAreaCode()."-".$supplier->getTelephone(),
                'company_name' => $supplier->getCompanyName(),
                'shipping_company' => $order->getSupplyShippingCompany(),
                'shipping_code' => $order->getSupplyShippingCode(),
            ],
            'customer' => [
                'shipping_company' => $order->getCustomerShippingCompany(),
                'shipping_code' => $order->getCustomerShippingCode(),
                'receive_address'=>$item->getOrder()->getAddress(),
                'receive_consignee'=>$item->getOrder()->getConsignee(),
                'receive_mobile'=>$item->getOrder()->getMobile(),
            ],
        ];
    }


    //商户同意退款
    public function agreeRefundMoney()
    {
        if ((new OrderRefund(['code' => $this->refund_code]))->setStatus(OrderRefund::REFUND_STATUS_RECEIVE_CONFIRM)) {
            return true;
        }
        $this->addError('agreeRefundMoney', 1134);
        return false;
    }


    //商户发货
    public function supplySendBack()
    {
        $order = new OrderRefund(['code' => $this->refund_code]);
        //设置物流公司
        $order->express_corporation_id = $this->shipping_company;
        //设置物流骗码
        $order->shipping_code = $this->shipping_code;
        if ($order->setStatus(OrderRefund::REFUND_STATUS_SENDED)) {
            return true;
        }
        $this->addError('supplySendBack', 1133);
        return false;
    }


    //商户同意退换货
    public function supplyAgreeRefund()
    {
        if ((new OrderRefund(['code' => $this->refund_code]))->setStatus(OrderRefund::REFUND_STATUS_SUPPLY_AGREE)) {
            return true;
        }
        $this->addError('supplyAgreeRefund', 1132);
        return false;
    }


    //获取商户id
    protected function getSupplyUserId()
    {
        return Yii::$app->user->id;
    }


    //获取退换单列表
    public function getRefundList()
    {
        $model = OrderRefundHandler::getRefundOrderList($this->current_page, $this->page_size, $this->refund_type, $this->refund_status, $this->refund_code, null, $this->getSupplyUserId());
        $data = array_map(function ($item) {
            $order = new OrderRefund(['id' => $item['id']]);
            $item = $order->getGoodsItem();
            $this->item_id=$item->id;
            $itemInfo=$this->getOrderItem();
            //设置退换数量
            $itemInfo['count']=$order->getQuantity();
            return [
                'id' => $order->id,
                'code' => $order->getCode(),
                'image' => $item->getImage()->path,
                'title' => $item->getTitle(),
                'price' => $item->getPrice(),
                'order_info'=>$itemInfo,
                'order_quantity' => $item->getCount(),
                'quantity' => $order->getQuantity(),
                'total' => ($item->getPrice() * $order->getQuantity()),
                'status' => $order->getStatus(),
                'create_time'=>$order->getCreateTime(),
                'service_agree_time'=>$order->getServiceAgreeTime(),
                'service_reject_time'=>$order->getServiceRejectTime(),
                'supply_agree_time'=>$order->getSupplyAgreeTime(),
                'customer_send_back_time'=>$order->getCustomerSendBackTime(),
                'supply_refund_money_time'=>$order->getSupplyRefundMoneyTime(),
                'supply_refund_send_time'=>$order->getSupplyRefundSendTime(),
                'finished_time'=>$order->getFinishedTime(),
            ];
        }, $model->models);
        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];


    }


}
