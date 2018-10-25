<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class SupplyUserWalletAR extends ActiveRecord{

    public static function tableName(){
        return '{{%supply_user_wallet}}';
    }
}
