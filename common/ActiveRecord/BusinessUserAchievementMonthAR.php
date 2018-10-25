<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessUserAchievementMonthAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_achievement_month}}';
    }
}
