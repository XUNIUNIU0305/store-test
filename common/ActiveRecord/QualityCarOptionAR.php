<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午4:42
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class QualityCarOptionAR extends ActiveRecord
{
    public static function tableName(){
        return '{{%quality_car_option}}';
    }
}