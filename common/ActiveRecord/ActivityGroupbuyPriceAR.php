<?php

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ActivityGroupbuyPriceAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%activity_groupbuy_price}}';
    }
}
