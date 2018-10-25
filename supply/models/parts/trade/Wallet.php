<?php
namespace supply\models\parts\trade;

use common\ActiveRecord\SupplyUserPayLogAR;
use common\ActiveRecord\SupplyUserPayRefundAR;
use common\models\parts\order\OrderRefund;
use Yii;
use common\models\parts\trade\WalletAbstract;
use common\ActiveRecord\SupplyUserWalletAR;
use common\ActiveRecord\SupplyUserReceiveLogAR;
use common\ActiveRecord\SupplyUserStatementAR;

class Wallet extends WalletAbstract
{

    public $userId;

    protected $rmbBefore;
    protected $rmbAfter;

    public function init()
    {
        if (is_null($this->id) && $this->userId) {
            try {
                $this->id = SupplyUserWalletAR::findOne(['supply_user_id' => $this->userId])->id;
            } catch (\Exception $e) {
            }
        }

        parent::init();
    }

    public function getUserId()
    {
        return $this->AR->supply_user_id;
    }

    public function getRMB()
    {
        return (float)$this->AR->rmb;
    }

    protected function getActiveRecord()
    {
        return new SupplyUserWalletAR;
    }

    public function pay($something, WalletAbstract $receiver = null)
    {
        if (!$receiver) return false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->rmbBefore = $this->RMB;
            //处理退货时，退款至客户钱包
            if ($something instanceof OrderRefund) {
                //扣除商户账户余额
                if (!$this->decreaseRMB($something->getRefundRmb())) {
                    $transaction->rollBack();
                    return false;
                }
                $this->rmbAfter = $this->RMB;
                //创建支付日志
                if (!$payLogId = $this->recordRefundPayLog($something)) {
                    $transaction->rollBack();
                    return false;
                }
                //创建日志总账记录
                if (!$this->recordStatement(Statement::TYPE_PAY, $payLogId, $something->getRefundRmb())) {
                    $transaction->rollBack();
                    return false;
                }
                //写入账户收款记录
                if (!$receiver->receive([
                    'rmb' => $something->getRefundRmb(),
                    'logId' => $payLogId,
                ])) {
                    $transaction->rollBack();
                    return false;
                }
            } else {
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    //记录退换货退款日志
    protected function recordRefundPayLog(OrderRefund $refund)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$payLogId = $this->recordPay(self::PAY_ORDER_REFUND, $refund->getRefundRmb())) throw new \Exception;
            Yii::$app->RQ->AR(new SupplyUserPayRefundAR())->insert([
                'supply_user_pay_log_id' => $payLogId,
                'order_refund_id' => $refund->id,
            ]);
            $transaction->commit();
            return $payLogId;
        } catch (\Exception $e) {

            $transaction->rollBack();
            return false;
        }
    }


    protected function recordPay(int $payType, float $payAmount)
    {
        if ($payType < 0 || $payAmount <= 0) return false;
        $result = Yii::$app->RQ->AR(new SupplyUserPayLogAR())->insert([
            'supply_user_id' => $this->userId,
            'pay_type' => $payType,
            'pay_amount' => $payAmount,
            'rmb_before' => $this->rmbBefore,
            'pay_datetime' => date("Y-m-d H:i:s"),
            'pay_unixtime' => time(),
            'rmb_after' => $this->rmbAfter,
        ]);
        return $result ? (int)Yii::$app->db->lastInsertId : false;
    }
 

    public function receive($something)
    {
        if (!in_array($this->receiveType, self::getReceiveTypes())) return false;
        if (!is_array($something)) return false;
        $rmb = null;
        $logId = null;
        extract($something, EXTR_IF_EXISTS);
        if (!is_float($rmb) || !is_int($logId)) return false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->rmbBefore = $this->RMB;
            if (!$this->increaseRMB($rmb)) throw new \Exception;
            $this->rmbAfter = $this->RMB;
            if (!$receiveLogId = $this->recordReceive($logId, $rmb)) throw new \Exception;
            if (!$this->recordStatement(Statement::TYPE_RECEIVE, $receiveLogId, $rmb)) throw new \Exception;
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public static function getReceiveTypes()
    {
        return [
            self::RECEIVE_ORDER_CONFIRMED,
        ];
    }

    protected function recordReceive(int $logId, float $rmb)
    {
        if (Yii::$app->RQ->AR(new SupplyUserReceiveLogAR)->insert([
            'supply_user_id' => $this->userId,
            'receive_type' => $this->receiveType,
            'corresponding_log_id' => $logId,
            'receive_amount' => $rmb,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ])
        ) {
            return (int)Yii::$app->db->lastInsertId;
        } else {
            return false;
        }
    }

    protected function recordStatement($type, int $logId, float $rmb)
    {
        if (!in_array($type, Statement::getTypes())) return false;
        return Yii::$app->RQ->AR(new SupplyUserStatementAR)->insert([
            'supply_user_id' => $this->userId,
            'alteration_type' => $type,
            'corresponding_log_id' => $logId,
            'alteration_amount' => $rmb,
            'rmb_before' => $this->rmbBefore,
            'rmb_after' => $this->rmbAfter,
        ]);
    }
}
