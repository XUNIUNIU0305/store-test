<?php
namespace custom\models\parts\temp\OrderLimit;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use custom\models\parts\ItemInCart;

class CustomLimit extends Object{

    private $_customLimit;

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        try{
            $this->_customLimit =include(__DIR__ . '/custom.php');
        }catch(\Exception $e){
            $this->_customLimit = [];
        }
    }

    public function getHasLimitCustom(){
        return empty($this->_customLimit) ? false : true;
    }

    public function isLimitProduct(ItemInCart $itemInCart){
        return isset($this->_customLimit[$itemInCart->productId]);
    }

    public function validateCustom(ItemInCart $itemInCart){
        if($customLimit = $this->_customLimit[$itemInCart->productId] ?? null){
            if(in_array($itemInCart->customUser->account, $customLimit)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
}
