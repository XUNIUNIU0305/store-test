<?php
namespace common\models\parts\basic;

use Yii;
use common\ActiveRecord\ProductSPUAttributeAR;
use common\ActiveRecord\ProductSPUOptionAR;
use common\models\RapidQuery;
use yii\base\InvalidParamException;
use yii\base\Object;

abstract class AttributeAbstract extends Object{

    /**
     * 获取属性名称
     *
     * @return array | string
     */
    public static function getAttributeName($attributeId){
        if(is_array($attributeId)){
            $attributeName = ProductSPUAttributeAR::find()->select(['id', 'name'])->where(['id' => $attributeId])->asArray()->all();
            return array_column($attributeName, 'name', 'id');
        }else{
            return ProductSPUAttributeAR::find()->select(['name'])->where(['id' => $attributeId])->scalar();
        }
    }

    /**
     * 获取选项
     *
     * @return array
     */
    public static function getOptions($attributeId){
        if(is_array($attributeId)){
            $options = ProductSPUOptionAR::find()->select(['id', 'product_spu_attribute_id', 'name'])->where(['product_spu_attribute_id' => $attributeId,'display'=>ProductSPUOptionAR::DISPLAY])->orderBy(['sort' => SORT_ASC])->asArray()->all();
            $sortedOptions = array_fill_keys($attributeId, []);
            if(!empty($options)){
                foreach($options as $option){
                    $attributeId = $option['product_spu_attribute_id'];
                    unset($option['product_spu_attribute_id']);
                    $sortedOptions[$attributeId][] = $option;
                }
            }
            return $sortedOptions;
        }else{
            return ProductSPUOptionAR::find()->select(['id', 'name'])->where(['product_spu_attribute_id' => $attributeId,'display'=>ProductSPUOptionAR::DISPLAY])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        }
    }

    /**
     * 获取属性
     *
     * @return array [attr1, attr2, attr3]
     */
    abstract public function getAttributes();

    /**
     * 获取带选项的属性
     *
     * @return array
     */
    abstract public function getAttributesWithOptions();
}
