<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ExpressChangeLogAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%express_change_log}}';
    }
}