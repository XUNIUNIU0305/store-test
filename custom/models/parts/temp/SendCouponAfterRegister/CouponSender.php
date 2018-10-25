<?php
namespace custom\models\parts\temp\SendCouponAfterRegister;

use Yii;
use yii\base\Object;
use common\models\parts\coupon\Coupon;

class CouponSender extends Object{

    /**
     * configuration
     * return [
     *     `couponId`,
     * ];
     */

    private $_coupons = [];
    private $_couponExist = false;

    public function init(){
        $configFile = __DIR__ . '/coupon.php';
        try{
            if(is_file($configFile)){
                $couponIds = include($configFile);
                $this->initializeCoupon($couponIds);
            }
        }catch(\Exception $e){
            //do nothing
        }
    }

    public function sendTo($account, $return = 'throw'){
        if($this->_couponExist == false)return true;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($this->_coupons as $coupon){
                if(!$coupon->sendForCustomers([$account]))throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    private function initializeCoupon(array $couponIds){
        try{
            foreach($couponIds as $couponId){
                try{
                    $coupon = new Coupon(['id' => $couponId]);
                    if($coupon->validateExpire() && $coupon->validateStatus() && $coupon->validateQuantity(1)){
                        $this->_coupons[] = $coupon;
                    }
                }catch(\Exception $e){
                    continue;
                }
            }
        }catch(\Exception $e){
            //do nothing
        }
        if($this->_coupons){
            $this->_couponExist = true;
        }
    }
}
