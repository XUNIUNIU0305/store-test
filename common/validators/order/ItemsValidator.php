<?php
namespace common\validators\order;

use Yii;
use common\models\Validator;
use custom\models\parts\UrlParamCrypt;
use common\models\parts\Product;
use common\ActiveRecord\CouponRecordAR;
use common\models\parts\coupon\CouponRecord;

class ItemsValidator extends Validator{

    public $message;
    public $q;

    private $couponIds = [];
    private $userAvailableCoupons;
    private $_model;
    private $_attribute;

    public function validateAttribute($model, $attribute){
        $this->_model = $model;
        $this->_attribute = $attribute;
        parent::validateAttribute($model, $attribute);
    }

    protected function validateValue($items){
        //游客状态
        if(Yii::$app->user->isGuest)return $this->message;

        //q值无法解析
        if(!$itemsId = (new UrlParamCrypt)->decrypt($this->q))return $this->message;

        //下单商品不存在购物车
        if(!$cartItems = Yii::$app->CustomUser->cart->getItemsGroupByOrders($itemsId))return $this->message;

        //按店铺验证
        foreach($cartItems as $supplierAndItems){
            $supplierId = $supplierAndItems['supplier']->id;

            //单店铺下订制与非订制均无商品
            if(!isset($items[$supplierId][Product::TYPE_STANDARD]) &&
                !isset($items[$supplierId][Product::TYPE_CUSTOMIZATION])
            )return $this->message;

            //初始化无商品订单(订制、非订制)
            if(!isset($items[$supplierId][Product::TYPE_STANDARD])){
                $this->_model->{$this->_attribute}[$supplierId][Product::TYPE_STANDARD] = $items[$supplierId][Product::TYPE_STANDARD] = ['ticket' => ''];
            }
            if(!isset($items[$supplierId][Product::TYPE_CUSTOMIZATION])){
                $this->_model->{$this->_attribute}[$supplierId][Product::TYPE_CUSTOMIZATION] = $items[$supplierId][Product::TYPE_CUSTOMIZATION] = [];
            }

            //订制与非订制商品种类不匹配
            if(count($items[$supplierId][Product::TYPE_STANDARD]) != count($supplierAndItems['items'][Product::TYPE_STANDARD]) + 1 ||
                count($items[$supplierId][Product::TYPE_CUSTOMIZATION]) != count($supplierAndItems['items'][Product::TYPE_CUSTOMIZATION]))return $this->message;

            //验证非订制商品
            foreach($supplierAndItems['items'][Product::TYPE_STANDARD] as $standardItem){

                //商品不存在或备注错误
                if(!$this->verifyComment($items[$supplierId][Product::TYPE_STANDARD][$standardItem->id] ?? null))return $this->message;

            }
            $standardItemCoupon = $items[$supplierId][Product::TYPE_STANDARD]['ticket'] ?? null;
            //商品优惠券错误
            if(!$this->verifyCoupon($standardItemCoupon))return $this->message;

            //验证订制商品
            foreach($supplierAndItems['items'][Product::TYPE_CUSTOMIZATION] as $customItem){
                //商品不存在
                if(!$outerCustomItem = $items[$supplierId][Product::TYPE_CUSTOMIZATION][$customItem->id] ?? false)return $this->message;

                //商品数量与购物车内不符
                if(count($outerCustomItem) != $customItem->count)return $this->message;

                //商品备注或优惠券错误
                foreach($outerCustomItem as $itemData){
                    if(!$this->verifyComment($itemData['comment'] ?? null) || 
                        !$this->verifyCoupon($itemData['ticket'] ?? null))return $this->message;
                }
            }
        }
        return true;
    }

    private function verifyCoupon($couponId){
        if(is_null($couponId) || in_array($couponId, $this->couponIds))return false;
        if($couponId != ''){
            if(is_null($this->userAvailableCoupons)){
                $this->userAvailableCoupons = Yii::$app->CustomUser->CurrentUser->getAvailableTickets(false);
            }
            if(!in_array($couponId, $this->userAvailableCoupons))return false;
            $this->couponIds[] = $couponId;
        }
        return true;
    }

    private function verifyComment($comment){
        if(!is_string($comment))return false;
        return mb_strlen($comment, Yii::$app->charset) < 255;
    }
}
