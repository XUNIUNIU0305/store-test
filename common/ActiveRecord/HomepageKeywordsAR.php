<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-6
 * Time: 下午3:09
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class HomepageKeywordsAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%homepage_keywords}}';
    }
}