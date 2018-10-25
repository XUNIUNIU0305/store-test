<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminRechargeApplyAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_recharge_apply}}';
    }
}
