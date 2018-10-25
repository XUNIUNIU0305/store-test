<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 14:43
 */

namespace admin\modules\service\models;


use Yii;
use common\ActiveRecord\OrderRefundAR;
use common\components\handler\Handler;
use common\components\handler\OrderRefundHandler;
use common\models\Model;
use common\models\parts\ItemInOrder;
use common\models\parts\Order;
use common\models\parts\order\OrderRefund;
use common\components\handler\OSSImageHandler;
class OrderRefundModel extends Model
{

    const SCE_GET_REFUND_ORDER_LIST="get_refund_order_list";//获取换货订单列表
    const SCE_GET_REFUND_ORDER_INFO="get_refund_order_info";//获取退换货订单信息
    const SCE_CHECK_REFUND_ORDER="check_refund_order";//审核用户退换申请
    const SCE_ADD_COMMENTS="add_comments";//添加备注
    const SCE_REFUND_RMB = 'refund_rmb'; //执行退款
    const SCE_CANCEL_REFUND = 'cancel_refund'; //取消退换单
    const SCE_INSTALL_CUSTOM_SENDING = 'install_custom_sending'; //代替客户发货


    public $page_size;
    public $current_page;
    public $status;
    public $refund_code;
    public $reason;
    public $images;
    public $sort;
    public $type;
    public $refund_rmb;
    public $cancel_reason;
    public $express_corporation_id;
    public $shipping_code;


    private $item_id;


    //场景
    public function scenarios()
    {
        return [
            self::SCE_GET_REFUND_ORDER_LIST=>['current_page','page_size','sort','status','refund_code'],
            self::SCE_GET_REFUND_ORDER_INFO=>['refund_code'],
            self::SCE_CHECK_REFUND_ORDER=>['refund_code','reason','images','type', 'refund_rmb'],
            self::SCE_ADD_COMMENTS=>['refund_code','reason','images'],
            self::SCE_REFUND_RMB => [
                'refund_code',
                'refund_rmb',
            ],
            self::SCE_CANCEL_REFUND => [
                'refund_code',
                'cancel_reason',
            ],
            self::SCE_INSTALL_CUSTOM_SENDING => [
                'refund_code',
                'express_corporation_id',
                'shipping_code',
            ],
        ];
    }

    //规则
    public function rules()
    {
        return [
            [
                ['refund_code'],
                'default',
                'value'=>'',
            ],
            [
                ['refund_code'],
                'exist',
                'targetClass'=>OrderRefundAR::className(),
                'targetAttribute'=>['refund_code'=>'code'],
                'message'=>5200,
            ],

            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['images'],
                'default',
                'value'=>[],
            ],
            [
                ['status'],
                'default',
                'value'=>OrderRefundHandler::SEARCH_TYPE_EXEC_FOR_ADMIN,
            ],
            [
                ['reason'],
                'default',
                'value'=>'',
            ],

            [
                ['reason'],
                'string',
                'length'=>[0,255],
                'tooShort'=>5203,
                'tooLong'=>5211,
                'message'=>5205,
            ],
            [
                ['cancel_reason'],
                'string',
                'length'=>[1,255],
                'tooShort'=> 5099,
                'tooLong'=> 5099,
                'message'=> 9002,
            ],
            //审核时添加验证
            [
                ['images'],
                'common\validators\item\PlatSuggestionValidator',
                'reason'=>$this->reason,
                'message'=>5207,
            ],
            [
                ['type'],
                'in',
                'range'=>[OrderRefund::REFUND_TYPE_NEW,OrderRefund::REFUND_TYPE_REFUND,OrderRefund::REFUND_TYPE_BARTER],
                'message'=>5202,
                'on'=>[self::SCE_CHECK_REFUND_ORDER],
            ],
            [
                ['sort'],
                'default',
                'value'=>0,//由远到近
            ],
            [
                ['refund_rmb'],
                'number',
                'min' => 0.01,
                'tooSmall' => 5208,
                'message' => 5208,
            ],
            [
                ['express_corporation_id', 'shipping_code'],
                'required',
                'message' => 9001,
            ],
            [
                ['express_corporation_id'],
                'exist',
                'targetClass' => \common\ActiveRecord\ExpressCorporationAR::className(),
                'targetAttribute' => ['express_corporation_id' => 'id'],
                'message' => 9002,
            ],
            [
                ['shipping_code'],
                'string',
                'length' => [1, 20],
                'tooShort' => 5099,
                'tooLong' => 5099,
                'message' => 9002,
            ],
        ];

    }

    //添加备注信息
    public function addComments(){
        $order = new OrderRefund(['code' => $this->refund_code]);
        if($order->addSuggestion($this->reason,$this->images,Yii::$app->user->id)){
            return true;
        }
        $this->addError('addComments',5206);
        return false;
    }

    public function getRefundOrderList(){
        //设置排序
        $orderBy=['id'=>SORT_DESC];
        if($this->sort==1){
            $orderBy=['id'=>SORT_ASC];
        }

        $model=OrderRefundHandler::getRefundOrderList($this->current_page,$this->page_size,null,$this->status,$this->refund_code,null,null,$orderBy);

        $data=array_map(function($item){
            $order=new OrderRefund(['id'=>$item['id']]);
            $item=$order->getGoodsItem();
            return [
                'id' => $order->id,
                'code' => $order->getCode(),
                'image' => $item->getImage()->path,
                'title' => $item->getTitle(),
                'customer_account'=>$order->getCustomer()->getAccount(),
                'price' => $item->getPrice(),
                'order_quantity' => $item->getCount(),
                'quantity' => $order->getQuantity(),
                'total' => ($item->getPrice() * $order->getQuantity()),
                'status' => $order->getStatus(),
                'create_time'=>$order->getCreateTime(),
              
            ];
        },$model->models);

        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
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
            'refund_rmb' => $order->getRefundRmb(),
            'apply_refund_rmb' => $order->getApplyRefundRmb(),
            'images'=>$order->getImages(),
            'reason'=>$order->getReason(),
            'cancel_reason' => $order->getCancelReason(),
            'order_info'=>$itemInfo,
            'original_order_info' => $this->getOriginalOrderInfo($item->getOrder()),
            'seller'=>[
                'id'=>$order->getCustomer()->id,
                'account'=>$order->getCustomer()->getAccount(),
                'headerImg'=>$order->getCustomer()->getHeaderImg(),
                'email'=>$order->getCustomer()->getEmail(),
                'area'=>$order->getCustomer()->getProvince()->getName()." ".$order->getCustomer()->getCity()->getName()." ".$order->getCustomer()->getDistrict()->getName(),
                'mobile'=>$order->getCustomer()->getMobile(),
            ],
            'customization'=>$item->getItem()->getProductObj()->getCustomization(),
            'plat_suggestion'=>$order->getPlatSuggestion(),
            'customer_image'=>$order->getImages(OrderRefund::IMAGE_TYPE_CUSTOMER),//客户图片
            'service_image'=>$order->getImages(OrderRefund::IMAGE_TYPE_SERVICE),//客服图片
            'supply' => [
                'address' => (($province=$supplier->getProvince(true))?$province->getName()." ":"").(($city=$supplier->getCity(true))?$city->getName()." ":"").(($district=$supplier->getDistrict(true))?$district->getName():"").$supplier->getAddress(),
                'mobile' => $supplier->getMobile(),
                'telephone' => $supplier->getAreaCode()."-".$supplier->getTelephone(),
                'company_name' => $supplier->getCompanyName(),
                'shipping_company' => $order->getSupplyShippingCompany(),
                'shipping_code' => $order->getSupplyShippingCode(),
                'brand_name'=>$supplier->getBrandName(),
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

    private function getOriginalOrderInfo(Order $order){
        return Handler::getMultiAttributes($order, [
            'total_fee' => 'totalFee',
            'items_fee' => 'itemsFee',
            'coupon_rmb' => 'couponRmb',
            'refund_rmb' => 'refundRmb',
            'items',
            '_func' => [
                'items' => function($items){
                    return array_map(function($item){
                        return Handler::getMultiAttributes($item, [
                            'id',
                            'product_id' => 'item',
                            'title',
                            'attributes' => 'SKUAttributes',
                            'price',
                            'count',
                            'total_fee' => 'totalFee',
                            'image',
                            'comments',
                            '_func' => [
                                'item' => function($item){
                                    return $item->productId;
                                },
                                'image' => function($image){
                                    $ossImageHandlerObj = OSSImageHandler::load($image);
                                    $ossSize = $ossImageHandlerObj->resize(92,92);
                                    return $ossSize->apply() ? $ossSize->image->path : '';
                                },
                            ],
                        ]);
                    }, $items);
                },
            ],
        ]);
    }

    //审核退换订单
    public function checkRefundOrder(){
        $order=new OrderRefund(['code'=>$this->refund_code]);
        //添加图片
        $order->plat_images=$this->images;
        //平台意见
        $order->plat_suggestion=$this->reason;
        $order->admin_user_id=Yii::$app->user->id;

        if($this->type==OrderRefund::REFUND_TYPE_NEW){
            //类型为0时，置其为拒绝
            $status=OrderRefund::REFUND_STATUS_REJECT;
        }elseif($this->type==OrderRefund::REFUND_TYPE_BARTER||$this->type==OrderRefund::REFUND_TYPE_REFUND){
            //当类型为退或者换时，表示同意
            $status=OrderRefund::REFUND_STATUS_AGREE;
            $order->type=$this->type;
        }
        //更新状态
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($order->setStatus($status)){
                if($this->type == OrderRefund::REFUND_TYPE_REFUND){
                    $order->refundRmb = (float)$this->refund_rmb;
                }
                $transaction->commit();
                return true;
            }else{
                throw new \Exception;
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('checkRefundOrder',5201);
            return false;
        }
    }

    //确认退款
    public function refundRmb(){
        $order = new OrderRefund([
            'code' => $this->refund_code,
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$order->setRefundRmb((float)$this->refund_rmb, false)){
                throw new \Exception;
            }
            if(!$order->setStatus(OrderRefund::REFUND_STATUS_REFUND_MONEY))throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('refundRmb', 5209);
            return false;
        }
    }

    //取消退换单
    public function cancelRefund(){
        $order = new OrderRefund([
            'code' => $this->refund_code,
        ]);
        if($order->cancel($this->cancel_reason)){
            return true;
        }else{
            $this->addError('cancelRefund', 5421);
            return false;
        }
    }

    //代替客户发货
    public function installCustomSending(){
        $order = new OrderRefund([
            'code' => $this->refund_code,
            'customer_express_corporation_id' => $this->express_corporation_id,
            'customer_shipping_code' => $this->shipping_code,
            'admin_user_id' => Yii::$app->user->id,
        ]);
        if($order->setStatus(OrderRefund::REFUND_STATUS_BACK)){
            return true;
        }else{
            $this->addError('installCustomSending', 5422);
            return false;
        }
    }
}
