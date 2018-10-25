<?php
namespace common\ActiveRecord;

use admin\models\parts\role\AuthItem;
use Yii;
use yii\db\ActiveRecord;

class AdminUserAR extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%admin_user}}';
    }


}
