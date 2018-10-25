<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 下午3:14
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * 首页控制二级分类
 * Class HomepageColumnItemAR
 * @package common\ActiveRecord
 */
class HomepageColumnItemAR extends ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;


    public static function tableName()
    {
        return '{{%homepage_column_item}}';
    }
}