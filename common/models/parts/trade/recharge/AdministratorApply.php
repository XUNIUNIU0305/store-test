<?php
namespace common\models\parts\trade\recharge;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\WxpayNotifyLogAR;
use common\ActiveRecord\AlipayNotifyLogAR;
use common\ActiveRecord\AdminRechargeApplyAR;
use common\models\parts\trade\RechargeMethodAbstract;
use common\models\parts\trade\recharge\RechargeApply;

class AdministratorApply extends Object{

    public $id;

    protected $AR;
    protected $returnUrl;
    protected $notifyId;

    public function init(){
        if(!$this->AR = AdminRechargeApplyAR::findOne($this->id))throw new InvalidConfigException('unavailable id');
        switch($this->AR->recharge_method){
            case RechargeMethodAbstract::METHOD_WX_INWECHAT:
                $this->returnUrl = '';
                break;

            default:
                $this->returnUrl = '';
                break;
        }
    }

    public function getReturnUrl(){
        return $this->returnUrl;
    }

    public function getUserId(){
        return false;
    }

    public function getUserAccount(){
        return false;
    }

    public function getTradeId(){
        return $this->AR->admin_trade_id;
    }

    public function getRechargeMethod(){
        return $this->AR->recharge_method;
    }

    public function getRechargeAmount(){
        return (float)$this->AR->recharge_amount;
    }

    public function getStatus(){
        return $this->AR->status;
    }

    public function setRecharged(){
        if($this->status == RechargeApply::STATUS_WAIT){
            return Yii::$app->RQ->AR($this->AR)->update([
                'status' => RechargeApply::STATUS_SUCCESS,
            ], false);
        }else{
            return false;
        }
    }

    public function getApplyTime($unixTime = false){
        return $unixTime ? $this->AR->apply_unixtime : $this->AR->apply_datetime;
    }

    public function setNotifyId($id){
        switch($this->rechargeMethod){
            case RechargeMethodAbstract::METHOD_ALIPAY:
                $AR = AlipayNotifyLogAR::findOne($id);
                break;

            case RechargeMethodAbstract::METHOD_WX_INWECHAT:
                $AR = WxpayNotifyLogAR::findOne($id);
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

    public function getNotifyId(){
        return $this->notifyId;
    }
}
