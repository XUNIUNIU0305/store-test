<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class PartnerApplyAR extends ActiveRecord{

    public static function tableName(){
        return '{{%partner_apply}}';
    }
}
