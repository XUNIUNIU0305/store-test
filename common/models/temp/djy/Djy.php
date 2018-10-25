<?php
namespace common\models\temp\djy;

use Yii;
use yii\base\Object;
use common\ActiveRecord\ProductSKUAR;

class Djy extends Object{

    const LONG_CACHE_EXPIRE = 86400;
    const SHORT_CACHE_EXPIRE = 60;
    const CACHE_CONFIG = 'djy_config';

    private $_productId = false;
    private $_skuIds = false;
    private $_start = false;
    private $_end = false;
    private $_commanders = false;

    /**
     * config
     * return [
     *     'product_id' => `integer`,
     *     'datetime_start' => `datetime`,
     *     'datetime_end' => `datetime`,
     * ];
     */
    public function init(){
        $this->_commanders = new Commanders;
        if($config = Yii::$app->cache->get(self::CACHE_CONFIG)){
            $this->_productId = $config['productId'];
            $this->_skuIds = $config['skuIds'];
            $this->_start = $config['start'];
            $this->_end = $config['end'];
        }else{
            $configFile = __DIR__ . '/config/config.php';
            if(is_readable($configFile)){
                $config = include($configFile);
                $this->_productId = $config['product_id'] ?? false;
                $this->_start = $config['datetime_start'] ?? false;
                $this->_end = $config['datetime_end'] ?? false;
            }
            if($this->_productId){
                $this->_skuIds = ProductSKUAR::find()->
                    select(['id'])->
                    where(['product_id' => $this->_productId])->
                    column();
            }
            if($this->isValid()){
                Yii::$app->cache->set(self::CACHE_CONFIG, [
                    'productId' => $this->_productId,
                    'skuIds' => $this->_skuIds,
                    'start' => $this->_start,
                    'end' => $this->_end,
                ], self::LONG_CACHE_EXPIRE);
            }
        }
    }

    public function getProductId(){
        return $this->_productId;
    }

    public function getSkuIds(){
        return $this->_skuIds;
    }

    public function getStart(){
        return $this->_start;
    }

    public function getEnd(){
        return $this->_end;
    }

    public function getCommanders(){
        return $this->_commanders;
    }

    public function isValid(){
        return ($this->_productId && $this->_skuIds && $this->_start && $this->_end && $this->_commanders->isValid());
    }

    public function reset(){
        Yii::$app->cache->delete(self::CACHE_CONFIG);
    }
}
