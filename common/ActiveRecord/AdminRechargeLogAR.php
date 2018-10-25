<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminRechargeLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_recharge_log}}';
    }
}
