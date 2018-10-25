<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 下午3:12
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * 首页控制一级栏目
 * Class HomepageColumnAR
 * @package common\ActiveRecord
 * @property $name string
 */
class HomepageColumnAR extends ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%homepage_column}}';
    }
}