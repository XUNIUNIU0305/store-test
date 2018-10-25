<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:30
 */

namespace common\models\parts\coupon;

use common\ActiveRecord\CouponAR;
use common\ActiveRecord\CouponRecordAR;
use common\ActiveRecord\CouponRuleAR;
use common\components\handler\coupon\CouponLogHandler;
use common\components\handler\coupon\CouponRecordHandler;
use common\components\handler\coupon\CouponRuleHandler;
use common\models\Object;
use common\models\parts\custom\CustomUser;
use common\models\parts\supply\SupplyUser;
use yii\base\Exception;
use yii\base\InvalidCallException;
use Yii;

class Coupon extends Object
{

    //优惠券状态
    CONST STATUS_DEL=-1;//删除
    CONST STATUS_NORMAL=0;//正常
    CONST STATUS_STOP=1;//停用
    CONST STATUS_FINISHED=2;//结束

    private $_status=[
        self::STATUS_DEL=>'已删除',
        self::STATUS_NORMAL=>'使用中',
        self::STATUS_STOP=>'暂停中',
        self::STATUS_FINISHED=>'已过期',
    ];

    public $id;
    private $_supplier;
    public function init(){
        if(!$this->id||!$this->AR=CouponAR::findOne($this->id))throw new InvalidCallException();
    }

    //设置最大可发行数量
    public function setMaxQuantity($quantity){
        $this->AR->max_quantity=$quantity;
        return $this->AR->save();
    }
    //设置当前已发行最大值
    public function setMaxCode($code){
        $this->AR->max_code=$code;
        return $this->AR->save();
    }

    public function updateAttribute($quantity,$code){
        $this->AR->send_quantity+=$quantity;
        $this->AR->max_code=$code;
        return $this->AR->save();
    }

    //获取状态描述
    public function getStatusTxt(){
        return $this->_status[$this->status];
    }

    //获取消费限制
    public function getConsumptionLimit(){
        return $this->AR->total_limit;
    }

    //获取优惠券金额
    public function getPrice(){
        return $this->AR->price;
    }

    //获取可发行数量
    public function getSendQuantity(){
        return $this->AR->total_quantity-$this->AR->send_quantity;
    }
    //获取最大可新增发行量
    public function getMaxQuantity(){
        return $this->AR->max_quantity-$this->AR->total_quantity;
    }
    //获取开始时间
    public function getStartTime(){
        return date("Y-m-d H:i:s",$this->start_time);
    }

    //获取结束时间
    public function getEndTime(){
        return date("Y-m-d H:i:s",$this->end_time);
    }

    //获取适用商户
    public function getSupplier(){
        if(is_null($this->_supplier)){
            if($this->AR->supply_user_id > 0){
                $this->_supplier = new SupplyUser(['id'=>$this->AR->supply_user_id]);
            }else{
                $this->_supplier = false;
            }
        }
        return $this->_supplier;
    }
    //设置优惠券状态
    public function setStatus($status){
        if(!in_array($status,[
            self::STATUS_FINISHED,
            self::STATUS_NORMAL,
            self::STATUS_STOP,
            self::STATUS_DEL
        ])){
            return false;
        }
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $this->AR->status=$status;
            //保存数据
            if(!$this->AR->save()){
                $transaction->rollBack();
                return false;
            }
            //记录日志
            $logIntro="更新优惠券状态由".$this->_status[$this->status]."变更为".$this->_status[$status];
            if(!$this->createLog($logIntro)){
                $transaction->rollBack();
                return false;
            }
            //如果删除优惠券，由注销所有已发出去的券
            if($status==self::STATUS_DEL){
                if(!CouponRecordHandler::disableTicket($this)){
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }

    }

    //创建日志
    public function createLog($intro,$return="throw"){
        return CouponLogHandler::create($this,$intro,$return);
    }




    //获取日志信息
    public function getLogs($pageSize=10,$currentPage=1){
        $model=CouponLogHandler::search($pageSize,$currentPage,$this,false);
        $data=array_map(function($item){
            $log=new CouponLog(['id'=>$item['id']]);
            return [
                'id'=>$log->id,
                'intro'=>$log->log_intro,
                'time'=>date("Y-m-d H:i:s",$log->log_time),
            ];
        },$model->models);
    }

    //创建实体券
    public function createTicket($quantity){

        return $this->sendCouponRecord(null,$quantity,0);

    }

    //增另已发出量
    public function increaseSendTotal($quantity){
        $this->AR->send_quantity+=$quantity;
        return $this->AR->save();
    }

    //添加发行量
    public function increaseTotal($quantity){
        if($this->AR->total_quantity+$quantity>$this->AR->max_quantity){
            $this->createLog('修改优惠券总发行量失败,发行总量不允许大于最大可发行量!');
            return false;
        }

        $this->AR->total_quantity+=$quantity;

        if($this->AR->save()){
            $this->createLog('修改优惠券总发行数量');
            return true;
        }
        return false;
    }

    //批量发送优惠券给个人
    public function sendForCustomers($customers=[]){
        $transaction=Yii::$app->db->beginTransaction();
        try{

            foreach($customers as $key=>$var){
                if(!$this->sendCouponRecord((new CustomUser(['account'=>$var])),1,time())){
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    //发放优惠券给个人
    public function sendCouponRecord(CustomUser $customer=null,$quantity=1,$activeTime=0,$custom_user_trade_id=0){

        if(!$this->validateExpire()
            ||!$this->validateStatus()
            ||!$this->validateQuantity($quantity)){
            //过期券，不允许发放
            return false;
        }

        //默认为未激活udyd记
        $status=CouponRecord::STATUS_EXCITED;
        //验证用户
        if($customer!==null){
            if(!$this->validateReceiveLimit($customer)){
                return false;
            }
            //绑定用户自动为已激活状态
            $status=CouponRecord::STATUS_ACTIVE;
        }

        //操作发送
        return CouponRecordHandler::create($this,$customer,$quantity,$status,$activeTime,$custom_user_trade_id);
    }


    //获取优惠券自动发放规则
    public function getRuleForAutomatic(){
        try{
            return new CouponRule(['coupon_id'=>$this->id]);
        }catch (Exception $e){
            return false;
        }
    }

    //创建自动发放规则
    public function createRuleForAutomatic($startTime,$endTime,$moneyLimit,$postLimit=null,$status){
        if($postLimit===null){
            //如果未设置，则表示根据优惠券配置可领数量
            $postLimit=$this->AR->total_quantity-$this->AR->send_quantity;
        }
        if(CouponRuleHandler::create($this,$startTime,$endTime,$moneyLimit,$postLimit,$status)){
            return $this->getRuleForAutomatic();
        }
        return false;
    }

    //获取发放记录
    public function getRecords($pageSize,$currentPage,CustomUser $user=null,$status,$sort=['id'=>SORT_DESC]){
        return CouponRecordHandler::search($pageSize,$currentPage,$this,$user,$status,$sort);
    }

    //验证有效期
    public function validateExpire($used=false){
        if($used) {
            if (time() >= $this->start_time && time() <= $this->end_time) {
                return true;
            }
        }else{
            if (time() <= $this->end_time) {
                return true;
            }
        }
        return false;
    }

    //验证可发送数量
    public function validateQuantity($quantity=0){
        if($this->total_quantity>$this->send_quantity
            &&$quantity<=($this->total_quantity-$this->send_quantity)
        ){
            return true;
        }
        return false;
    }

    //验证发送状态
    public function validateStatus(){
        if($this->status==self::STATUS_NORMAL){
            return true;
        }
        return false;
    }

    //验证使用限额
    public function validateTotal($totalMoney){
        if($this->total_limit>$totalMoney && $this->price > $totalMoney){
            return false;
        }
        return true;
    }

    //验证限领数量
    public function validateReceiveLimit(CustomUser $user){
        //不限单人领取
        if($this->receive_limit==0){return true;}

        if($user->getCouponQuantity($this)>=$this->receive_limit){
            return false;
        }
        return true;
    }

    //验证消费商户
    public function validateSupplier(SupplyUser $user){
        if($this->supply_user_id>0){
            if($user->id==$this->supply_user_id){
                return true;
            }
            return false;
        }
        return true;
    }

    //注销优惠券
    public function cancelTicket(array $ticket_id=[]){
        $id=implode(',',$ticket_id);
        $where="coupon_id='$this->id' and id in($id)";
        return CouponRecordAR::updateAll(['status'=>CouponRecord::STATUS_CANCEL],$where);
    }


    //获取优惠券自动发送规则
    public function getRule(){
        if(CouponRuleAR::find()->where("coupon_id='$this->id'")->exists()){
            return new CouponRule(['coupon_id'=>$this->id]);
        }
        return false;
    }



}
