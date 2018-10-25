<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/14
 * Time: 下午6:46
 */

namespace admin\models\parts\template;

use common\ActiveRecord\AdminImageCarouselAR;
use yii\base\Object;
use Yii;

class Carousel extends Object
{
    const MAX = 7;

    //新增
    public static function add($carouselData)
    {
        if(!SELF::checkQuantity($carouselData)) {
            return false;
        }
        $carouselData['sort'] = 1;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            SELF::updateAllCarousel();
            $id = Yii::$app->RQ->AR(new AdminImageCarouselAR())->insert($carouselData);        
            $transaction->commit();
            return AdminImageCarouselAR::findOne(['id' => $id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
            
    }

    //检测数量限制
    public static function checkQuantity($data)
    {
        $count = current(AdminImageCarouselAR::find()->select('count(*) as count')->asArray()->all())['count'];
        return $count < self::MAX;
    }
    
    //更新所有的Carousel,sort值全部++,用来插入新数据
    public static function updateAllCarousel()
    {
        $allCarousel = AdminImageCarouselAR::find()->all();
        try {
            foreach($allCarousel as $carousel){
                if($carousel instanceof AdminImageCarouselAR) {
                    $carousel->sort++;
                    $carousel->update();
                }
            }
        } catch (\Exception $ex) {
            throw $e;
        }
    }

    //删除
    public function delete($obj)
    {
        return Yii::$app->RQ->AR(AdminImageCarouselAR::findOne($obj->id))->delete();
    }

    /**
     * 更新排序
     * @param $items
     * @return bool
     * @throws \Exception
     */
    public static function updateSort($items)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach ($items as $sort=>$id){
                AdminImageCarouselAR::updateAll(['sort' => ++$sort], ['id' => $id]);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }
}



