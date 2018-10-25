<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-13
 * Time: 下午2:25
 */

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminUnbindCustomMobileLogAR extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin_unbind_custom_mobile_log}}";
    }
}