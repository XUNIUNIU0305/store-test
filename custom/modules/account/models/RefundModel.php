<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 11:29
 */

namespace custom\modules\account\models;


use common\ActiveRecord\ExpressCorporationAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\OrderRefundAR;
use common\components\handler\Handler;
use common\components\handler\OrderRefundHandler;
use common\models\Model;
use common\models\parts\Item;
use common\models\parts\ItemInOrder;

use common\models\parts\Order;
use common\models\parts\order\OrderRefund;
use common\models\parts\Supplier;
use Yii;

class RefundModel extends Model
{

    const SCE_CREATE_REQUEST = "create_request";//创建申请
    const SCE_GET_LIST = "get_refund_order_list";//获取当前用户退款申请列表
    const SCE_GET_REFUND_ORDER_INFO = "get_refund_order_info";//获取用户订单信息
    const SCE_SEND_PACKAGE = "send_package";//客户发货
    const SCE_GET_ORDER_ITEM = "get_order_item";//获取订单项信息
    const SCE_FINISHED_ORDER = "finished";//结束完成退换货订单
    const SCE_CUSTOM_CAN_REFUND="custom_can_refund";//


    public $item_id;//订单商品项ID
    public $quantity;//退换数量
    public $reason;//退换原因
    public $images;//图片
    public $order_code;//订单code


    public $refund_code;//退换货订单号
    public $type;//退换货类型
    public $status;//订单状态
    public $current_page;//当前页
    public $page_size;//每页显示记录数量


    public $express_id;//物流公司ID
    public $shipping_code;//物流 单号

    public $custom_user_id;


    //配置场景
    public function scenarios()
    {
        return [
            self::SCE_CREATE_REQUEST => ['order_code', 'item_id', 'quantity', 'reason', 'images'],
            self::SCE_GET_LIST => ['current_page', 'page_size', 'status', 'refund_code', 'type'],
            self::SCE_GET_REFUND_ORDER_INFO => ['refund_code'],
            self::SCE_SEND_PACKAGE => ['refund_code', 'express_id', 'shipping_code'],
            self::SCE_FINISHED_ORDER => ['refund_code'],
            self::SCE_GET_ORDER_ITEM => ['order_code', 'item_id'],
            self::SCE_CUSTOM_CAN_REFUND=>['order_code','item_id'],
        ];
    }

    //配置规则
    public function rules()
    {
        return [
            [
                ['order_code', 'item_id', 'quantity', 'reason', 'express_id', 'shipping_code'],
                'required',
                'message' => 9001,
            ],
            [
                ['express_id'],
                'exist',
                'targetClass' => ExpressCorporationAR::className(),
                'targetAttribute' => ['express_id' => 'id'],
                'message' => 3278,
            ],
            [
                ['type'],
                'default',
                'value' => null,
            ],
            [
                ['status'],
                'default',
                'value' => null,
            ],
            [
                ['type'],
                'in',
                'range' => [
                    OrderRefund::REFUND_TYPE_NEW,
                    OrderRefund::REFUND_TYPE_BARTER,
                    OrderRefund::REFUND_TYPE_REFUND,
                ],
                'message' => 9002,
            ],
            [
                ['status'],
                'in',
                'range' => [
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
                ['refund_code'],
                'required',
                'message' => 9001,
                'on' => [self::SCE_GET_REFUND_ORDER_INFO, self::SCE_SEND_PACKAGE, self::SCE_FINISHED_ORDER],
            ],
            [
                ['refund_code'],
                'exist',
                'targetClass' => OrderRefundAR::className(),
                'targetAttribute' => ['refund_code' => 'code', 'CustomUserId' => 'custom_user_id'],
                'message' => 3277,
                'on' => [self::SCE_GET_REFUND_ORDER_INFO, self::SCE_SEND_PACKAGE, self::SCE_FINISHED_ORDER],
            ],
            [
                ['refund_code'],
                'default',
                'value' => '',
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
                ['order_code'],
                'exist',
                'targetClass' => OrderAR::className(),
                'targetAttribute' => ['order_code' => 'order_number', 'CustomUserId' => 'custom_user_id'],
                'message' => 3281,
            ],
            [
                ['images'],
                'default',
                'value' => [],
            ],
            [
                ['quantity'],
                'default',
                'value' => 1,
            ],
            [
                ['item_id'],
                'exist',
                'targetClass' => OrderItemAR::className(),
                'targetAttribute' => ['item_id' => 'id'],
                'message' => 3270,
            ],
            [
                ['item_id'],
                'common\validators\item\RefundItemIdValidator',
                'custom_user_id' => Yii::$app->user->id,
                'order_code' => $this->order_code,
                'quantity' => $this->quantity,
                'messageForOrder' => 3271,//验证订单，验证订单是否属于该用户
                'messageForUser' => 3272,//验证用户，订单项是否属于用户
                'messageForQuantity' => 3273,//验证数量，大于订购数量时输出错误
                'messageForStatus' => 3282,//检测订单状态
                'validatorExists' => true,
                'messageRefundExists' => 3276,//退换货订单已存在
                'message' => 3270,//验证报错
                'on' => [self::SCE_CREATE_REQUEST,self::SCE_CUSTOM_CAN_REFUND],
            ]
        ];
    }
    //验证是否允许客户提交退换货
    public function customCanRefund(){

        $item=(new ItemInOrder(['id'=>$this->item_id]));
        //使用过优惠券，禁止退换
        if(!\common\ActiveRecord\ActivityGpubsGroupDetailAR::findOne(['order_number' => $item->getOrder()->getOrderNo()])){
            if($item->getOrder()->getTrade()->getCouponRecord()){
                $this->addError('customCanRefund',3286);
                return false;
            }
        }

        if(!$item->refundExists()&&($item->getCount()>$item->getRefundQuantity())){
            return true;
        }
        $this->addError('customCanRefund',3285);
        return false;
    }

    //获取订单项信息
    public function getOrderItem()
    {
        $items = new ItemInOrder(['id' => $this->item_id]);
        $emptyFunc = function ($data) {
            return empty($data) ? '' : $data;
        };
        $refundQuantity= $items->refundQuantity;
        $count=function($count)use($refundQuantity){
            return ($count - $refundQuantity);
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
                },
                'count'=>$count,
                'item' => function($item){
                    return [
                        'id' => $item->productId,
                    ];
                }
            ]
        ]);

    }


    //完成退换货订单
    public function finished()
    {
        $order = new OrderRefund(['code' => $this->refund_code]);
        if ($order->setStatus(OrderRefund::REFUND_STATUS_FINISHED)) {
            return true;
        }
        $this->addError('finished', 3280);
        return false;

    }

    //获取用户id
    protected function getCustomUserId()
    {
        return Yii::$app->user->id;
    }


    //客户发货
    public function sendPackage()
    {

        $order = new OrderRefund(['code' => $this->refund_code]);
        //设置物流公司ID
        $order->customer_express_corporation_id = $this->express_id;
        //设置物流单号
        $order->customer_shipping_code = $this->shipping_code;
        //设置订单状态
        if ($order->setStatus(OrderRefund::REFUND_STATUS_BACK)) {
            return true;
        }
        $this->addError('sendPackage', 3279);
        return false;
    }

    //获取退货货单相关信息
    public function getRefundOrderInfo()
    {
        $order = new OrderRefund(['code' => $this->refund_code]);
        //订购项
        $item = $order->getGoodsItem();
        //商户信息
        $supplier = $item->getSupplier();

        $this->item_id=$item->id;
        $itemInfo=$this->getOrderItem();
        $itemInfo['count']=$order->getQuantity();
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
            'item_id'=>$item->id,
            'refund_type' => $order->getType(),
            'order_info'=>$itemInfo,
            'customer_image'=>$order->getImages(OrderRefund::IMAGE_TYPE_CUSTOMER),//客户图片
            'service_image'=>$order->getImages(OrderRefund::IMAGE_TYPE_SERVICE),//客服图片
            'reason'=>$order->getReason(),
            'cancel_reason' => $order->getCancelReason(),
            'goods_id'=>$item->getItem()->getProductId(),
            'plat_suggestion'=>$order->getPlatSuggestion(),
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

    //查询获取当前用户退换货订单列表
    public function getRefundOrderList()
    {
        $model = OrderRefundHandler::getRefundOrderList($this->current_page, $this->page_size, $this->type, $this->status, $this->refund_code, Yii::$app->user->id);
        $data = array_map(function ($item) {
            $refund = new OrderRefund(['id' => $item['id']]);
            $item = $refund->getGoodsItem();
            $data = [
                'refund_order_code' => $refund->getCode(),//退款单号
                'order_create_time' => $refund->getCreateTime(),//订单创建时间
                'order_code' => $item->getOrder()->getOrderNo(),//关联订单号
                'goods_img' => $item->getImage()->getPath(),
                'goods_title' => $item->getTitle(),
                'refund_status' => $refund->getStatus(),
                'goods_id'=>$item->getItem()->getProductId(),
                'refund_status_txt' => $refund->getStatusName(),
                'refund_type' => $refund->getType(),
                'refund_type_txt' => $refund->getStatusName(),
                'refund_quantity' => $refund->getQuantity(),
                'goods_price' => $item->getPrice(),
                'refund_total' => $item->getPrice() * $refund->getQuantity(),
            ];
            return $data;
        }, $model->models);
        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];
    }


    //获取订购单商品列表
    public function createRequest()
    {
        //验证数量
        if ($this->quantity <= 0) {
            $this->addError('createRequest', 3274);
            return false;
        }
        $item=new ItemInOrder(['id' => $this->item_id]);
        if(empty($item->getSupplier()->getAddress())){
            $this->addError('createRequest',3284);
            return false;
        }
        //如果该订单使用过优惠券，则不给退换货
        if(!\common\ActiveRecord\ActivityGpubsGroupDetailAR::findOne(['order_number' => $item->getOrder()->getOrderNo()])){
            if($item->getOrder()->getTrade()->getCouponRecord()){
                $this->addError('customCanRefund',3286);
                return false;
            }
        }


        //创建退换货订单
        if (OrderRefundHandler::create($item, $this->quantity, $this->reason, $this->images, false)) {
            return true;
        }
        $this->addError('createRequest', 3275);
        return false;
    }


}
