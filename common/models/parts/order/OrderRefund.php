<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/31
 * Time: 17:07
 */

namespace common\models\parts\order;


use admin\components\AdminUser;
use admin\models\parts\role\AdminAccount;
use common\ActiveRecord\OrderRefundAR;
use common\ActiveRecord\OrderRefundImgAR;
use common\ActiveRecord\OrderRefundAdminSendLogAR;
use common\components\handler\OrderRefundCommentHandler;
use common\components\handler\OrderRefundImgHandler;
use common\models\parts\custom\CustomUser;
use common\models\parts\ExpressCorporation;
use common\models\parts\ItemInOrder;
use common\models\parts\Order;
use common\models\parts\OSSImage;
use supply\models\parts\trade\Wallet;
use Yii;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\base\Object;

class OrderRefund extends Object
{

    //申请类型
    CONST REFUND_TYPE_NEW = 0; //新申请未区分
    CONST REFUND_TYPE_BARTER = 1;//换货
    CONST REFUND_TYPE_REFUND = 2;//退款
    //申请单状态
    const REFUND_STATUS_NEW = 0;//新申请
    const REFUND_STATUS_AGREE = 1;//客服同意
    const REFUND_STATUS_REJECT = 2;//客服驳回
    const REFUND_STATUS_SUPPLY_AGREE = 3;//商家同意退换
    const REFUND_STATUS_BACK = 4;//客户已退回
    const REFUND_STATUS_RECEIVE_CONFIRM = 8; //新增退货流程：商家确认收到退货物品
    const REFUND_STATUS_REFUND_MONEY = 5;//已退款
    const REFUND_STATUS_SENDED = 6;//商家已发货
    const REFUND_STATUS_FINISHED = 7;//已完成
    const REFUND_STATUS_CANCEL = 9; //新增退换货流程：流程取消

    //图片类型
    const IMAGE_TYPE_SERVICE = 1;//客服上传图片
    const IMAGE_TYPE_CUSTOMER = 0;//客户上传图片




    //类型中文描术
    private $typeName = [
        self::REFUND_TYPE_NEW => '新申请',
        self::REFUND_TYPE_REFUND => '退货',
        self::REFUND_TYPE_BARTER => '换货',
    ];

    //获取状态描述
    private $statusName = [
        self::REFUND_STATUS_NEW => '新申请',
        self::REFUND_STATUS_AGREE => '客服同意',
        self::REFUND_STATUS_REJECT => '客服拒绝',
        self::REFUND_STATUS_SUPPLY_AGREE => '商家同意退换',
        self::REFUND_STATUS_BACK => '客户已退货',
        self::REFUND_STATUS_REFUND_MONEY => '商家已退款',
        self::REFUND_STATUS_SENDED => '商家已发货',
        self::REFUND_STATUS_FINISHED => '已完成',
        self::REFUND_STATUS_RECEIVE_CONFIRM => '退货商品确认',
        self::REFUND_STATUS_CANCEL => '取消',
    ];

    private $_cancelReason = '';

    public $id;
    public $code;//订单号
    public $type;//退货单类型
    public $plat_suggestion;//平台意见
    public $customer_express_corporation_id;//客户退换使用物流公司ID
    public $customer_shipping_code;//客户退货物流单号
    public $express_corporation_id;//代理商换货物流公司ID
    public $shipping_code;//换货物流单号
    public $plat_images;//临时图片
    //记录操作管理员ID
    public $admin_user_id=0;

    protected $AR;

    public function init()
    {
        if ($this->code) {
            if (!$this->AR = OrderRefundAR::findOne(['code' => $this->code])) throw new InvalidCallException();

            $this->id = $this->AR->id;
        } else {
            if (!$this->id || !$this->AR = OrderRefundAR::findOne($this->id)) throw new InvalidCallException();
        }
    }


    //获取订单号
    public function getCode()
    {
        return $this->AR->code;
    }

    //获取类型标识
    public function getType()
    {
        return $this->AR->type;
    }

    //获取平台意见
    public function getPlatSuggestion()
    {
        $comments = OrderRefundCommentHandler::getList($this);
        $tmp = array();
        foreach ($comments as $key => $var) {

            $account=new AdminAccount(['id'=>$var["admin_user_id"]]);

            $var['admin_account']=$account->getAccount();
            $var["admin_name"]=$account->getName();
            $var["post_time"]=date("Y-m-d H:i:s",$var["post_time"]);
            $var['images'] = $this->getImages(self::IMAGE_TYPE_SERVICE, $var['id']);
            $tmp[] = $var;
        }

        return $tmp;
    }

    //获取类型名称
    public function getTypeName()
    {
        return $this->typeName[$this->AR->type];
    }

    //获取订单状态标识
    public function getStatus()
    {
        return $this->AR->status;
    }

    //获取状态中文名称
    public function getStatusName()
    {
        return $this->statusName[$this->AR->status];
    }

    //获取客户信息
    public function getCustomer()
    {
        return new CustomUser(['id' => $this->AR->custom_user_id]);
    }

    //获取退换单创建时间
    public function getCreateTime()
    {
        return $this->AR->create_time > 0 ? date("Y-m-d H:i:s", $this->AR->create_time) : '';
    }

    //获取客服同意操作时间
    public function getServiceAgreeTime()
    {
        return $this->AR->service_agree_time > 0 ? date("Y-m-d H:i:s", $this->AR->service_agree_time) : '';
    }

    //获取客户拒绝时间
    public function getServiceRejectTime()
    {
        return $this->AR->service_reject_time > 0 ? date("Y-m-d H:i:s", $this->AR->service_reject_time) : '';
    }

    //获取商户同意时间
    public function getSupplyAgreeTime()
    {
        return $this->AR->supply_agree_time > 0 ? date("Y-m-d H:i:s", $this->AR->supply_agree_time) : '';
    }

    //客户退货时间
    public function getCustomerSendBackTime()
    {
        return $this->AR->customer_send_back_time > 0 ? date("Y-m-d H:i:s", $this->AR->customer_send_back_time) : '';

    }

    //客户退款时间
    public function getSupplyRefundMoneyTime()
    {

        return $this->AR->supply_refund_money_time > 0 ? date("Y-m-d H:i:s", $this->AR->supply_refund_money_time) : '';

    }

    public function getSupplyReceiveConfirmTime(){
        return $this->AR->supply_receive_confirm_time > 0 ? date('Y-m-d H:i:s', $this->AR->supply_receive_confirm_time) : '';
    }

    //客户退款时间
    public function getSupplyRefundSendTime()
    {
        return $this->AR->supply_refund_send_time > 0 ? date("Y-m-d H:i:s", $this->AR->supply_refund_send_time) : '';

    }

    //获取订单完成时间
    public function getFinishedTime()
    {
        return $this->AR->finished_time > 0 ? date("Y-m-d H:i:s", $this->AR->finished_time) : '';
    }

    public function getCancelTime(){
        return $this->AR->cancel_time > 0 ? date('Y-m-d H:i:s', $this->AR->cancel_time) : '';
    }

    //获取退换原因
    public function getReason()
    {
        return $this->AR->reason;
    }

    public function getCancelReason(){
        return $this->AR->cancel_reason;
    }

    //获取客户退货物流公司
    public function getCustomerShippingCompany()
    {
        if ($this->AR->customer_express_corporation_id > 0) {
            return (new ExpressCorporation(['id' => $this->AR->customer_express_corporation_id]))->getName();
        }
        return '';
    }

    //获取客户退货物流编号
    public function getCustomerShippingCode()
    {
        return $this->AR->customer_shipping_code;
    }

    //获取供应商发货物流编号
    public function getSupplyShippingCompany()
    {
        if ($this->AR->express_corporation_id > 0) {
            return (new ExpressCorporation(['id' => $this->AR->express_corporation_id]))->getName();
        }
        return '';
    }

    //获取代理商发货物流单号
    public function getSupplyShippingCode()
    {
        return $this->AR->shipping_code;
    }

    //获取退换商品信息
    public function getGoodsItem()
    {
        return new ItemInOrder(['id' => $this->AR->order_item_id]);
    }

    //获取退换数量
    public function getQuantity()
    {
        return $this->AR->quantity;
    }

    //获取退款总额
    public function getTotal()
    {
        return (float)$this->AR->sub_total;
    }

    public function setRefundRmb(float $rmb, $return = 'throw'){
        $order = $this->goodsItem->order;
        if($rmb <= 0 || $rmb > $this->total || $rmb > $order->totalFee - $this->applyRefundRmb){
            return Yii::$app->EC->callback($return, 'error refund rmb');
        } 
        if($this->refundRmb == $rmb)return 1;
        return Yii::$app->RQ->AR($this->AR)->update([
            'refund_rmb' => $rmb,
        ], $return);
    }

    public function getRefundRmb(){
        return (float)$this->AR->refund_rmb;
    }

    public function getApplyRefundRmb(){
        try{
            $orderId = Yii::$app->RQ->AR(new \common\ActiveRecord\OrderItemAR)->scalar([
                'select' => ['order_id'],
                'where' => ['id' => $this->AR->order_item_id],
            ]);
            $itemIds = Yii::$app->RQ->AR(new \common\ActiveRecord\OrderItemAR)->column([
                'select' => ['id'],
                'where' => ['order_id' => $orderId],
            ]);
            return (float)Yii::$app->RQ->AR(new OrderRefundAR)->sum([
                'where' => ['order_item_id' => $itemIds],
                'andWhere' => ['<>', 'id', $this->id],
            ], 'refund_rmb');
        }catch(\Exception $e){
            return 0;
        }
    }

    //获取图片路径
    public function getImages($imageType = 0, $comments_id = 0)
    {
        return Yii::$app->RQ->AR(new OrderRefundImgAR())->all([
            'select' => ['path', 'type'],
            'where' => [
                'order_refund_id' => $this->id,
                'type' => $imageType,
                'order_refund_comment_id' => $comments_id,
            ]
        ]);
    }

    //删除图片
    public function deleteImage($image_id)
    {
        return OrderRefundImgHandler::delete($this, $image_id, false);
    }

    //创建图片信息
    public function createImages(array $images = null, $image_type = 0, $comment_id = 0,$return="throw")
    {
        //检测图片类型
        if ($image_type != self::IMAGE_TYPE_CUSTOMER && $image_type != self::IMAGE_TYPE_SERVICE) {
            return false;
        }

        if (!$images) {
            return false;
        }
        try {
            foreach ($images as $image) {
                $url = new OSSImage([
                    'images' => ['filename' => $image],
                ]);
                if (!Yii::$app->RQ->AR(new OrderRefundImgAR())->insert([
                    'oss_upload_file_id' => current($url->getId()),
                    'order_refund_id' => $this->id,
                    'order_refund_comment_id' => $comment_id,
                    'type' => $image_type,
                    'path' => current($url->getPath())
                ], $return)
                ) {
                    return false;
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    //平台客户补充意见
    public function addSuggestion($comments, array $images = [],$admin_user_id=0)
    {
        if (empty($comments)) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        if (!$comment_id = OrderRefundCommentHandler::create($this, $comments,$admin_user_id)) {
            //写入意见信息
            $transaction->rollBack();
            return false;
        }
        if ($images) {
            //检测保存图片
            if (!$this->createImages($images, self::IMAGE_TYPE_SERVICE, $comment_id,false)) {
                $transaction->rollBack();
                return false;
            }
        }
        $transaction->commit();
        return true;
    }

    //设置退货状状态

    public function setStatus($status)
    {
        switch ($status) {
            case self::REFUND_STATUS_AGREE://同意，
            case self::REFUND_STATUS_REJECT://拒绝
                //仅允许对新状态，

                if ($this->AR->status != self::REFUND_STATUS_NEW) {
                    return false;
                }

                //检测退货单类型,两者选其一
                $data = [
                    'status' => $status,
                ];
                if ($status == self::REFUND_STATUS_AGREE) {
                    $data['service_agree_time'] = time();
                } else {
                    $data['service_reject_time'] = time();
                }

                //检测类型
                if ($status == self::REFUND_STATUS_AGREE) {
                    if ($this->type != self::REFUND_TYPE_BARTER && $this->type != self::REFUND_TYPE_REFUND) {
                        return false;
                    }
                    //配置数据
                    $data['type'] = $this->type;
                }

                $transaction = Yii::$app->db->beginTransaction();

                //提交客服意见
                if (!empty($this->plat_suggestion)) {
                    if (!$this->addSuggestion($this->plat_suggestion, $this->plat_images,$this->admin_user_id)) {
                        $transaction->rollBack();
                        return false;
                    }
                }

            

                if (!$this->updateStatus($data)) {
                    $transaction->rollBack();
                    return false;
                }
                if($status==self::REFUND_STATUS_REJECT){
                    //更改退换单
                    if (!$this->getGoodsItem()->getOrder()->setRefundStatus(Order::REFUND_STATUS_NO)) {
                        $transaction->rollBack();
                        return false;
                    }
                }


                $transaction->commit();
                return true;
            case self::REFUND_STATUS_SUPPLY_AGREE:
                //商户同意退换,仅可对平台已同意的订单进行处理
                if ($this->AR->status != self::REFUND_STATUS_AGREE) {
                    return false;
                }
                //处理数据
                $data = [
                    'status' => $status,
                    'supply_agree_time' => time(),
                ];
                return $this->updateStatus($data);

            case self::REFUND_STATUS_BACK://处理客户退回
                //客户仅允许对商家已同意退换的订单处理退回操作
                if ($this->AR->status != self::REFUND_STATUS_SUPPLY_AGREE) {
                    return false;
                }
                //用户必须选择使用快递公司，及物流编号
                if (empty($this->customer_express_corporation_id) || empty($this->customer_shipping_code)) {
                    return false;
                }
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $data = [
                        'status' => $status,
                        'customer_send_back_time' => time(),
                        'customer_express_corporation_id' => $this->customer_express_corporation_id,
                        'customer_shipping_code' => $this->customer_shipping_code,
                    ];
                    if(!$this->updateStatus($data))throw new \Exception;
                    if($this->admin_user_id != 0){
                        Yii::$app->RQ->AR(new OrderRefundAdminSendLogAR)->insert([
                            'order_refund_id' => $this->AR->id,
                            'admin_user_id' => $this->admin_user_id,
                            'target_status' => $status,
                            'express_corporation_id' => $this->customer_express_corporation_id,
                            'shipping_code' => $this->customer_shipping_code,
                            'operate_datetime' => date('Y-m-d H:i:s'),
                            'operate_unixtime' => time(),
                        ]);
                    }
                    $transaction->commit();
                    return true;
                }catch(\Exception $e){
                    $transaction->rollBack();
                    return false;
                }

            //新增退货流程：商家确认收到退货商品
            case self::REFUND_STATUS_RECEIVE_CONFIRM:
                if($this->AR->status != self::REFUND_STATUS_BACK || $this->AR->type != self::REFUND_TYPE_REFUND){
                    return false;
                }
                $data = [
                    'status' => $status,
                    'supply_receive_confirm_time' => time(),
                ];
                return $this->updateStatus($data);

            //处理退款流程
            case self::REFUND_STATUS_REFUND_MONEY:

                //状态须是同意，类型为退款，
                if ($this->AR->status != self::REFUND_STATUS_RECEIVE_CONFIRM || $this->AR->type != self::REFUND_TYPE_REFUND) {
                    return false;
                }

                $transaction = Yii::$app->db->beginTransaction();
                //处理商户退款
                //收款人钱包
                $receiverWallet = new \custom\models\parts\trade\Wallet([
                    'userId' => $this->getCustomer()->id,
                    'receiveType' => \custom\models\parts\trade\Wallet::RECEIVE_SUPPLY_REFUND,
                ]);
                //执行付款操作
                if (!(new Wallet(['userId' => $this->getGoodsItem()->getSupplierId()]))->pay($this, $receiverWallet)) {
                    $transaction->rollBack();
                    return false;
                }

                //更新状态，及时间
                $data = [
                    'status' => $status,
                    'supply_refund_money_time' => time(),
                ];
                if (!$this->updateStatus($data)) {
                    $transaction->rollBack();
                    return false;
                }

                //更改退换单
                if (!$this->getGoodsItem()->getOrder()->setRefundStatus(Order::REFUND_STATUS_NO)) {
                    $transaction->rollBack();
                    return false;
                }
                //设置原订单退款金额
                if (!$this->getGoodsItem()->getOrder()->setRefundRmb($this->AR->refund_rmb)) {
                    $transaction->rollBack();
                    return false;
                }


                $transaction->commit();
                return true;

            case self::REFUND_STATUS_SENDED://供应商发货
                //换货仅供已发货的用户退货款有效

                if ($this->AR->status != self::REFUND_STATUS_BACK || $this->AR->type != self::REFUND_TYPE_BARTER) {
                    return false;
                }
                //需供应商填写单号及物流公司
                if (empty($this->express_corporation_id) || empty($this->shipping_code)) {
                    return false;
                }
                $data = [
                    'status' => $status,
                    'supply_refund_send_time' => time(),
                    'express_corporation_id' => $this->express_corporation_id,
                    'shipping_code' => $this->shipping_code,
                ];

                return $this->updateStatus($data);

            case self::REFUND_STATUS_FINISHED://结束换货订单
                //仅允许结束供应商已发货的订单
                if ($this->AR->status != self::REFUND_STATUS_SENDED) {
                    return false;
                }
                $data = [
                    'status' => $status,
                    'finished_time' => time(),
                ];
                $transaction = Yii::$app->db->beginTransaction();
                try {

                    if (!$this->updateStatus($data)) {
                        $transaction->rollBack();
                        return false;
                    }
                    //更改退换单
                    if (!$this->getGoodsItem()->getOrder()->setRefundStatus(Order::REFUND_STATUS_NO)) {
                        $transaction->rollBack();
                        return false;
                    }

                    $transaction->commit();
                    return true;
                } catch (Exception $e) {
                    $transaction->rollBack();
                    return false;
                }

            case self::REFUND_STATUS_CANCEL:
                if(!in_array($this->AR->status, [
                    self::REFUND_STATUS_AGREE,
                    self::REFUND_STATUS_SUPPLY_AGREE,
                    self::REFUND_STATUS_BACK,
                    self::REFUND_STATUS_SENDED,
                    self::REFUND_STATUS_RECEIVE_CONFIRM,
                ])){
                    return false;
                }
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    if(!$this->updateStatus([
                        'cancel_time' => time(),
                        'cancel_reason' => $this->_cancelReason,
                        'status' => $status,
                    ]))throw new \Exception;
                    if(!$this->getGoodsItem()->getOrder()->setRefundStatus(Order::REFUND_STATUS_NO))throw new \Exception;
                    $transaction->commit();
                    return true;
                }catch(\Exception $e){
                    $transaction->rollBack();
                    return false;
                }
            
            default:
                return false;

        }
        //无任何操作，返回
        return false;
    }

    public function cancel(string $reason, $return = 'throw'){
        if(mb_strlen($reason, Yii::$app->charset) > 255)return Yii::$app->EC->callback('string is too long', $return);
        $this->_cancelReason = $reason;
        return $this->setStatus(self::REFUND_STATUS_CANCEL);
    }


    //处理更新数据，并记录日志
    private function updateStatus(array $data = null)
    {
        if (!$data) return false;
        return Yii::$app->RQ->AR($this->AR)->update($data,false);
    }
}
