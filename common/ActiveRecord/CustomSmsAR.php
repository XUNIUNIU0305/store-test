<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 16:38
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class CustomSmsAR extends  ActiveRecord
{

    public static function tableName()
    {
        return '{{%custom_sms}}';
    }

}