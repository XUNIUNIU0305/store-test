<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessUserWalletAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_wallet}}';
    }
}
