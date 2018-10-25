<?php

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class QualityImportLogAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%quality_import_log}}';
    }
}
