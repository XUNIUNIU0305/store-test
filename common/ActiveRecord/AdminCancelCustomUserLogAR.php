<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-13
 * Time: 下午2:25
 */

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminCancelCustomUserLogAR extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin_cancel_custom_user_log}}";
    }
}