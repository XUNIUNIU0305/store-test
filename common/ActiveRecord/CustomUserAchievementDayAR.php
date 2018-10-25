<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class CustomUserAchievementDayAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_achievement_day}}';
    }
}
