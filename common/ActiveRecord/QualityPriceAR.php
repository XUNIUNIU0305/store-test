<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:31
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class QualityPriceAR extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%quality_price}}';
    }

}