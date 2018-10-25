<?php

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ActivityGroupbuyAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%activity_groupbuy}}';
    }
}
