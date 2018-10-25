<?php
namespace custom\models\parts\search;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductCategoryAR;
use common\models\parts\Product;
use SplObserver;
use SplSubject;

use Yii;
class CategoryKeywordObserver implements SplObserver {

    public function update(SplSubject $subject)
    {
        $keyword = $subject->keyword;
        for ($i=0,$cnt = count($keyword);$i<$cnt;$i++){
            //1：处理第一个关键字
            //去分类表 keyword搜索
            $subject->result[$i]['category_keyword'] = $this->getCategory($keyword[$i],$subject->filterWhere);
        }
    }

    private function getCategory($keyword,$filterWhere){
        $category =  Yii::$app->RQ->AR(new ProductCategoryAR())->column([
            'select'=>['id'],
            'where'=>[
                'is_end'=>1,
                'display'=>1
            ],
            'andWhere'=>['like','keyword',$keyword]
        ]);
        $product = ProductAR::find()->select('id')
            ->where([
                'sale_status'=>Product::SALE_STATUS_ONSALE
            ])
            ->andWhere(['in','product_category_id',$category])
            ->andWhere(['<=','customer_limit',$filterWhere['level']])
            ->andWhere(['not in','id',$filterWhere['limit_product_id']])
            ->column();
        return [
            'good_id'=>$product,
            'category_id'=>$category
        ];

    }
}
