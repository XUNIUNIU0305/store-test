<?php
namespace custom\models\parts\temp\OrderReduction;

use Yii;
use yii\base\Object;
use common\models\parts\coupon\Coupon;
use yii\base\InvalidCallException;
use common\models\parts\Product;

class ActivityReduction extends Object{

    //configuration1
    //return [
        //'time' => [
            //'from' => '2017-12-01 00:00:01',
            //'to' => '2017-12-31 12:00:01',
        //],
        //'supplier' => [
            //'1' => [135,],
        //],
    //];
    //configuration2
    //return [
        //0 => [
            //'time' => [],
            //'supplier' => [],
        //],
        //1 => [
            //'time' => [],
            //'supplier' => [],
        //],
        //2 => [
            //'time' => [],
            //'supplier' => [],
        //],
    //];

    private $_time;
    private $_supplier; // [`supplierId` => [`couponId`, ]];
    private $_supplierId;
    private $_supplierCoupon; // [`couponId` => new Coupon(['id' => `couponId`, ])];
    private $_couponLimit = []; // [`couponId` => `price`, ];
    private $_couponPrice = []; //[`couponId` => `price`, ];

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        $configFile = __DIR__ . '/activity.php';
        try{
            if(is_file($configFile)){
                $file = include($configFile);
                $config = $this->achieveConfig($file);
            }else{
                $config = false;
            }
        }catch(\Exception $e){
            $config = false;
        }
        $this->initializeActivity($config);
    }

    protected function achieveConfig(array $file){
        if(isset($file['time']) && isset($file['supplier'])){
            return $file;
        }else{
            foreach($file as $config){
                if(isset($config['time']) && isset($config['supplier'])){
                    try{
                        if(strtotime($config['time']['from']) <= Yii::$app->time->unixTime && strtotime($config['time']['to']) > Yii::$app->time->unixTime){
                            return $config;
                        }else{
                            continue;
                        }
                    }catch(\Exception $e){
                        continue;
                    }
                }else{
                    continue;
                }
            }
            return false;
        }
    }

    public function isActivityTime(){
        $time = time();
        return ($this->_time['from'] <= $time && $this->_time['to'] >= $time);
    }

    public function isActivitySupplier(string $supplierId){
        return array_key_exists($supplierId, $this->_supplier);
    }

    public function matchCoupon(array $items, bool $obj){
        if(!$this->isActivityTime())return false;
        $items = $obj ? $items : Yii::$app->CustomUser->cart->getItemsGroupByOrders($items);
        $coupons = [];
        foreach($items as $item){
            $supplier = $item['supplier'];
            $itemsOfSupplier = $item['items'];
            if(!$this->initSupplier((string)$supplier->id))continue;
            $itemsFee = 0;
            foreach($itemsOfSupplier[Product::TYPE_STANDARD] as $standardItem){
                $itemsFee += $standardItem->price * $standardItem->count;
            }
            foreach($this->_couponLimit as $couponId => $limitPrice){
                if($itemsFee >= $limitPrice){
                    $coupons[] = $this->_supplierCoupon[$couponId];
                    break;
                }
            }
        }
        if($coupons){
            return $coupons;
        }else{
            return false;
        }
    }

    protected function initSupplier(string $supplierId){
        $this->_supplierCoupon = [];
        $this->_supplierId = null;
        $this->_couponLimit = [];
        $this->_couponPrice = [];
        if(array_key_exists($supplierId, $this->_supplier)){
            try{
                foreach($this->_supplier[$supplierId] as $couponId){
                    $this->_supplierCoupon[$couponId] = new Coupon(['id' => $couponId]);
                }
                $this->_supplierId = $supplierId;
                $this->initSupplierCoupon();
                return true;
            }catch(\Exception $e){
                return false;
            }
        }else{
            return false;
        }
    }

    protected function initSupplierCoupon(){
        if($this->_supplierCoupon){
            foreach($this->_supplierCoupon as $coupon){
                $this->_couponLimit[$coupon->id] = $coupon->consumptionLimit;
                $this->_couponPrice[$coupon->id] = $coupon->price;
            }
            arsort($this->_couponLimit, SORT_NUMERIC);
        }
    }

    protected function initializeActivity($config){
        if($config){
            try{
                $this->_time = [
                    'from' => strtotime($config['time']['from']),
                    'to' => strtotime($config['time']['to']),
                ];
                $this->_supplier = $config['supplier'];
            }catch(\Exception $e){
                $this->initializeNoActivity();
            }
        }else{
            $this->initializeNoActivity();
        }
        return true;
    }

    protected function initializeNoActivity(){
        $this->_time = [
            'from' => 0,
            'to' => -1,
        ];
    }
}
