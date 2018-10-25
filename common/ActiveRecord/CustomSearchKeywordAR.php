<?php
namespace common\ActiveRecord;
use yii\db\ActiveRecord;

class CustomSearchKeywordAR extends  ActiveRecord
{

    public static function tableName()
    {
        return '{{%custom_search_keyword}}';
    }

}