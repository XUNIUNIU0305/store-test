<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/15
 * Time: 上午9:58
 */

namespace custom\models\parts\search;


use common\ActiveRecord\CustomSearchKeywordAR;
use SplSubject;
use yii\base\Object;
use SplObserver;
use Yii;
class CountKeywordObserver extends Object implements SplObserver {

    public function update(SplSubject $subject)
    {
        $keyword = $subject->keyword;
        for ($i = 0, $cnt = count($keyword); $i < $cnt; $i++){
            //统计搜字段
            $this->save($keyword[$i]);
        }
    }

    private function save($keyword){
        $model = Yii::$app->RQ->AR(new CustomSearchKeywordAR());
        if ($model->exists(['where'=>['keyword'=>$keyword]])){
            CustomSearchKeywordAR::findOne(['keyword'=>$keyword])->updateCounters(['num'=>1]);
        }else{
            $model->insert(['keyword' => $keyword]);
        }
    }

}