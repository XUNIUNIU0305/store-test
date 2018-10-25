<?php
namespace common\models\parts;

use Yii;
use yii\base\Object;
use common\models\RapidQuery;
use common\ActiveRecord\ProductSKUAR;
use common\ActiveRecord\ProductSKUAttributeAR;
use common\ActiveRecord\ProductSKUOptionAR;
use yii\base\InvalidConfigException;

class ProductSKU extends Object{

    //商品id
    public $productId;

    public function init(){
        if(is_null($this->productId))throw new InvalidConfigException;
    }

    /**
     * 获取价格区间
     *
     * @return array
     */
    public function getPriceInterval(){
        $price = (new RapidQuery(new ProductSKUAR))->column([
            'select' => ['price'],
            'where' => ['product_id' => $this->productId],
        ]);
        $price = array_map(function($str){
            return floatval($str);
        }, $price);
        return [
            'min' => min($price),
            'max' => max($price),
        ];
    }

    /**
     * 获取商品销售属性
     *
     * @return array
     */
    public function getAttribute(){
        $attribute = (new RapidQuery(new ProductSKUAttributeAR))->all([
            'select' => ['id', 'name'],
            'where' => ['product_id' => $this->productId],
        ]);
        return array_column($attribute, 'name', 'id');
    }

    /**
     * 获取销售属性的选项
     *
     * @return array
     */
    public function getOption(){
        $option = (new RapidQuery(new ProductSKUOptionAR))->all([
            'select' => ['id', 'product_sku_attribute_id', 'name'],
            'where' => ['product_id' => $this->productId],
        ]);
        $optionId = array_column($option, 'id');
        $optionData = array_map(function($data){
            return [
                'attribute' => $data['product_sku_attribute_id'],
                'name' => $data['name'],
            ];
        }, $option);
        return array_combine($optionId, $optionData);
    }

    /**
     * 获取销售属性及选项
     *
     * @return array
     */
    public function getAttributeWithOption(){
        $attributes = $this->getAttribute();
        $options = $this->getOption();
        foreach($options as $id => $option){
            $attributeWithOption[$option['attribute']][$attributes[$option['attribute']]][$id] = $option['name'];
        }
        return $attributeWithOption ?? [];
    }

    /**
     * 获取sku数据
     *
     * @return array
     */
    public function getSKU(){
        $sku = (new RapidQuery(new ProductSKUAR))->all([
            'select' => ['id', 'sku_cartesian', 'cost_price', 'guidance_price', 'price', 'stock', 'custom_id', 'bar_code', 'original_price', 'original_guidance_price'],
            'where' => ['product_id' => $this->productId],
        ]);
        $skuId = array_column($sku, 'sku_cartesian');
        $skuData = array_map(function($data){
            unset($data['sku_cartesian']);
            return $data;
        }, $sku);
        return array_combine($skuId, $skuData);
    }

    /**
     * 修改sku数据
     *
     * @param array $sku sku数据
     * [
     *     product_sku表id => [product_sku表字段名 => product_sku表字段值],
     *     ...
     * ]
     *
     * @return boolean
     */
    public function modify(array $sku){
        $itemPrice = new ItemPrice;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($sku as $skuId => $fields){
                if($skuAR = ProductSKUAR::findOne(['id' => $skuId, 'product_id' => $this->productId])){
                    foreach($fields as $fieldName => $fieldValue){
                        $skuAR->$fieldName = $fieldValue;
                        if(($queryResult = $skuAR->update()) === false)throw new \Exception;
                        if($fieldName == 'cost_price' && $queryResult > 0){
                            $skuAR->price = $itemPrice->generatePrice((float)$fieldValue);
                            if($skuAR->update() === false)throw new \Exception;
                        }
                    }
                }else{
                    throw new \Exception;
                }
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
