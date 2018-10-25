<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:38
 */

namespace common\components\handler\coupon;


use common\ActiveRecord\CouponAR;
use common\ActiveRecord\CouponLogAR;
use common\ActiveRecord\CouponRuleAR;
use common\components\handler\Handler;
use common\models\parts\coupon\Coupon;
use common\models\parts\coupon\CouponRule;
use common\models\parts\supply\SupplyUser;
use custom\models\parts\trade\Trade;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
class CouponRuleHandler extends  Handler
{

    //创建优惠券券
    public static function create(Coupon $coupon,int $startTime,int $endTime,float $moneyLimit,int $postLimit,int $status=CouponRule::STATUS_NORMAL,$suppliers=[],int $moneyLimitType){
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $data=[
                'coupon_id'=>$coupon->id,
                'start_time'=>$startTime,
                'end_time'=>$endTime,
                'money_limit'=>$moneyLimit,
                'post_limit'=>$postLimit,
                'post_ready'=>0,//默认已发为0
                'money_limit_type'=>$moneyLimitType,
                'status'=>$status,
                'supply_limit'=>0,
            ];

            //如果配置商户，则表示限制商户，如果不配置，则表示不限
            if($suppliers){
                $data['supply_limit']=1;
            }

            //更新数据
            if(!CouponRuleAR::find()->where("coupon_id='$coupon->id'")->exists()){
                $return=Yii::$app->RQ->AR(new CouponRuleAR())->insert($data,false);
            }else{
                $return=Yii::$app->RQ->AR(CouponRuleAR::findOne(['coupon_id'=>$coupon->id]))->update($data,false);
            }

            //检测更新结果
            if($return===false){
                $transaction->rollBack();
                return false;
            }


            //配置适用商户
            $rule=new CouponRule(['coupon_id'=>$coupon->id]);
            $rule->emptySupply();//清除所有配置
            if($suppliers){
                foreach($suppliers as $key=>$var){
                    if(!$rule->setSupplier(new SupplyUser(['id'=>$var]))){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            //提交数据
            $transaction->commit();
            return true;
        }catch (Exception $e){
            echo $e->getMessage();
            $transaction->rollBack();
            return false;
        }

    }


    //获取所有规则
    private static function search($status=null){
        $couponIds = CouponAR::find()->select(['id'])->where(['status' => Coupon::STATUS_NORMAL])->column();

        return Yii::$app->RQ->AR(new CouponRuleAR())->all(
            [
                'select'=>['id'],
                'where'=>[
                    'coupon_id' => $couponIds,
                ],
            ]
        );

    }


    //检测发出优惠券
    public static function sendTicket(Trade $trade){
        $orders=$trade->getOrders();
        if(!$orders)return true;
        //如果已发放，则不重新发放
        if($trade->getCouponSendStatus()==Trade::COUPON_SEND_TYPE_YES){
            return false;
        }
        $ticket_list = self::search(CouponRule::STATUS_NORMAL);
        foreach($ticket_list as $key => $var){
            $rule=new CouponRule(['id'=>$var['id']]);
            if($rule->validateTrade($trade)){
                if($rule->getCoupon()->sendCouponRecord($trade->getCustomer(),CouponRule::DEFAULT_SEND_QUANTITY,time(),$trade->id)){
                    //更新发放状态
                   $trade->setCouponSendStatus(Trade::COUPON_SEND_TYPE_YES);

                }
            }
        }
        return true;
    }






}
