<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/10
 * Time: 10:58
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class SupplyUserPayLogAR extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%supply_user_pay_log}}';
    }

}