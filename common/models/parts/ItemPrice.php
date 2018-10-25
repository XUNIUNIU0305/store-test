<?php
namespace common\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\ProductSKUAR;
use yii\base\InvalidConfigException;

class ItemPrice extends Object{

    public $itemId;

    //税率、加价率 %
    public $tax = 0;
    public $markup = 0;

    protected $AR;

    public function init(){
        if(!is_null($this->itemId)){
            if(!$this->itemId ||
                (!$this->AR = ProductSKUAR::findOne($this->itemId))
            )throw new InvalidConfigException('unavailable item id');
        }
        if($this->tax < 0 || $this->markup < 0)throw new InvalidConfigException('incorrent rate');
    }

    /**
     * 获取商品实际售价
     *
     * @return string
     */
    public function __toString(){
        return (string)$this->getPrice();
    }

    /**
     * 获取商品成本价
     *
     * @return float
     */
    public function getCostPrice(){
        return is_null($this->AR) ? false : (float)$this->AR->cost_price;
    }

    /**
     * 获取商品建议售价
     *
     * @return float
     */
    public function getGuidancePrice(){
        return is_null($this->AR) ? false : (float)$this->AR->guidance_price;
    }

    /**
     * 获取商品实际售价
     *
     * @return float
     */
    public function getPrice(){
        return is_null($this->AR) ? false : (float)$this->AR->price;
    }

    /**
     * 根据商品成本价乘以税率、加价率生成售价
     *
     * @return float
     */
    public function generatePrice(float $costPrice){
        return (float)bcmul($costPrice * (1 + $this->tax * 0.01), 1 + $this->markup * 0.01, 2);
    }
}
