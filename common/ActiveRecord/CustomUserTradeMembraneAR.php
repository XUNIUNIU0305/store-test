<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 12:01
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class CustomUserTradeMembraneAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%custom_user_trade_membrane}}';
    }
}