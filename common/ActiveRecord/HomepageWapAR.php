<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-6
 * Time: 上午9:54
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class HomepageWapAR extends ActiveRecord
{
    const IS_DEL_YES = 1;
    const IS_DEL_NO = 0;

    public static function tableName()
    {
        return '{{%homepage_wap}}';
    }
}