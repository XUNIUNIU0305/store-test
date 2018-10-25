<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-20
 * Time: 上午11:33
 */

namespace mobile\models;


use common\ActiveRecord\ProductSKUAR;
use common\ActiveRecord\ProductSKUAttributeAR;
use common\ActiveRecord\ProductSKUOptionAR;
use common\models\Object;

/**
 * Class Sku
 * @package wechat\models
 * @property $custom_user_id
 * @property $product_sku_id
 * @property $product_id
 * @property $sku_cartesian
 * @property $cost_price
 * @property $guidance_price
 * @property $price
 * @property $stock
 */
class Sku extends Object
{
    public static function getInstanceById($id)
    {
        if($ar = ProductSKUAR::findOne($id)){
            return new static(['ar' => $ar]);
        }
    }

    public static function getInstancesByArray($items):array
    {
        foreach ($items as &$item){
            $item = new static(['ar' => $item]);
        }
        return $items;
    }

    /**
     * 获取属性
     */
    public function getSkuAttributes()
    {
        $skuCartesian = $this->sku_cartesian;
        $skuCartesian = explode(';', $skuCartesian);
        $res = [];
        foreach ($skuCartesian as $value){
            $value = explode(':', $value);
            $res[] = [
                'attribute' => $value[0],
                'option' => $value[1]
            ];
        }
        $optionId = array_column($res, 'option');
        $attributeId = array_column($res, 'attribute');
        $optionItems = ProductSKUOptionAR::find()
            ->select(['name', 'id'])
            ->where(['id' => $optionId])
            ->indexBy('id')->asArray()->all();
        $attributeItems = ProductSKUAttributeAR::find()
            ->select(['name', 'id'])
            ->where(['id' => $attributeId])
            ->indexBy('id')->asArray()->all();

        foreach ($res as &$val){
            $optionItem = $optionItems[$val['option']] ?? [];
            $attributeItem = $attributeItems[$val['attribute']] ?? [];
            $attributeItem['selected_option'] = $optionItem;
            $val = $attributeItem;
        }
        return $res;
    }
}
