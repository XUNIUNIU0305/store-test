<?php

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class QualityImportLogYonineAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%quality_import_log_yonine}}';
    }
}
