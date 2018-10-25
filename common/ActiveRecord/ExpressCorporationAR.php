<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class ExpressCorporationAR extends ActiveRecord{

    public static function tableName(){
        return '{{%express_corporation}}';
    }
}
