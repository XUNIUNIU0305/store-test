<?php
namespace business\models\parts\trade;

use Yii;
use common\models\parts\trade\StatementAbstract;
use common\ActiveRecord\BusinessUserStatementAR;
use common\ActiveRecord\NonTransactionDepositAndDrawAR;
use common\ActiveRecord\AdminPayNonTransactionAR;
use common\models\parts\MembraneOrder;
use business\models\parts\trade\record\AdminPayMembraneOrder;
use business\models\parts\trade\record\BusinessUserFreezeDraw;
use business\models\parts\trade\record\BusinessUserPayDraw;
use business\models\parts\trade\record\BusinessUserthawDraw;
use common\ActiveRecord\BusinessUserPayNonTransactionAR;
use business\models\parts\trade\record\UserDraw;
use business\models\parts\trade\record\ReceiveLog;
use business\models\parts\trade\record\PayLog;
use yii\base\InvalidConfigException;
use common\models\parts\trade\WalletAbstract;

class Statement extends StatementAbstract{

    public $id;

    protected $AR;

    public function init(){
        if(!$this->AR = BusinessUserStatementAR::findOne($this->id))throw new InvalidConfigException;
    }

    public function getAlterationType(){
        return $this->AR->alteration_type;
    }

    public function getLogId(){
        return $this->AR->corresponding_log_id;
    }

    public function getAlterationAmount(){
        return $this->AR->alteration_amount;
    }

    public function getRMBBefore(){
        return $this->AR->rmb_before;
    }

    public function getRMBAfter(){
        return $this->AR->rmb_after;
    }

    public function getAlterationTime($unixTime = false){
        return $unixTime ? $this->AR->alteration_unixtime : $this->AR->alteration_datetime;
    }

    /**
     *====================================================
     * 获取交易内容

     *====================================================
     */
    public function getContent()
    {
        $data = [];
        //实例化入账对象

        switch ($this->AR->alteration_type) {
            //入账有两种情况 消费者买膜和提现失败
            case statement::TYPE_RECEIVE:
                $receiveLog = new ReceiveLog([
                    'id' => $this->AR->corresponding_log_id,
                ]);
                //获取对应支付id
                $logId = $receiveLog->getLogId();
                //获取入账类型
                $receiveType = $receiveLog->getReceiveType();
                switch ($receiveType) {
                    case WalletAbstract::RECEIVE_MEMBRANE_ORDER_FINISH:
                        try{
                            $adminPayMembraneOrder = new AdminPayMembraneOrder([
                                'log_id' => $logId,
                            ]);

                            $order = new MembraneOrder([
                                'id' => $adminPayMembraneOrder->getMembraneOrderId()
                            ]);
                            $orderNo = $order->no;

                            $data['type'] = 'order_receive';
                            $data['title'] = '车膜订单号';
                            $data['message'] = "车膜订单号：" . $orderNo ;
                            break;
                        }catch (\Exception $e) {
                            return '';
                        }

                    case WalletAbstract::RECEIVE_NON_TRANSACTION:
                        try{
                            $ticketId = Yii::$app->RQ->AR(new AdminPayNonTransactionAR)->scalar([
                                'select' => ['non_transaction_deposit_and_draw_id'],
                                'where' => [
                                    'admin_pay_log_id' => $logId,
                                ],
                            ]);
                            if($ticketId) {
                                $brief = Yii::$app->RQ->AR(new NonTransactionDepositAndDrawAR)->scalar([
                                    'select' => ['operate_brief'],
                                    'where' => [
                                        'id' => $ticketId,
                                    ],
                                ]);
                                $data['type'] = 'non_transaction_receive';
                                $data['title'] = $brief;
                                $data['message'] = $brief;
                                break;
                            }
                        }catch (\Exception $e) {
                            return '';
                        }

                    case WalletAbstract::RECEIVE_PARTNER_AWARD:
                        $data['type'] = 'partner_award';
                        $data['title'] = '门店加盟奖励';
                        $data['message'] = "门店加盟奖励";
                        break;
                }
                break;
            //提现失败:返款

            //提现申请：冻结资金
            case statement::TYPE_FREEZE:
                try{
                    $freezeDraw = new BusinessUserFreezeDraw([
                        'log_id' => $this->AR->corresponding_log_id
                    ]);

                    $userDraw = new UserDraw ([
                        'log_id' => $freezeDraw->getUserDrawId(),
                    ]);

                    $data['type'] = 'freeze';
                    $data['title'] = '提现操作冻结流程';
                    $data['message'] = "提现流水号：<a href='/bank/draw-detail?id=" . $freezeDraw->getUserDrawId() . "' target='_blank'>" . $userDraw->getOrderNo() . '</a>';
                    break;
                }catch (\Exception $e) {
                    return '';
                }

            //提现成功：解冻资金
            case statement::TYPE_THAW:
                try{
                    $thawDraw = new BusinessUserthawDraw([
                        'log_id' => $this->AR->corresponding_log_id
                    ]);
                    $userDraw = new UserDraw([
                        'log_id' => $thawDraw->getUserDrawId(),
                    ]);
                    $data['type'] = 'thaw';
                    $data['title'] = '提现操作解冻流程';
                    $data['message'] = "提现流水号：<a href='/bank/draw-detail?id=" . $thawDraw->getUserDrawId() . "' target='_blank'>" . $userDraw->getOrderNo() . '</a>';
                    break;
                }catch (\Exception $e) {
                    return '';
                }

            //提现成功：扣款
            case statement::TYPE_PAY:
                $payLog = new PayLog([
                    'id' => $this->AR->corresponding_log_id,
                ]);
                //获取对应支付id
                $logId = $payLog->getLogId();
                //获取入账类型
                $payType = $payLog->getPayType();
                switch ($payType){
                    case WalletAbstract::PAY_BUSINESS_DRAW:
                        try{
                            $payDraw = new BusinessUserPayDraw([
                                'log_id' => $this->AR->corresponding_log_id
                            ]);
                            $userDraw = new UserDraw([
                                'log_id' => $payDraw->getUserDrawId(),
                            ]);

                            $data['type'] = 'pay';
                            $data['title'] = '提现操作完成扣款';
                            $data['message'] = "提现流水号：<a href='/bank/draw-detail?id=" . $payDraw->getUserDrawId() . "' target='_blank'>" . $userDraw->getOrderNo() . '</a>';
                            break;
                        }catch (\Exception $e) {
                            return '';
                        }

                    case WalletAbstract::PAY_NON_TRANSACTION:
                    try{
                        $ticketId = Yii::$app->RQ->AR(new BusinessUserPayNonTransactionAR)->scalar([
                            'select' => ['non_transaction_deposit_and_draw_id'],
                            'where' => [
                                'business_user_pay_log_id' => $logId,
                            ],
                        ]);
                        if($ticketId) {
                            $brief = Yii::$app->RQ->AR(new NonTransactionDepositAndDrawAR)->scalar([
                                'select' => ['operate_brief'],
                                'where' => [
                                    'id' => $ticketId,
                                ],
                            ]);
                            $data['type'] = 'non_transaction_pay';
                            $data['title'] = $brief;
                            $data['message'] = $brief;
                            break;
                        }
                    }catch (\Exception $e) {
                        return '';
                    }
                }

        }
        return $data;
    }
}
