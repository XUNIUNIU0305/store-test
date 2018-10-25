<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessAreaAchievementDayAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_area_achievement_day}}';
    }
}
