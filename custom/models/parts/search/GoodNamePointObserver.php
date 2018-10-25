<?php
/**
 * Created by shuang.li on  2017/9/12 下午4:46.
 * Current file name GoodNamePoint.php
 */
namespace custom\models\parts\search;
use common\ActiveRecord\ProductAR;
use common\models\parts\Product;
use SplObserver;
use SplSubject;

use Yii;
class GoodNamePointObserver implements SplObserver {
    public function update(SplSubject $subject)
    {
        $keyword = $subject->keyword;
        for ($i=0,$cnt = count($keyword);$i<$cnt;$i++){
            //1：处理第一个关键字
            //去Product keyword搜索
            $subject->result[$i]['good_name_point'] = $this->getProduct($keyword[$i],$subject->filterWhere);
        }
    }

    private function getProduct($keyword,$filterWhere){
        $product =  ProductAR::find()->select('id')
            ->where(['sale_status'=>Product::SALE_STATUS_ONSALE])
            ->andWhere(['<=','customer_limit',$filterWhere['level']])
            ->andWhere(['or', ['like','title',$keyword], ['like','description',$keyword]])
            ->andWhere(['not in','id',$filterWhere['limit_product_id']])
            ->column();
        return [
            'good_id'=>$product,
        ];
    }
}
