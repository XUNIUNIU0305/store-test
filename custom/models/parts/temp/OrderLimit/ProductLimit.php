<?php
namespace custom\models\parts\temp\OrderLimit;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use custom\models\parts\ItemInCart;
use common\ActiveRecord\CustomUserOrderLimitAR;

class ProductLimit extends Object{

    private $_productLimit;

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        try{
            $this->_productLimit = include(__DIR__ . '/product.php');
        }catch(\Exception $e){
            $this->_productLimit = [];
        }
    }

    public function getHasLimitProduct(){
        return empty($this->_productLimit) ? false : true;
    }

    public function isLimitProduct(ItemInCart $itemInCart){
        return isset($this->_productLimit[$itemInCart->productId]);
    }

    public function validateProductQuantity(ItemInCart $itemInCart){
        $hasBought = CustomUserOrderLimitAR::find()->where([
            'custom_user_id' => Yii::$app->user->id,
            'product_id' => $itemInCart->productId,
        ])->sum('quantity') ? : 0;
        return ($this->_productLimit[$itemInCart->productId] >= $hasBought + $itemInCart->count);
    }

    public function addBoughtProduct(ItemInCart $itemInCart){
        return Yii::$app->RQ->AR(new CustomUserOrderLimitAR)->insert([
            'custom_user_id' => Yii::$app->user->id,
            'product_id' => $itemInCart->productId,
            'quantity' => $itemInCart->count,
        ], false);
    }

    public function hasBoughtQuantity(ItemInCart $itemInCart){
        return Yii::$app->RQ->AR(new CustomUserOrderLimitAR)->sum([
            'where' => [
                'custom_user_id' => Yii::$app->user->id,
                'product_id' => $itemInCart->productId,
            ],
        ], 'quantity', false) ? : 0;
    }

    public function getProductLimitQuantity(ItemInCart $itemInCart){
        return $this->_productLimit[$itemInCart->productId] ?? null;
    }


    /**
     *====================================================
     * 获取限制商品id
     * @return array
     * @author shuang.li
     *====================================================
     */
    public static function getLimitProductId(){
        try{
            $productLimit = include(__DIR__ . '/product.php');
        }catch(\Exception $e){
            $productLimit = [];
        }
        return array_keys($productLimit);
    }
}
