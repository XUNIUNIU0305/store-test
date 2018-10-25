<?php
namespace business\models\parts\trade\nanjing;

use Yii;
use yii\db\ActiveRecord;
use common\ActiveRecord\BusinessUserAR;
use common\models\parts\trade\recharge\nanjing\account\NanjingAccount;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;
use business\models\parts\trade\Wallet;

class BusinessAccount extends AccountAbstract{

    private $_mobilePhone;

    public function getActiveRecord() : ActiveRecord{
        return new BusinessUserAR;
    }

    public function getAccountField() : string{
        return 'account';
    }

    public function getUserType() : int{
        return self::ACCOUNT_TYPE_BUSINESS;
    }

    public function getMobilePhone() : string{
        if(is_null($this->_mobilePhone)){
            $this->_mobilePhone = BusinessUserAR::findOne($this->id)->mobile;
        }
        return $this->_mobilePhone;
    }

    public function getWallet(){
        return new Wallet(['userId' => $this->id]);
    }
}
