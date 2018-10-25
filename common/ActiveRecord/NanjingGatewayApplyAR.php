<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingGatewayApplyAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_gateway_apply}}';
    }
}
