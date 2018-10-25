<?php
namespace common\models\parts;

use Yii;
use common\models\RapidQuery;
use common\models\parts\basic\AttributeAbstract;
use common\ActiveRecord\ProductSPUAR;
use yii\base\InvalidParamException;

class ProductSPU extends AttributeAbstract{

    //商品id
    public $productId;

    public function init(){
        if(is_null($this->productId))throw new InvalidParamException;
    }

    public function modifyOption($attributeId, $optionId, $return = 'throw'){
        if(!$attributeAR = ProductSPUAR::findOne([
            'product_id' => $this->productId,
            'product_spu_attribute_id' => $attributeId,
        ]))return $this->selectNewAttribute($attributeId, $optionId, $return);
        $options = array_column(self::getOptions($attributeId), 'id');
        if(!in_array($optionId, $options))return Yii::$app->EC->callback($return, 'unavailable option id');
        if($attributeAR->product_spu_option_id == $optionId)return true;
        return Yii::$app->RQ->AR($attributeAR)->update([
            'product_spu_option_id' => $optionId,
        ], $return);
    }

    public function selectNewAttribute($attributeId, $optionId, $return = 'throw'){
        if(ProductSPUAR::findOne([
            'product_id' => $this->productId,
            'product_spu_attribute_id' => $attributeId,
        ]))return Yii::$app->EC->callback($return, 'this attributs has been added');
        $categoryAttribute = new CategoryAttribute(['categoryId' => (new Product(['id' => $this->productId]))->category]);
        if(!in_array($attributeId, $categoryAttribute->attributes))return Yii::$app->EC->callback($return, 'unavailable attribute id');
        if(!in_array($optionId, array_column(self::getOptions($attributeId), 'id')))return Yii::$app->EC->callback($return, 'unavailable option id');
        return Yii::$app->RQ->AR(new ProductSPUAR)->insert([
            'product_id' => $this->productId,
            'product_spu_attribute_id' => $attributeId,
            'product_spu_option_id' => $optionId,
        ], $return);
    }

    /**
     * inherit
     *
     * @return array
     */
    public function getAttributes(){
        return (new RapidQuery(new ProductSPUAR))->column([
            'select' => ['product_spu_attribute_id'],
            'where' => ['product_id' => $this->productId],
        ]);
    }

    /**
     * 获取该商品选择的属性选项
     *
     * @return array
     */
    public function getSelectedOptions(){
        $selectedOptions = (new RapidQuery(new ProductSPUAR))->all([
            'select' => ['product_spu_attribute_id', 'product_spu_option_id'],
            'where' => ['product_id' => $this->productId],
        ]);
        return array_column($selectedOptions, 'product_spu_option_id', 'product_spu_attribute_id');
    }

    /**
     * inherit
     *
     * @return array
     */
    public function getAttributesWithOptions(){
        $attributesId = $this->getAttributes();
        $attributesName = self::getAttributeName($attributesId);
        $attributesOptions = self::getOptions($attributesId);
        $selectedOptions = $this->getSelectedOptions();
        return array_map(function($attributeId)use($attributesName, $attributesOptions, $selectedOptions){
            return [
                'id' => $attributeId,
                'name' => $attributesName[$attributeId],
                'options' => $attributesOptions[$attributeId],
                'selectedOption' => $selectedOptions[$attributeId],
            ];
        }, $attributesId);
    }
}
