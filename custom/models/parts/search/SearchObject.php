<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/12
 * Time: 下午5:05
 */

namespace custom\models\parts\search;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductCategoryAR;
use custom\models\parts\temp\OrderLimit\ProductLimit;
use SplObserver;
use SplSubject;
use yii\base\Object;
use Yii;
class SearchObject extends Object implements SplSubject {

    //搜索关键词
    public $keyword = [] ;

    protected $observers = [];

    //观察者处理后结果集
    public $result = [] ;

    //过滤条件
    public $filterWhere = [];


    public function init(){
        if (is_string($this->keyword)){
            $this->keyword = preg_split("/[\s|,]+/", $this->keyword);
        }
        $this->filterWhere['level'] = Yii::$app->user->isGuest ? 2 : Yii::$app->CustomUser->CurrentUser->level;
        $this->filterWhere['limit_product_id'] = ProductLimit::getLimitProductId();
    }

    /**
     *====================================================
     * 绑定
     * @param SplObserver $observer
     * @author shuang.li
     *====================================================
     */
    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     *====================================================
     * 解除
     * @param SplObserver $removeToObserver
     * @author shuang.li
     *====================================================
     */
    public function detach(SplObserver $removeToObserver)
    {
        $this->observers = array_filter($this->observers,function($observer) use ($removeToObserver){
            return  $observer !== $removeToObserver;
        });
    }


    /**
     *====================================================
     * 通知观察者
     * @author shuang.li
     *====================================================
     */
    public function notify()
    {
        foreach ($this->observers as $observer){
            $observer->update($this);
        }
    }


    /**
     *====================================================
     * 获取处理结果
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getAnalysisResult(){
        $good = [];
        $category = [];
        for ($i = 0,$cnt = count($this->result);$i<$cnt;$i++){
           $good[$i] = array_unique(array_merge(
               $this->result[$i]['good_keyword']['good_id'],
               $this->result[$i]['category_keyword']['good_id'],
               $this->result[$i]['good_name_point']['good_id']
           ));
           $category[$i] = array_unique(array_merge(
               $this->result[$i]['category_keyword']['category_id'],
               $this->result[$i]['good_keyword']['category_id'],
               $this->result[$i]['category_name']['category_id'],
               $this->result[$i]['category_attribute']['category_id']
           ));
       }
       $goodId = array_reduce($good,function($carry, $item){
           return array_intersect($carry,$item);
       },$good[0]);
       
       $categoryId = array_reduce($category,function($carry, $item){
           return array_intersect($carry,$item);
       },$category[0]);

       $categoryId = $this->filterCategory($categoryId);
       $supplyUserId = $this->getBrand($goodId);
        
       return [
           'good_id'=> array_values($goodId),
           'category_id'=>array_values($categoryId),
           'brand_id'=>$supplyUserId,
       ];
    }


    private function getBrand($good){
        $supplyUserId = Yii::$app->RQ->AR(new ProductAR())->column([
            'select'=>['supply_user_id'],
            'where'=>[
                'id'=>$good
            ]
        ]);
        return array_values(array_unique($supplyUserId));
    }


    /**
     *====================================================
     * 过滤 删除的三级分类
     * @param $categoryId
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    private function filterCategory($categoryId){
        return Yii::$app->RQ->AR(new ProductCategoryAR())->column([
            'select' => ['id'],
            'where' => [
                'is_end' => 1,
                'display'=>1,
                'id'=>$categoryId
            ],
        ]);
    }




}