<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminArticleAR extends ActiveRecord
{
    //删除
    const IS_DEL = 1;

    const NOT_DEL = 0;


    public static function tableName()
    {
        return '{{%admin_article}}';
    }
}