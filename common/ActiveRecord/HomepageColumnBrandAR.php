<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 下午3:16
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class HomepageColumnBrandAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%homepage_column_brand}}';
    }
}