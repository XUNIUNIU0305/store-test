<?php
namespace custom\models\parts\search;
use common\ActiveRecord\ProductCategoryAttributeAR;
use common\ActiveRecord\ProductSPUOptionAR;
use SplObserver;
use SplSubject;
use Yii;
class CategoryAttributeObserver implements SplObserver {
    public function update(SplSubject $subject)
    {
        $keyword = $subject->keyword;
        for ($i=0,$cnt = count($keyword);$i<$cnt;$i++){
            //1：处理第一个关键字
            //去ProductSPUOption keyword搜索
            $subject->result[$i]['category_attribute'] = $this->getCategory($keyword[$i]);
        }

    }

    public function getCategory($keyword){
        $attributeId = Yii::$app->RQ->AR(new ProductSPUOptionAR())->column([
            'select'=>['product_spu_attribute_id'],
            'where'=>['like','name',$keyword],
            'andWhere'=>['display'=>1],
        ]);

        $categoryId = Yii::$app->RQ->AR(new ProductCategoryAttributeAR())->column([
            'select'=>['product_category_id'],
            'where'=>[
                'product_spu_attribute_id'=>array_unique($attributeId)
            ]
        ]);

        return [
            'category_id'=>array_unique($categoryId),
        ];
    }



}
