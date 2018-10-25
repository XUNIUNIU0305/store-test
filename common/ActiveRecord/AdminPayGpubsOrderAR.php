<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminPayGpubsOrderAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_pay_gpubs_order}}';
        
    }
}
