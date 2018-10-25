<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminWalletAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_wallet}}';
    }
}
