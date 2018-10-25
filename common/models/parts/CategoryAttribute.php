<?php
namespace common\models\parts;

use Yii;
use common\models\parts\basic\AttributeAbstract;
use common\ActiveRecord\ProductCategoryAttributeAR;
use yii\base\InvalidConfigException;
use common\models\RapidQuery;

class CategoryAttribute extends AttributeAbstract{

    //分类ID
    public $categoryId;

    public $category;

    protected $categoryObj;

    public function init(){
        if(is_null($this->categoryId) && (is_null($this->category) || !($this->category instanceof ProductCategory)))throw new InvalidConfigException;
        $this->categoryObj = $this->category ?? new ProductCategory(['id' => $this->categoryId]);
    }

    /**
     * 获取当前分类的所有属性
     *
     * @return array
     */
    public function getAttributes(){
        return ProductCategoryAttributeAR::find()->select(['product_spu_attribute_id'])->where(['product_category_id' => $this->categoryObj->id])->orderBy(['sort' => SORT_ASC])->asArray()->column();
    }

    /**
     * 获取带选项的属性
     *
     * @return array
     */
    public function getAttributesWithOptions(){
        $attributesId = $this->getAttributes();
        $attributesName = self::getAttributeName($attributesId);
        $attributesOptions = self::getOptions($attributesId);
        return array_map(function($attributeId)use($attributesName, $attributesOptions){
            return [
                'id' => $attributeId,
                'name' => $attributesName[$attributeId],
                'options' => isset($attributesOptions[$attributeId]) ? $attributesOptions[$attributeId] : [],
            ];
        }, $attributesId);
    }
}
