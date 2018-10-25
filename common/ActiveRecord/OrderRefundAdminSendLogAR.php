<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class OrderRefundAdminSendLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%order_refund_admin_send_log}}';
    }
}
