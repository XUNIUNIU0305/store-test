<?php
namespace custom\models\parts\trade\nanjing;

use Yii;
use yii\db\ActiveRecord;
use common\ActiveRecord\CustomUserAR;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;

class CustomerAccount extends AccountAbstract{

    public function getActiveRecord() : ActiveRecord{
        return new CustomUserAR;
    }

    public function getAccountField() : string{
        return 'account';
    }

    public function getUserType() : int{
        return self::ACCOUNT_TYPE_CUSTOM;
    }
}
