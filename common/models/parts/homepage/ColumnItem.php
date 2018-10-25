<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-31
 * Time: 上午9:15
 */

namespace common\models\parts\homepage;


use common\ActiveRecord\HomepageColumnItemAR;
use common\models\Object;

class ColumnItem extends Object
{
    public static function getInstanceById($id)
    {
        if($model = HomepageColumnItemAR::findOne($id)){
            return new static([
                'ar' => $model
            ]);
        }
        throw new \RuntimeException('分类ID无效');
    }

    public static function queryItemsByColumnId($id)
    {
        $res = HomepageColumnItemAR::find()
        ->where(['column_id' => $id])
        ->all();
        foreach ($res as &$record){
            $record = new static(['ar' => $record]);
        }
        return $res;
    }

    public function getImgUrl()
    {
        return \Yii::$app->params['OSS_PostHost'] . '/' . $this->AR->img;
    }
}