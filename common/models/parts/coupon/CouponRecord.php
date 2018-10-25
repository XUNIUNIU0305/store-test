<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:30
 */

namespace common\models\parts\coupon;




use common\ActiveRecord\CouponRecordAR;
use common\models\Object;
use common\models\parts\custom\CustomUser;
use common\models\parts\supply\SupplyUser;
use yii\base\InvalidCallException;
use Yii;
use common\models\parts\Order;

class CouponRecord extends Object
{
    /*
0 未激号
1:已激活
2:已使用
3已过期*/

    const STATUS_EXCITED=0;//未激活
    const STATUS_ACTIVE=1;//已激活
    const STATUS_USED=2;//已使用
    const STATUS_EXPIRE=3;//已过期
    const STATUS_CANCEL=4;//已注销


    private $_status=[
        self::STATUS_EXCITED=>'未激活',
        self::STATUS_ACTIVE=>'已激活',
        self::STATUS_USED=>'已使用',
        self::STATUS_EXPIRE=>'已过期',
        self::STATUS_CANCEL=>'已注销',
    ];

    public $passwd;
    public $ticket_code;
    public $id;

    private $_coupon;
    public function init(){
        if($this->id){
            if(!$this->AR=CouponRecordAR::findOne($this->id))throw new InvalidCallException();

        }else{
            if(!empty($this->ticket_code)&&!empty($this->passwd)){
                if(!$this->AR=CouponRecordAR::findOne(['code'=>$this->ticket_code,'password'=>$this->passwd]))throw new InvalidCallException();
                $this->id=$this->AR->id;
            }else{
                throw new InvalidCallException();
            }
        }

    }


    public function getValidate(){
        return $this->getCoupon();
    }


    //获取所属优惠券
    public function getCoupon(){
        if(is_null($this->_coupon)){
            $this->_coupon = new Coupon(['id' => $this->coupon_id]);
        }
        return $this->_coupon;
    }

    //获取所属用户
    public function getCustomer(){
        if($this->custom_user_id>0){
            return new CustomUser(['id'=>$this->custom_user_id]);
        }
        return false;
    }

    //获取状态描述
    public function getStatusTxt(){
        return $this->_status[$this->status];
    }

    public function getExpire(){
        return $this->AR->expire>0?date("Y-m-d H:i:s",$this->AR->expire):"";
    }


    public function getActiveTime(){
        return $this->AR->active_time>0?date("Y-m-d H:i:s",$this->AR->active_time):"";
    }
    public function getUsedTime(){
        return $this->AR->used_time>0?date("Y-m-d H:i:s",$this->AR->used_time):"";
    }
    public function getCreateTime(){
        return $this->AR->create_time>0?date("Y-m-d H:i:s",$this->AR->create_time):"";
    }
    //更新过期
    public function setExpire(){
        if($this->expire>time()){
            return false;
        }
        $this->AR->status=self::STATUS_EXPIRE;
        return $this->AR->save();
    }

    //激活
    public function setActive(CustomUser $user){
        //仅允许对未激活的优惠券执行激活操作
        if($this->status!=self::STATUS_EXCITED){
            return false;
        }
        //限领数量
        if($this->getCoupon()->receive_limit>0&&$user->getCouponQuantity($this->getCoupon())>=$this->getCoupon()->receive_limit){
            return false;
        }

        $this->AR->custom_user_id=$user->id;
        $this->AR->status=self::STATUS_ACTIVE;
        $this->AR->active_time=time();
        return $this->AR->save();
    }

    //使用
    public function setUsed(Order $order){
        //仅可以使用激活状态的优惠券
        if($this->status != self::STATUS_ACTIVE){
            return false;
        }
        //过期券，不允许使用
        if(time()>$this->expire){
            $this->setExpire();//更新其状态
            return false;
        }
        //非本人不可使用
        if($order->customerId != $this->custom_user_id){
            return false;
        }
        //限制使用金额
        if(!$this->getCoupon()->validateTotal($order->itemsFee)){
            return false;
        }

        $this->AR->used_time = time();
        $this->AR->status = self::STATUS_USED;
        $this->AR->order_id = $order->id;
        return $this->AR->save();
    }


    //验证优惠券是否可用
    public function validateTicket(SupplyUser $supplier=null,$supplyTotal,$orderTotal){
        $coupon=$this->getCoupon();
        if(!$coupon->validateStatus()||!$coupon->validateExpire(true)){
            return false;
        }
        //限制商户
        if($coupon->getSupplier()){
            if($supplier!=null){
                if($coupon->validateSupplier($supplier)&&$coupon->validateTotal($supplyTotal)){
                    return true;
                }
            }
            return false;
        }

        if($coupon->validateTotal($orderTotal)){
            return true;
        }

        return false;
    }


}
