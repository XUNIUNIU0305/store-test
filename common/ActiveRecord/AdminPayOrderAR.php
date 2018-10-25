<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminPayOrderAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_pay_order}}';
    }
}
