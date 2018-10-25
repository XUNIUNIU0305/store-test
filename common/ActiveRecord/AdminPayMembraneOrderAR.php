<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-17
 * Time: 下午6:29
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class AdminPayMembraneOrderAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_pay_membrane_order}}';
    }
}