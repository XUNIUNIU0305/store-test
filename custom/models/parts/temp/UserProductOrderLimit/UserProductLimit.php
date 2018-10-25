<?php
namespace custom\models\parts\temp\UserProductOrderLimit;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use custom\models\parts\ItemInCart;

class UserProductLimit extends Object{

    private $_userLimit;
    private $_productLimit;


    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        try{
            $this->_userLimit       = include(__DIR__ . '/user_account.php');
            $this->_productLimit    = include(__DIR__ . '/user_product.php');
        }catch(\Exception $e){
            $this->_userLimit       = empty($this->_userLimit)    ? [] : $this->_userLimit;
            $this->_productLimit    = empty($this->_productLimit) ? [] : $this->_productLimit;
        }
    }

    public function getHasUserProductLimit(){
        return (empty($this->_userLimit) || empty($this->_productLimit)) ? false : true;
    }

    public function isLimitProduct(ItemInCart $itemInCart){
        return in_array($itemInCart->productId,$this->_productLimit);
    }

    public function isLimitUser(ItemInCart $itemInCart){
        return in_array($itemInCart->customUser->account,$this->_userLimit);
    }

    public function validateUserProductLimit(ItemInCart $itemInCart){
        if(is_array($this->_userLimit) && is_array($this->_productLimit)){
            if($this->isLimitProduct($itemInCart) && $this->isLimitUser($itemInCart)){
                return true;
            }elseif ($this->isLimitUser($itemInCart) && !$this->isLimitProduct($itemInCart)){
                return false;
            }
            else{
                return true;
            }
        }else{
            return false;
        }

    }

    public function validateProductLimit($param){
        if(is_array($this->_userLimit) && in_array($param,$this->_userLimit)){
            return true;
        }else{
            return false;
        }
    }

}