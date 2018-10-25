<?php
namespace common\models\parts;

use common\models\parts\supply\SupplyUser;
use Yii;
use yii\base\Object;
use common\ActiveRecord\ProductSKUAR;
use yii\base\InvalidConfigException;
use common\models\parts\ProductSKUGenerator;
use common\ActiveRecord\ProductSKUAttributeAR;
use common\ActiveRecord\ProductSKUOptionAR;
use common\models\parts\Product;

class Item extends Object{

    /**
     * product_sku表 ID
     */
    public $id;

    //ActiveRecord ProductSKUAR
    protected $AR;

    //Object Product
    protected $product;

    public function init(){
        if(!$this->id || 
            (!$this->AR = ProductSKUAR::findOne($this->id))
        )throw new InvalidConfigException;
    }

    /**
     * 获取商品ID
     *
     * @return integer
     */
    public function getProductId(){
        return $this->AR->product_id;
    }

    /**
     * 获取商品标题
     *
     * @return string
     */
    public function getTitle(){
        if(is_null($this->product))$this->initProductObj();
        return $this->product->title;
    }

    /**
     * 获取商品主图
     *
     * @return Object OSSImage
     */
    public function getMainImage(){
        if(is_null($this->product))$this->initProductObj();
        return $this->product->mainImage;
    }

    /**
     * 获取供应商ID
     *
     * @return integer
     */
    public function getSupplier($supply_id=false){
        if(!$supply_id) {
            return $this->AR->supply_user_id;
        }else{
            return new SupplyUser(['id'=>$this->AR->supply_user_id]);
        }
    }


    //获取优惠券所属供应商


    /**
     * 获取SKU
     *
     * @return string
     */
    public function getSKU(){
        return $this->AR->sku_cartesian;
    }

    /**
     * 获取单价
     *
     * @return float
     */
    public function getPrice(){
        return (float)$this->AR->price;
    }

    /**
     * 获取成本价
     *
     * @return float
     */
    public function getCostPrice(){
        return (float)$this->AR->cost_price;
    }

    /**
     * 获取建议售价
     *
     * @return float
     */
    public function getGuidancePrice(){
        return (float)$this->AR->guidance_price;
    }

    /**
     * 获取价格对象
     *
     * @return Object ItemPrice
     */
    public function getPriceSetting(){
        return new ItemPrice(['itemId' => $this->id]);
    }

    /**
     * 获取库存
     *
     * @return integer
     */
    public function getStock(){
        return $this->AR->stock;
    }

    public function decreaseStock(int $count){
        $count = abs($count);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $lineData = Yii::$app->db->createCommand("SELECT * FROM {{%product_sku}} WHERE [[id]] = :id FOR UPDATE")->bindValues([
                ':id' => $this->AR->id,
            ])->queryOne();
            if($count > $lineData['stock'])throw new \Exception;
            $result = Yii::$app->db->createCommand("UPDATE {{%product_sku}} SET [[stock]] = [[stock]] - :count WHERE [[id]] = :id")->bindValues([
                ':count' => $count,
                ':id' => $this->AR->id,
            ])->execute();
            if(!$result)throw new \Exception;
            $this->AR = ProductSKUAR::findOne($this->AR->id);
            $transaction->commit();
            return $result;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    public function increaseStock(int $count, $return = 'throw'){
        $count = abs($count);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->db->createCommand("SELECT * FROM {{%product_sku}} WHERE [[id]] = :id FOR UPDATE")->bindValues([
                ':id' => $this->AR->id,
            ])->queryOne();
            $result = Yii::$app->db->createCommand("UPDATE {{%product_sku}} SET [[stock]] = [[stock]] + :count WHERE [[id]] = :id")->bindValues([
                ':count' => $count,
                ':id' => $this->AR->id,
            ])->execute();
            if(!$result)throw new \Exception;
            $this->AR = ProductSKUAR::findOne($this->AR->id);
            $transaction->commit();
            return $result;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    /**
     * 获取自定义ID
     *
     * @return string
     */
    public function getCustomId(){
        return $this->AR->custom_id;
    }

    /**
     * 获取条形码
     *
     * @return string
     */
    public function getBarCode(){
        return $this->AR->bar_code;
    }

    /**
     * 获取商品对象
     *
     * @return Object Product
     */
    public function getProductObj(){
        if(is_null($this->product))$this->initProductObj();
        return $this->product;
    }

    /**
     * 获取商品销售状态
     *
     * @return integer
     */
    public function getSaleStatus(){
        if(is_null($this->product))$this->initProductObj();
        return $this->product->saleStatus;
    }

    /**
     * 获取item选择的属性及选项
     *
     * @return array
     */
    public function getAttributes(){
        $attributesWithOptions = $this->reverseCartesian();
        $attributes = [];
        foreach($attributesWithOptions as $one){
            $attribute = ProductSKUAttributeAR::findOne($one[0]);
            $option = ProductSKUOptionAR::findOne($one[1]);
            $attributes[] = [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'selectedOption' => [
                    'id' => $option->id,
                    'name' => $option->name,
                ],
            ];
        }
        return $attributes;
    }

    /**
     * 初始化self::$product对象
     */
    protected function initProductObj(){
        $this->product = new Product(['id' => $this->productId]);
    }

    /**
     * 反序列笛卡尔积，获取属性ID及选项ID
     *
     * @return array
     */
    protected function reverseCartesian(){
        $cartesian = $this->SKU;
        $attributesWithOptions = explode(ProductSKUGenerator::ATTRS_SPLIT, $cartesian);
        return array_map(function($i){
            return explode(ProductSKUGenerator::KEY_VALUE_SPLIT, $i);
        }, $attributesWithOptions);
    }
}
