<?php

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ActivityGroupbuyOrderAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%activity_groupbuy_order}}';
    }
}
