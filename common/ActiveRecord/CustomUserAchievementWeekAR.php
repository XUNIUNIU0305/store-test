<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class CustomUserAchievementWeekAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_achievement_week}}';
    }
}
