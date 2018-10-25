<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessUserAchievementDayAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_achievement_day}}';
    }
}
