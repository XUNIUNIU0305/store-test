<?php
namespace common\models\parts\trade\recharge;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use common\ActiveRecord\CustomUserRechargeApplyAR;
use common\ActiveRecord\WxpayNotifyLogAR;
use common\ActiveRecord\AlipayNotifyLogAR;
use common\ActiveRecord\AbchinaNotifyLogAR;
use common\ActiveRecord\NanjingGatewayDepositAR;
use common\models\parts\trade\RechargeMethodAbstract;
use common\models\parts\trade\recharge\RechargeApply;

class CustomerApply extends Object{

    public $id;

    protected $AR;
    protected $returnUrl;
    protected $notifyId;

    public function init(){
        if(!$this->id ||
            !$this->AR = CustomUserRechargeApplyAR::findOne($this->id)
        )throw new InvalidCallException;
        switch($this->AR->recharge_method){
            case RechargeMethodAbstract::METHOD_ALIPAY:
                $this->returnUrl = Yii::$app->params['RECHARGE_Alipay_Return_Url'];
                break;

            case RechargeMethodAbstract::METHOD_WX_INWECHAT:
                $this->returnUrl = '';
                break;

            default:
                $this->returnUrl = '';
                break;
        }
    }

    /**
     * 获取ALI通知路径
     *
     * @return string
     */
    public function getReturnUrl(){
        return $this->returnUrl;
    }

    /**
     * 获取用户ID
     *
     * @return integer
     */
    public function getUserId(){
        return $this->AR->custom_user_id;
    }

    public function getUserAccount(){
        return Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserAR)->scalar([
            'select' => ['account'],
            'where' => [
                'id' => $this->AR->custom_user_id,
            ],
        ]);
    }

    /**
     * 获取交易单ID
     *
     * @return integer
     */
    public function getTradeId(){
        return $this->AR->custom_user_trade_id;
    }

    /**
     * 获取充值方式
     *
     * @return integer
     */
    public function getRechargeMethod(){
        return $this->AR->recharge_method;
    }

    /**
     * 获取充值金额
     *
     * @return float
     */
    public function getRechargeAmount(){
        return (float)$this->AR->recharge_amount;
    }

    /**
     * 获取充值状态
     *
     * @return integer
     */
    public function getStatus(){
        return $this->AR->status;
    }

    /**
     * 设置当前请求为已充值
     *
     * @return integer|false
     */
    public function setRecharged(){
        if($this->status == RechargeApply::STATUS_WAIT){
            $this->AR->status = RechargeApply::STATUS_SUCCESS;
            return $this->AR->update();
        }else{
            return false;
        }
    }

    /**
     * 获取请求时间
     *
     * @return string
     */
    public function getApplyTime($unixTime = false){
        return $unixTime ? $this->AR->apply_unixtime :$this->AR->apply_datetime;
    }

    /**
     * 设置ALI通知ID
     *
     * @return boolean
     */
    public function setNotifyId($id){
        switch($this->rechargeMethod){
            case RechargeMethodAbstract::METHOD_ALIPAY:
                $AR = AlipayNotifyLogAR::findOne($id);
                break;

            case RechargeMethodAbstract::METHOD_WX_INWECHAT:
                $AR = WxpayNotifyLogAR::findOne($id);
                break;

            case RechargeMethodAbstract::METHOD_GATEWAY_PERSON:
                $AR = NanjingGatewayDepositAR::findOne($id);
                break;

            case RechargeMethodAbstract::METHOD_GATEWAY_CORP:
                $AR = NanjingGatewayDepositAR::findOne($id);
                break;

            case RechargeMethodAbstract::METHOD_ABCHINA_GATEWAY:
                $AR = AbchinaNotifyLogAR::findOne($id);
                break;

            default:
                $AR = null;
                break;
        }
        if($AR){
            $this->notifyId = $id;
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取通知ID
     *
     * @return integer
     */
    public function getNotifyId(){
        return $this->notifyId;
    }
}
