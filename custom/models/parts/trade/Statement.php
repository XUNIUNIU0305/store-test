<?php
namespace custom\models\parts\trade;
use common\ActiveRecord\AdminPayMembraneOrderAR;
use common\models\parts\custom\CustomUser;
use common\ActiveRecord\SupplyUserPayRefundAR;
use common\ActiveRecord\AdminPayLogAR;
use common\models\parts\MembraneOrder;
use common\models\parts\Order;
use common\models\parts\trade\StatementAbstract;
use common\ActiveRecord\CustomUserStatementAR;
use common\traits\CheckReturnTrait;
use custom\modules\temp\models\parts\Zodiac;
use yii\base\InvalidCallException;
use common\models\parts\trade\WalletAbstract;
use Yii;

class Statement extends StatementAbstract
{

    use CheckReturnTrait;

    //custom_user_statement表主键
    public $id;

    protected $AR;

    public function init()
    {
        if (!$this->id || (!$this->AR = CustomUserStatementAR::findOne($this->id)))
        {
            throw new InvalidCallException;
        }
    }

    /**
     * 获取变更类型
     * @return int
     */
    public function getAlterationType()
    {
        return $this->AR->alteration_type;
    }

    /**
     * 获取相关记录ID
     * @return int
     */
    public function getLogId()
    {
        return $this->AR->corresponding_log_id;
    }

    /**
     * 获取变更金额
     * @return float
     */
    public function getAlterationAmount()
    {
        return $this->AR->alteration_amount;
    }

    /**
     * 获取变更前余额
     * @return float
     */
    public function getRMBBefore()
    {
        return $this->AR->rmb_before;
    }

    /**
     * 获取变更后余额
     * @return float
     */
    public function getRMBAfter()
    {
        return $this->AR->rmb_after;
    }

    /**
     * 获取变更时间
     * @param boolean $unixTime 是否返回时间戳
     * @return string|int
     */
    public function getAlterationTime($unixTime = false)
    {
        return $unixTime ? $this->AR->alteration_unixtime : $this->AR->alteration_datetime;
    }


    public function getAccount(){
        $customUser = new CustomUser([
            'id' => $this->AR->custom_user_id,
        ]);
         return $customUser->getAccount();
    }


    /**
     *====================================================
     * 获取交易内容
     * @return array
     * @author shuang.li
     * @Date:
     *====================================================
     */
    public function getContent()
    {
        $data = [];
        switch ($this->AR->alteration_type)
        {
            //入账有两种情况 取消订单和充值
        case CustomUserStatementAR::ALTER_TYPE_RECEIVE:
            //实例化入账对象
            $receiveLog = new ReceiveLog([
                'id' => $this->AR->corresponding_log_id,
            ]);
            //获取对应支付id
            $logId = $receiveLog->getLogId();
            //获取入账类型
            $receiveType = $receiveLog->getReceiveType();

            switch ($receiveType)
            {
                //充值
            case 1:
                $rechargeLog = new RechargeLog([
                    'id' => $logId
                ]);
                $amount = $rechargeLog->getRechargeAmount();
                $tradeNo = strval($rechargeLog->getTradeNo());
                $rechargeMothod = $rechargeLog->getRechargeMethod();
                switch ($rechargeMothod) {
                case RechargeLog::RECHANGE_METHOD_ALIPAY:
                    $method = '支付宝';
                    break;
                case RechargeLog::RECHANGE_METHOD_WECHAT:
                    $method = '微信';
                    break;

                case RechargeLog::RECHANGE_METHOD_GATEWAY_PERSON:
                    $method = '南行-个人网关';
                    break;

                case RechargeLog::RECHANGE_METHOD_GATEWAY_CORP:
                    $method = '南行-企业网关';
                    break;
                
                case RechargeLog::RECHANGE_METHOD_ABCHINA:
                    $method = '农行-网银';
                    break;
                }
                $data['type'] = 'recharge';
                $data['message'] = $data['title'] = $method.'充值：' . $amount . '元,交易单号：' . $tradeNo;
                break;
                //取消订单
            case 4:
                $adminPayOrder = new AdminPayOrder([
                    'log_id' => $logId,
                ]);
                $order = new Order([
                    'id' => $adminPayOrder->getOrderId()
                ]);
                $orderNo =  $order->getOrderNo();
                $data['type'] = 'cancel_order';
                $data['title'] = '您的订单号：' .$orderNo . '已被取消';
                $data['message'] = "您的订单号：<a href='/account/order/detail?no=" . $orderNo . "' target='_blank'>" . $orderNo . '</a>已被取消';
                break;
                //退货入账
                //  你的退货售后单号：554456，已完成操作，请注意查看您的余额账户
            case 5:
                $code = $receiveLog->getRefund()->getCode();
                $data['type'] = 'return';
                $data['title'] = '你的退货售后单号：' . $code. '已完成操作，请注意查看您的余额账户';
                $data['message'] = "你的退货售后单号：<a href='/account/refund/detail?refund_code=" . $code . "' target='_blank'>" . $code . '</a>已完成操作，请注意查看您的余额账户';
                break;
                //扫码门店加盟入账
            case 7:
                $amount = $receiveLog->getReceiveAmount();
                $data['type'] = 'promoter';
                $data['message'] = $data['title'] =  '邀请门店奖励金：' . $amount . '元';
                break;
                //膜订单退款
            case 8:
                $log = AdminPayMembraneOrderAR::findOne(['admin_pay_log_id' => $logId]);
                $order = new MembraneOrder(['id' => $log->membrane_order_id]);
                $orderNo = $order->no;
                $data['type'] = 'cancel_order';
                $data['title'] = '您的订单号：' .$orderNo . '已被取消';
                $data['message'] = "您的订单号：" . $orderNo . '已被取消';
                break;
                //团购返现
            case 13:
                $data['type'] = 'groupbuy';
                $data['title'] = '拼团活动返现';
                $data['message'] = '拼团活动返现';
                break;

            case WalletAbstract::RECEIVE_VOUCHER:
                $data = [
                    'type' => 'voucher',
                    'title' => '代金券充值',
                    'message' => '代金券充值'
                ];
                break;

            case WalletAbstract::RECEIVE_NON_TRANSACTION:
                $ticketId = Yii::$app->RQ->AR(new \common\ActiveRecord\AdminPayNonTransactionAR)->scalar([
                    'select' => ['non_transaction_deposit_and_draw_id'],
                    'where' => [
                        'admin_pay_log_id' => $logId,
                    ],
                ]);
                $brief = Yii::$app->RQ->AR(new \common\ActiveRecord\NonTransactionDepositAndDrawAR)->scalar([
                    'select' => ['operate_brief'],
                    'where' => [
                        'id' => $ticketId,
                    ],
                ]);
                $data = [
                    'type' => 'non_transaction_deposit',
                    'title' => $brief,
                    'message' => $brief,
                ];
                break;

            case WalletAbstract::RECEIVE_GPUBS_ORDER:
                $orderId = Yii::$app->RQ->AR(new \common\ActiveRecord\AdminPayGpubsOrderAR)->scalar([
                    'select' => ['gpubs_group_detail_id'],
                    'where' => [
                        'admin_pay_log_id' => $logId,
                    ],
                ]);
                $brief = Yii::$app->RQ->AR(new \common\ActiveRecord\ActivityGpubsGroupDetailAR)->scalar([
                    'select' => ['detail_number'],
                    'where' => [
                        'id' => $orderId,
                    ],
                ]);
                $data = [
                    'type' => 'gpubs_order_refund',
                    'title' => '拼购参团失败',
                    'message' => '拼购参团失败，订单号：' . $brief,
                ];
                break;
            }

            break;
            //出账 购物消费 星座消费
        case CustomUserStatementAR::ALTER_TYPE_PAY:
            $ticketId = Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserPayNonTransactionAR)->scalar([
                'select' => ['non_transaction_deposit_and_draw_id'],
                'where' => [
                    'custom_user_pay_log_id' => $this->AR->corresponding_log_id,
                ],
            ]);
            if($ticketId){
                $brief = Yii::$app->RQ->AR(new \common\ActiveRecord\NonTransactionDepositAndDrawAR)->scalar([
                    'select' => ['operate_brief'],
                    'where' => [
                        'id' => $ticketId,
                    ],
                ]);
                $data['type'] = 'non_transaction_draw';
                $data['title'] = $brief;
                $data['message'] = $brief;
                break;
            }
            $payTrade = new PayTrade([
                'log_id' => $this->AR->corresponding_log_id,
            ]);
            //获取trade 对象
            $trade = new Trade([
                'id' => $payTrade->getTradeId(),
            ]);
            //trade对象的交易类型
            $tradeType = $trade->getType();
            switch ($tradeType)
            {
                //订单
            case 1:
                $orderNos = array_map(function ($order)
                {
                    return [
                        'href'=>"<a href='/account/order/detail?no=" . $order->getOrderNo() . "' target='_blank'>" . $order->getOrderNo() . '</a>',
                        'origin'=>$order->getOrderNo(),
                    ];

                }, $trade->getOrders());

                $data['type'] = 'shopping';
                $data['title'] = '购物消费：订单号为:' . implode(',', array_column($orderNos,'origin'));
                $data['message'] = '购物消费：订单号为:' . implode(',', array_column($orderNos,'href'));
                break;
                //星座
            case 2:

                $zodiacNumbers = $trade->getZodiacNumber();
                $zodiac = self::index2map(Zodiac::getList(), 'id', 'name');

                $zodiacName = current(array_unique(array_column($zodiacNumbers, 'temp_youga_zodiac_id')));
                $zodiacNum = array_column($zodiacNumbers, 'num');
                $data['type'] = 'shopping';
                $data['title'] = '选星座消费:' . $zodiac[$zodiacName] . ',号码为:' . implode(',', $zodiacNum);
                $data['message'] = '选星座消费:' . $zodiac[$zodiacName] . ',号码为:' . implode(',', $zodiacNum);
                break;
            //膜订单
            case 3:
                $no = array_map(function ($order) {
                    return $order->no;
                }, $trade->getMembraneOrders());
                $data = [
                    'type' => 'shopping',
                    'title' => '购物消费: 订单号为: ' . implode(',', $no),
                    'message' => '购物消费: 订单号为: ' . implode(',', $no)
                ];
                break;

            case 4:
                $groupNumbers = array_map(function($ticket){
                    return $ticket->group->groupNumber;
                }, $trade->getGpubsTickets());
                $data = [
                    'type' => 'shopping',
                    'title' => '拼购消费',
                    'message' => '拼购消费，团编号：' . implode(',', $groupNumbers),
                ];
                break;
            }
            break;
        }
        return $data;

    }
}
