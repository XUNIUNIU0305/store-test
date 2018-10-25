<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-8
 * Time: 上午10:19
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class HomepageBrandAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%homepage_brand}}';
    }
}