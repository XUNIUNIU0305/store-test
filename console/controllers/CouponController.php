<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/10
 * Time: 17:29
 */

namespace console\controllers;


use common\components\handler\coupon\CouponHandler;
use common\components\handler\coupon\CouponRecordHandler;
use common\models\parts\coupon\CouponRecord;
use console\controllers\basic\Controller;
use common\models\parts\coupon\Coupon;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\OrderAR;
use Yii;

class CouponController extends  Controller {

    /**
     * 优惠券自动过期
     */
    public function actionUpdateExpire(){
        CouponHandler::updateExpire();
        CouponRecordHandler::updateExpire();
        return 0;
    }

    /**
     * 发放指定优惠券至全员
     */
    public function actionSendCouponToEveryone($couponId){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $coupon = new Coupon(['id' => $couponId]);
            $customAccounts = Yii::$app->RQ->AR(new CustomUserAR)->column([
                'select' => ['account'],
                'where' => ['status' => 0],
            ]);
            if($coupon->validateQuantity($customAccountQuantity = count($customAccounts))){
                if(!$coupon->sendForCustomers($customAccounts))throw new \Exception;
                $transaction->commit();
                $this->stdout("sending coupon success\n");
            }else{
                $this->stdout("Too many accounts!\n");
                $this->stdout("The quantity of coupon can be sent: [{$coupon->sendQuantity}], the quantity of user accounts: [{$customAccountQuantity}]\n");
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->stdout("sending coupon failed\n");
        }
        return 0;
    }

    /**
     * 发送指定优惠券至未下单用户
     */
    public function actionSendCouponToNewUser($couponId){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $coupon = new Coupon(['id' => $couponId]);
            $boughtUser = OrderAR::find()->select('custom_user_id')->distinct()->column();
            $customAccounts = Yii::$app->RQ->AR(new CustomUserAR)->column([
                'select' => ['account'],
                'where' => ['not in', 'id', $boughtUser],
                'andWhere' => ['status' => 0],
            ]);
            if($coupon->validateQuantity($customAccountQuantity = count($customAccounts))){
                if(!$coupon->sendForCustomers($customAccounts))throw new \Exception;
                $transaction->commit();
                $this->stdout("sending coupon success\n");
            }else{
                $this->stdout("Too many accounts!\n");
                $this->stdout("The quantity of coupon can be sent: [{$coupon->sendQuantity}], the quantity of user accounts: [{$customAccountQuantity}]\n");
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->stdout("sending coupon failed\n");
        }
        return 0;
    }
}
