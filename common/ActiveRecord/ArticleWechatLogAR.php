<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ArticleWechatLogAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%article_wechat_log}}';
    }
}