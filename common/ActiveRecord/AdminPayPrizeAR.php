<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-12
 * Time: 下午9:58
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class AdminPayPrizeAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_pay_prize}}';
    }
}