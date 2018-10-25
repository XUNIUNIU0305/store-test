<?php
/**
 * Created by shuang.li on  2017/9/12 下午4:46.
 * Current file name CategoryName.php
 */
namespace custom\models\parts\search;
use common\ActiveRecord\ProductCategoryAR;
use SplObserver;
use SplSubject;
use Yii;
class CategoryNameObserver implements SplObserver
{
    public function update(SplSubject $subject)
    {
        $keyword = $subject->keyword;
        for ($i = 0, $cnt = count($keyword); $i < $cnt; $i++){
            //去ProductCategory keyword搜索
            $subject->result[$i]['category_name'] = $this->getCategory($keyword[$i]);
        }
    }

    private function getCategory($keyword)
    {
        $category = Yii::$app->RQ->AR(new ProductCategoryAR())->column([
            'select' => ['id'],
            'where' => [
                'is_end' => 1,
                'display'=>1
            ],
            'andWhere' => [
                'like',
                'title',
                $keyword
            ]
        ]);

        return [
            'category_id'=>$category
        ];

    }

}
