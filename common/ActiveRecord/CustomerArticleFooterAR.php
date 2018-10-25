<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomerArticleFooterAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%customer_article_footer}}';
    }
}