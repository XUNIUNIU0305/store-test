<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;
 
class PartnerPromoterAR extends ActiveRecord{

    public static function tableName(){
        return '{{%partner_promoter}}';
    }
}
