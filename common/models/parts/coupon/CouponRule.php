<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:30
 */

namespace common\models\parts\coupon;




use common\ActiveRecord\CouponAR;
use common\ActiveRecord\CouponLogAR;
use common\ActiveRecord\CouponRuleAR;
use common\ActiveRecord\CouponRuleSupplyAR;
use common\components\handler\coupon\CouponLogHandler;
use common\models\Object;
use common\models\parts\custom\CustomUser;
use common\models\parts\Order;
use common\models\parts\supply\SupplyUser;
use yii\base\InvalidCallException;
use Yii;
use custom\models\parts\trade\Trade;

class CouponRule extends Object
{

    const DEFAULT_SEND_QUANTITY=1;//默认发送优惠券数量
    //是否限制发行量
    const LIMIT_SEND_YES=1;//按发行数量
    const LIMIT_SEND_NO=0;//不限发放量

    const STATUS_STOP=1;//停用
    const STATUS_NORMAL=0;//可用

    //限制消费金额类型
    const MONEY_LIMIT_TYPE_TOTAL=0;//限制消费总额
    const MONEY_LIMIT_TYPE_SUPPLY=1;//商户消费金额

    //是否限制商户
    const SUPPLY_LIMIT_TYPE_YES=1;// 限制商户
    const SUPPLY_LIMIT_TYPE_NO=0;//不限制消费商户

    public $coupon_id;
    public $id;
    public function init(){
        if($this->id){
            if(!$this->AR=CouponRuleAR::findOne($this->id))throw new InvalidCallException();
            $this->coupon_id=$this->AR->coupon_id;
        }elseif($this->coupon_id){
            if(!$this->AR=CouponRuleAR::findOne(['coupon_id'=>$this->coupon_id]))throw new InvalidCallException();
            $this->id=$this->AR->id;
        }else{
            throw new InvalidCallException();
        }
    }

    //获取开始时间
    public function getStartTime(){
        return $this->AR->start_time?date("Y-m-d H:i:s",$this->AR->start_time):"";
    }

    //获取结束时间
    public function getEndTime(){
        return $this->AR->end_time?date("Y-m-d H:i:s",$this->AR->end_time):"";
    }

    //获取优惠券对象
    public function getCoupon(){
        return new Coupon(['id'=>$this->coupon_id]);
    }


    //验证数量
    public function validateQuantity($quantity=1){
         $coupon=$this->getCoupon();
        if($this->post_limit==self::LIMIT_SEND_NO){
            //不限,检测发行量,则自动调整总量
            if($coupon->total_quantity-$coupon->send_quantity>=$quantity){
                return true;
            }
            //不足时，自动更新发行数量
            if($coupon->increaseTotal($quantity)){
                return true;
            }
        }else{
            //限制发行量
            if($coupon->total_quantity-$coupon->send_quantity>=$quantity){
                return true;
            }
        }
        return false;
    }

    //验证消费金额
    public function validateTotal($total){
        if($total <= 0)return false;
        return ($this->money_limit <= $total);
    }

    //验证时间
    public function validateTime(Trade $trade){
        return ($this->start_time <= $trade->getCreateTime(true) && $this->end_time >= $trade->getCreateTime(true));
    }

    //为该规则配置适用商户
    public function setSupplier(SupplyUser $seller,$return="throw"){
        //检测是否已存在
        if($this->supplierExist($seller)){
            return true;
        }
        return Yii::$app->RQ->AR(new CouponRuleSupplyAR())->insert([
            'supply_user_id'=>$seller->id,
            'coupon_rule_id'=>$this->id,
        ],$return);

    }

    //检测是否有配置商户
    public function supplierExist(SupplyUser $seller){

        return CouponRuleSupplyAR::find()->where([
            'supply_user_id'=>$seller->id,
            'coupon_rule_id'=>$this->id,
        ])->exists()?true:false;
    }


    public function getSuppliersList(){
        return array_map(function($item){
            return [
                'id'=>$item->id,
                'company_name'=>$item->getCompanyName(),
                'account'=>$item->getAccount(),
                'brand_name'=>$item->getBrandName(),
            ];
        },$this->getSuppliers());
    }

    //获取配置商户信息
    public function getSuppliers(){
        return array_map(function($item){
            return new SupplyUser(['id'=>$item['supply_user_id']]);
        },Yii::$app->RQ->AR(new CouponRuleSupplyAR())->all([
            'select'=>['supply_user_id'],
            'where'=>['coupon_rule_id'=>$this->id]
        ]));
    }


    //取消绑定商户
    public function cancelSupplier(SupplyUser $seller,$return="throw"){
        return Yii::$app->RQ->AR(new CouponRuleSupplyAR())->delete([
            'supply_user_id'=>$seller->id,
            'coupon_rule_id'=>$this->id,
        ],$return);
    }

    //清除所有绑定的商户信息
    public function emptySupply(){
        return CouponRuleSupplyAR::deleteAll(['coupon_rule_id'=>$this->id]);
    }


    //验证订单是否符合发券条件
    public function validateTrade(Trade $trade){
        $total = 0;
        if($this->money_limit_type == self::MONEY_LIMIT_TYPE_TOTAL){
            foreach($trade->orders as $order){
               if($this->supplierExist($order->getSupplier())){
                    $total = $trade->totalFee;
                    break;
               }
            }
        }else {
            foreach ($trade->orders as $order) {
                if ($this->supplierExist($order->getSupplier())) {
                    $total += $order->getTotalFee();
                }
            }
        }

        //验证消费金额与消费时间
        if(!$this->validateTotal($total)//验证消费金额
            ||!$this->validateTime($trade)//验证时间
            ||!$this->validateQuantity()//验证发送数量
        ){
           return false;
        }

       return true;

    }



}
