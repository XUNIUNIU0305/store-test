<?php
/**
 * Created by shuang.li on  2017/9/12 下午4:44.
 * Current file name GoodKeyword.php
 */
namespace custom\models\parts\search;
use common\ActiveRecord\ProductAR;
use common\models\parts\Product;
use SplObserver;
use SplSubject;
use Yii;
class GoodKeywordObserver implements SplObserver {
    public function update(SplSubject $subject)
    {
        $keyword = $subject->keyword;
        for ($i=0,$cnt = count($keyword);$i<$cnt;$i++){
            //1：处理第一个关键字
            //去商品表 keyword搜索
            $product = $this->getProduct($keyword[$i],$subject->filterWhere);
            //获取商品id
            $goodIds = $product ? array_column($product,'id') : [];
            //根据商品id查询三级分类id
            $endCategoryId = $product ? array_unique(array_column($product,'product_category_id')) : [];
            //写入result 数组
            $subject->result[$i]['good_keyword'] = [
                'good_id'=>$goodIds,
                'category_id'=>$endCategoryId
            ];

        }
    }

    private function getProduct($keyword,$filterWhere){
        //获取满足条件的商品
        return  ProductAR::find()->select(['id','product_category_id'])
            ->where(['sale_status'=>Product::SALE_STATUS_ONSALE])
            ->andWhere(['like','keyword',$keyword])
            ->andWhere(['<=','customer_limit',$filterWhere['level']])
            ->andWhere(['not in','id',$filterWhere['limit_product_id']])
            ->asArray()->all();
    }
}
