<?php
namespace common\ActiveRecord;

use admin\models\parts\role\AuthItem;
use Yii;
use yii\db\ActiveRecord;

class AdminRolePermissionAR extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%admin_role_permission}}';
    }


}
