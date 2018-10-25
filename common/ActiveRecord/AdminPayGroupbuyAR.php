<?php

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class AdminPayGroupbuyAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_pay_groupbuy}}';
    }
}