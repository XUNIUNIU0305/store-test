<?php
namespace common\models\parts;

use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserTradeOrderAR;
use common\ActiveRecord\ExpressCorporationAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderCustomizationAR;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\OrderRefundAR;
use common\components\amqp\Message;
use common\models\parts\order\OrderRefund;
use common\models\parts\supply\SupplyUser;
use common\models\RapidQuery;
use console\models\AmqpTask\DeleteOrderRecordTask;
use console\models\AmqpTask\OrderRecordTask;
use custom\models\parts\trade\Trade;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use custom\modules\temp\models\GroupbuyExpiredConfig;
use Yii;
use yii\base\InvalidCallException;
use yii\base\Object;
use common\models\parts\coupon\CouponRecord;

class Order extends Object
{

    /**
     * 订单状态：
     * 未支付
     * 未发货
     * 已发货
     * 已确认收货
     * 已取消
     * 已关闭
     */
    const STATUS_UNPAID = 0;
    const STATUS_UNDELIVER = 1;
    const STATUS_DELIVERED = 2;
    const STATUS_CONFIRMED = 3;
    const STATUS_CANCELED = 4;
    const STATUS_CLOSED = 5;


    //定制订单
    const CUSTOM_STATUS_NO  = 0;    //正常订单
    const CUSTOM_STATUS_IS = 1;     //定制订单


    //退换状态
    const REFUND_STATUS_YES = 1;//退换中
    const REFUND_STATUS_NO = 0;//结束，或者未退换

    //订单表主键
    public $id;
    //快递公司:int
    public $expressCorporation;
    //快递单号:string
    public $expressNumber;
    //订单号
    public $orderNumber;

    protected $AR;

    public function init()
    {
        if ($this->orderNumber && is_null($this->id)) {
            if ($AR = OrderAR::findOne(['order_number' => $this->orderNumber])) {
                $this->id = $AR->id;
            }
        }
        if (!$this->id ||
            !$this->AR = OrderAR::findOne($this->id)
        ) throw new InvalidCallException;
    }

    /**
     * 获取订单号
     *
     * @return int
     */
    public function getOrderNo()
    {
        return $this->AR->order_number;
    }

    /**
     * 获取购买用户ID
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->AR->custom_user_id;
    }

    /**
     * 获取购买用户账号
     *
     * @return int
     */
    public function getCustomerAccount()
    {
        return $this->getCustomUser()->account;
    }

    private $customUser;
    /**
     * 获取订单用户
     */
    public function getCustomUser()
    {
        if($this->customUser === null){
            $this->customUser = CustomUserAR::findOne($this->getCustomerId());
        }
        return $this->customUser;
    }

    private $_trade = false;
    //获取交易单
    public function getTrade(){
        if($this->_trade === false){
            if($data=Yii::$app->RQ->AR(new CustomUserTradeOrderAR())->one([
                'select'=>['custom_user_trade_id'],
                'where'=>['order_id'=>$this->id],
                'orderBy' => ['id' => SORT_DESC],
            ])){
                $this->_trade = new Trade(['id'=>$data['custom_user_trade_id']]);
            }
        }
        return $this->_trade;
    }
    /**
     * 获取销售用户ID
     *
     * @return int
     */
    public function getSupplierId()
    {
        return $this->AR->supply_user_id;
    }

    private $supplier;
    //获取供应商
    public function getSupplier(){
        if($this->supplier === null){
            $this->supplier = new SupplyUser(['id' => $this->getSupplierId()]);
        }
        return $this->supplier;
    }

    /**
     * 获取下单时的店铺名称
     *
     * @return string
     */
    public function getStoreName()
    {
        return $this->AR->store_name;
    }

    /**
     * 获取品牌名
     * @return mixed
     */
    public function getBrandName()
    {
        return $this->getSupplier()->getBrandName();
    }

    /**
     * 获取订单总金额
     *
     * @return float
     */
    public function getTotalFee()
    {
        return (float)$this->AR->total_fee;
    }

    public function getItemsFee(){
        return (float)$this->AR->items_fee;
    }

    public function getCouponRmb(){
        return (float)$this->AR->coupon_rmb;
    }

    public function getTicket(){
        if($this->AR->coupon_record_id){
            return new CouponRecord([
                'id' => $this->AR->coupon_record_id,
            ]);
        }else{
            return false;
        }
    }

    public function getRefundRmb(){
        return (float)$this->AR->refund_rmb;
    }

    public function useCoupon(CouponRecord $ticket, $return = 'throw'){
        if($this->status != self::STATUS_UNPAID)return Yii::$app->EC->callback($return, 'only unpaid order can use coupon');
        if($ticket->status != CouponRecord::STATUS_ACTIVE)return Yii::$app->EC->callback($return, 'coupon status error');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($ticket->setUsed($this)){
                Yii::$app->RQ->AR($this->AR)->update([
                    'total_fee' => $this->totalFee - $ticket->coupon->price,
                    'coupon_record_id' => $ticket->id,
                    'coupon_rmb' => $ticket->coupon->price,
                ]);
            }else{
                throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    /**
     * 获取订单当前状态
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->AR->status;
    }

    /**
     * 获取快递公司
     *
     * @param boolean $string 返回ID或文字
     *
     * @return int|string
     */
    public function getExpressCorp($string = false)
    {
        if ($this->status == self::STATUS_UNPAID ||
            $this->status == self::STATUS_UNDELIVER ||
            $this->status == self::STATUS_CANCELED
        ) return false;
        if ($string) {
            return ExpressCorporationAR::findOne($this->expressCorp)->name;
        } else {
            return $this->AR->express_corporation_id;
        }
    }

    /**
     * 获取快递公司名称
     *
     * @return string
     */
    public function getExpressCorpName()
    {
        return $this->getExpressCorp(true);
    }

    /**
     * 获取快递单号
     *
     * @return string|false
     */
    public function getExpressNo()
    {
        if ($this->status == self::STATUS_UNPAID ||
            $this->status == self::STATUS_UNDELIVER
        ) return false;
        return $this->AR->express_number;
    }

    /**
     * 获取收货人名称
     *
     * @return string
     */
    public function getConsignee()
    {
        return $this->AR->receive_consignee;
    }

    /**
     * 获取完整的收货地址
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->AR->receive_address;
    }

    /**
     * 获取收货的手机号码
     *
     * @return int
     */
    public function getMobile()
    {
        return $this->AR->receive_mobile;
    }


    /**
     * 获取收货的邮政编码
     *
     * @return int
     */
    public function getPostalCode()
    {
        return $this->AR->receive_postal_code;
    }

    /**
     * 获取订单创建时间
     *
     * @param boolean $unixTime 时间or时间戳
     *
     * @return string|int
     */
    public function getCreateTime($unixTime = false)
    {
        return $unixTime ? $this->AR->create_unixtime : ($this->AR->create_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->create_datetime);
    }

    /**
     * 获取订单支付时间
     *
     * @param boolean $unixTime 时间or时间戳
     *
     * @return string|int
     */
    public function getPayTime($unixTime = false)
    {
        if ($this->status == self::STATUS_UNPAID) return false;
        return $unixTime ? $this->AR->pay_unixtime : ($this->AR->pay_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->pay_datetime);
    }

    /**
     * 获取订单发货时间
     *
     * @param boolean $unixTime 时间or时间戳
     *
     * @return string|int
     */
    public function getDeliverTime($unixTime = false)
    {
        if ($this->status == self::STATUS_UNPAID ||
            $this->status == self::STATUS_UNDELIVER
        ) return false;
        return $unixTime ? $this->AR->deliver_unixtime : ($this->AR->deliver_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->deliver_datetime);
    }

    /**
     * 获取订单收货时间
     *
     * @param boolean $unixTime 时间or时间戳
     *
     * @return string|int
     */
    public function getReceiveTime($unixTime = false)
    {
        if ($this->status != self::STATUS_CONFIRMED && $this->status != self::STATUS_CLOSED) return false;
        return $unixTime ? $this->AR->receive_unixtime : ($this->AR->receive_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->receive_datetime);
    }

    public function getCancelTime($unixTime = false)
    {
        if ($this->status != self::STATUS_CANCELED) return false;
        return $unixTime ? $this->AR->cancel_unixtime : ($this->AR->cancel_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->cancel_datetime);
    }

    public function getCloseTime($unixTime = false)
    {
        if ($this->status != self::STATUS_CLOSED) return false;
        return $unixTime ? $this->AR->close_unixtime : ($this->AR->close_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->close_datetime);
    }

    /**
     * 获取订单物品信息
     *
     * @return array
     */
    public function getItems()
    {
        $itemsId = (new RapidQuery(new OrderItemAR))->column([
            'select' => ['id'],
            'where' => ['order_id' => $this->id],
        ]);
        if (!$itemsId) return false;
        return array_map(function ($itemId) {
            return new ItemInOrder(['id' => $itemId]);
        }, $itemsId);
    }

    /**
     * 设置订单状态至下一步
     *
     * @return boolean
     */
    public function goNextStep()
    {
        if (($step = array_search($this->status, self::getStatuses())) >= array_search(self::STATUS_CONFIRMED, self::getStatuses())) return false;
        $step += 1;
        return $this->setStatus(self::getStatuses()[$step]);
    }

    //临时功能：拼团；记录商品所在订单信息、增加拼团商品已购买数量
    private function groupbuyRecord(){
        $productsInOrder = array_map(function($item){
            return [
                'id' => $item->getItem()->getProductId(),
                'quantity' => $item->count,
            ];
        }, $this->items);
        $quantityOfProducts = [];
        foreach($productsInOrder as $product){
            if(array_key_exists($product['id'], $quantityOfProducts)){
                $quantityOfProducts[$product['id']] += $product['quantity'];
            }else{
                $quantityOfProducts[$product['id']] = $product['quantity'];
            }
        }
        $productsInGroupbuy = Yii::$app->RQ->AR(new \common\ActiveRecord\ActivityGroupbuyAR)->column([
            'select' => ['product_id'],
            'where' => [
                'product_id' => array_keys($quantityOfProducts),
                'status' => 1,
            ],
        ], []);
        if($productsInGroupbuy){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                Yii::$app->RQ->AR(new \common\ActiveRecord\ActivityGroupbuyOrderAR)->insert([
                    'order_id' => $this->id,
                    'custom_user_id' => $this->customerId,
                    'status' => 0,
                ]);
                foreach($productsInGroupbuy as $productId){
                    foreach(\common\ActiveRecord\ActivityGroupbuyAR::findAll(['product_id' => $productId]) as $groupbuyAR){
                        if(!$groupbuyAR->updateCounters(['achieve_sales' => $quantityOfProducts[$productId]]))throw new \Exception;
                    }
                }
                $transaction->commit();
                return true;
            }catch(\Exception $e){
                $transaction->rollBack();
                return false;
            }
        }else{
            return true;
        }
    }

    /**
     * 设置订单状态
     *
     * 设置订单状态设置为已发货时需要提供快递公司$expressCorporation和快递单号$expressNumber
     *
     * @return boolean
     */
    public function setStatus($status, $force = false, $return = 'throw')
    {
        if (!in_array($status, self::getStatuses())) return Yii::$app->EC->callback($return, 'undefined order status');
        if (!$force) {
            if ((array_search($this->status, self::getStatuses()) + 1) != array_search($status, self::getStatuses())) return Yii::$app->EC->callback($return, 'can not set the status: ' . $status);
        }
        switch ($status) {
            case self::STATUS_UNDELIVER:
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$this->increaseProductPaid()) throw new \Exception;
                    $updateResult = (new RapidQuery($this->AR))->update([
                        'status' => self::STATUS_UNDELIVER,
                        'pay_datetime' => Yii::$app->time->fullDate,
                        'pay_unixtime' => Yii::$app->time->unixTime,
                    ]);
                    if (!$updateResult) throw new \Exception;

                    //临时功能：领水活动；确认用户已付款
                    if(\common\ActiveRecord\CustomUserActivityLimitAR::findOne(['order_id' => $this->id])){
                        \custom\models\parts\temp\OrderLimit\ActivityLimit::setPaid($this);
                    }
                    //临时功能结束

                    //临时功能：拼团；付款后记录信息
                    if((new GroupbuyExpiredConfig())->isValid($this->getCreateTime(true))) {
                        if(!$this->groupbuyRecord())throw new \Exception;
                    }
                    //临时功能结束

                    $this->recordBusiness();
                    if($this->AR->is_customization){
                        Yii::$app->RQ->AR(new OrderCustomizationAR)->insert(['order_number' => $this->getOrderNo()]);
                    }
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return Yii::$app->EC->callback($return, 'set order status to undeliver failed');
                }
                $this->recordCustomAndProduct();
                return true;
                break;

            case self::STATUS_DELIVERED:
                if (!$this->expressCorporation || !$this->expressNumber) return Yii::$app->EC->callback($return, 'unavailable express corporation or express number');
                if (!ExpressCorporationAR::findOne($this->expressCorporation)) return Yii::$app->EC->callback($return, 'unknown express corporation');
                return Yii::$app->RQ->AR($this->AR)->update([
                    'status' => self::STATUS_DELIVERED,
                    'deliver_datetime' => Yii::$app->time->fullDate,
                    'deliver_unixtime' => Yii::$app->time->unixTime,
                    'express_corporation_id' => $this->expressCorporation,
                    'express_number' => $this->expressNumber,
                ], $return);
                break;

            case self::STATUS_CONFIRMED:
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $adminWallet = new \admin\models\parts\trade\Wallet;
                    $supplierWallet = new \supply\models\parts\trade\Wallet([
                        'userId' => $this->supplierId,
                        'receiveType' => \supply\models\parts\trade\Wallet::RECEIVE_ORDER_CONFIRMED,
                    ]);
                    if (!$this->increaseProductSales()) throw new \Exception;
                    $queryResult = (new RapidQuery($this->AR))->update([
                        'status' => self::STATUS_CONFIRMED,
                        'receive_datetime' => Yii::$app->time->fullDate,
                        'receive_unixtime' => Yii::$app->time->unixTime,
                    ]);
                    if (!$queryResult) throw new \Exception;
                    if (!$adminWallet->pay($this, $supplierWallet)) throw new \Exception;
                    $transaction->commit();
                    return true;
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return Yii::$app->EC->callback($return, 'set order status to confirmed failed');
                }
                break;

            case self::STATUS_CLOSED:
                return Yii::$app->RQ->AR($this->AR)->update([
                    'status' => self::STATUS_CLOSED,
                    'close_datetime' => Yii::$app->time->fullDate,
                    'close_unixtime' => Yii::$app->time->unixTime,
                ], $return);
                break;

            case self::STATUS_CANCELED:
                if (!$force) return Yii::$app->EC->callback($return, 'you must set the canceled status forcely');
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($this->items as $item) {
                        $item->item->increaseStock($item->count);
                    }
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => self::STATUS_CANCELED,
                        'cancel_datetime' => Yii::$app->time->fullDate,
                        'cancel_unixtime' => Yii::$app->time->unixTime,
                    ]);
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return Yii::$app->EC->callback($return, 'set order status to canceled failed');
                }
                $this->deleteCustomAndProductRecord();
                return true;
                break;

            default:
                return false;
        }
    }

    protected function recordCustomAndProduct()
    {
        try {
            $recordTask = new OrderRecordTask(['order_id' => $this->id]);
            $message = new Message($recordTask);
            Yii::$app->amqp->publish($message);
        } catch (\Exception $e) {
        }
    }

    protected function deleteCustomAndProductRecord()
    {
        try {
            $deleteTask = new DeleteOrderRecordTask(['order_id' => $this->id]);
            $message = new Message($deleteTask);
            Yii::$app->amqp->publish($message);
        } catch (\Exception $e) {
        }
    }

    protected function recordBusiness($return = 'throw'){
        $areaId = CustomUserAR::findOne($this->getCustomerId())->business_area_id;
        $fifthArea = new \business\models\parts\Area(['id' => $areaId]);
        $leader = $fifthArea->leader;
        $commissar = $fifthArea->commissar;
        $insertData = [
            'order_id' => $this->id,
            'fifth_area_id' => $fifthArea->id,
            'fifth_leader_role_id' => $leader ? $leader->id : 0,
            'fifth_commissar_role_id' => $commissar ? $commissar->id : 0,
        ];
        $otherArea = [
            'quaternary' => ($quaternaryArea = $fifthArea->parent),
            'tertiary' => ($tertiaryArea = $quaternaryArea->parent),
            'secondary' => ($secondaryArea = $tertiaryArea->parent),
            'top' => $secondaryArea->parent,
        ];
        foreach($otherArea as $level => $area){
            $insertData[$level . '_area_id'] = $area->id;
            $role = $area->leader;
            $insertData[$level . '_role_id'] = $role ? $role->id : 0;
        }
        return Yii::$app->RQ->AR(new \common\ActiveRecord\OrderBusinessRecordAR)->insert($insertData, $return);
    }

    /**
     * 增加商品销量
     *
     * @return boolean
     */
    protected function increaseProductSales()
    {
        $items = $this->items;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($items as $item) {
                if (!$item->item->productObj->increaseSales($item->count)) throw new \Exception;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    //mod:Jiangyi date:2017/04/17  desc:修改退换状态标识
    public function setRefundStatus($status)
    {
        //当取消原订单退换状态时，需检测总个订单
        if ($status == self::REFUND_STATUS_NO) {
            $items = $this->getItems();
            foreach ($items as $item) {
                if ($item->refundExists()) {
                    //如果存在退换，直接返回true
                    return true;
                }
            }
        }

        $this->AR->refund_status = $status;
        return $this->AR->save() ? true : false;
    }

    //mod Jiangyi date:2017/04/18 desc 设置更新退款金额

    public function setRefundRmb($rmb)
    {
        if ($rmb <= 0) return false;
        $this->AR->refund_rmb += $rmb;
        return $this->AR->save() ? true : false;
    }

    /**
     * 增加商品已付款数量
     *
     * @return boolean
     */
    protected function increaseProductPaid()
    {
        $items = $this->items;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($items as $item) {
                if (!$item->item->productObj->increasePaid($item->count)) throw new \Exception;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 获取订单状态列表
     *
     * 必须根据订单生命周期顺序填写状态
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_UNPAID,
            self::STATUS_UNDELIVER,
            self::STATUS_DELIVERED,
            self::STATUS_CONFIRMED,
            self::STATUS_CLOSED,
            self::STATUS_CANCELED,
        ];
    }


    /**
     *====================================================
     * 获取驳回的钱数
     * @return float|int
     * @author shuang.li
     *====================================================
     */
    public function getRejectPrice(){
        $rejectPrice = 0;
        if ($refundIds = $this->getRefundItemIds(OrderRefund::REFUND_STATUS_REJECT))
        {
            foreach ($refundIds as $id)
            {
                $rejectPrice += (new OrderRefund(['id' => $id]))->getTotal();
            }
        }
        return $rejectPrice;

    }

    /**
     *====================================================
     * 获取退换货钱数
     * @return float|int
     * @author shuang.li
     *====================================================
     */
    public function getRefundPrice(){
        $returnChangePrice = 0;
        if ($returnChangeId =  $this->getRefundItemIds([OrderRefund::REFUND_STATUS_REFUND_MONEY,OrderRefund::REFUND_STATUS_FINISHED])){
            foreach ($returnChangeId as $id)
            {
                $returnChangePrice += (new OrderRefund(['id' => $id]))->getTotal();
            }
        }
        return $returnChangePrice;
    }

    /**
     *====================================================
     * 获取不同状态下的退款id
     * @param $status
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    protected function getRefundItemIds($status){
        //获取订单下的item 主键id
        $itemIds = Yii::$app->RQ->AR(new OrderItemAR())->column([
            'select' => ['id'],
            'where' => ['order_id' => $this->id],
        ]);
        return  Yii::$app->RQ->AR(new OrderRefundAR())->column([
            'select'=>['id'],
            'where' => [
                'order_item_id' => $itemIds,
                'status'=>$status,
            ]
        ]);
    }


    /**
     *====================================================
     * 设置重新订阅
     * @param $subscribeNum
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setRestartSubscribe($subscribeNum){
        $this->AR->is_subscribe = 0;
        $this->AR->subscribe_num =$subscribeNum ;
        return $this->AR->update();
    }

    /**
     *====================================================
     * 订阅
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setSubscribe(){
        $this->AR->is_subscribe = 1;
        return $this->AR->update();
    }


    private $customization;
    /**
     * 定制订单
     * @return bool|OrderCustomization
     */
    public function getCustomization()
    {
        if($this->customization === null){
            if($this->AR->is_customization === self::CUSTOM_STATUS_NO || $this->getStatus() === self::STATUS_UNPAID)
                return false;
            else
                $this->customization = new OrderCustomization(['order_number' => $this->getOrderNo()]);
        }
        return $this->customization;
    }

    /**
     * 获取支付方式
     * @return mixed
     */
    public function getPayMethod()
    {
        if ($trade = $this->getTrade()){
            return $trade->getPaymentMethod();
        }else{
            return Yii::$app->RQ->AR(new ActivityGpubsGroupDetailAR)->scalar([
                'select' => ['pay_method'],
                'where' => [
                    'order_number' => $this->getOrderNo(),
                ],
            ]) ? : '';
        }
    }
}
