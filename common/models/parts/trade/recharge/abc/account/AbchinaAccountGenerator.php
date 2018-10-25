<?php
namespace common\models\parts\trade\recharge\abc\account;

use Yii;
use yii\base\Object;
use common\models\parts\custom\CustomUser;
use yii\base\InvalidConfigException;
use common\ActiveRecord\AbchinaAccountAR;
use common\models\parts\trade\recharge\abc\Abc;

class AbchinaAccountGenerator extends Object{

    public $account;

    private static $_abc;

    public function init(){
        if(!($this->account instanceof CustomUser)){
            throw new InvalidConfigException('unavailable account');
        }
        self::$_abc = new Abc;
    }

    public function generate($return = 'throw'){
        if($this->account instanceof Customuser){
            return $this->generateCustomAccount($this->account, $return);
        }else{
            return Yii::$app->EC->callback($return, 'unavailable account configuration');
        }
    }

    protected function generateCustomAccount(CustomUser $account, $return = 'throw'){
        if($id = Yii::$app->RQ->AR(new AbchinaAccountAR)->scalar([
            'select' => ['id'],
            'where' => [
                'user_id' => $account->id,
                'user_type' => AbchinaAccount::ACCOUNT_TYPE_CUSTOM,
            ],
        ])){
            return new AbchinaAccount([
                'id' => $id,
            ]);
        }
        if($dealerInfo = self::$_abc->achieveDealerInfo(AbchinaAccount::ACCOUNT_TYPE_CUSTOM . $account->account)){
            $abchinaUserid = $dealerInfo['DealerNum'];
            $ebizDealerNo = $dealerInfo['DealerOrgId'];
            $userId = $account->id;
            $userType = AbchinaAccount::ACCOUNT_TYPE_CUSTOM;
            $userAccount = $account->account;
            $dealerName = $dealerInfo['DealerName'];
            $contact = $dealerInfo['Contact'];
            $contactTel = $dealerInfo['ContactPhone'];
            $needAddAccount = false;
        }else{
            $abchinaUserid = AbchinaAccount::ACCOUNT_TYPE_CUSTOM . $account->account;
            $userId = $account->id;
            $userType = AbchinaAccount::ACCOUNT_TYPE_CUSTOM;
            $userAccount = $account->account;
            $dealerName = $account->account;
            $contact = $account->account;
            $contactTel = 10000000000 + $account->account;
            $needAddAccount = true;
        }
        if($needAddAccount){
            if(!$ebizDealerNo = $this->addDealerToAbchina(
                $abchinaUserid,
                $dealerName,
                $contact,
                $contactTel
            ))return Yii::$app->EC->callback($return, 'add dealer to abchina failed');
        }
        $insertId = $this->insertAccountInfo([
            'abchinaUserid' => $abchinaUserid,
            'ebizDealerNo' => $ebizDealerNo,
            'userId' => $userId,
            'userType' => $userType,
            'userAccount' => $userAccount,
            'dealerName' => $dealerName,
            'contact' => $contact,
            'contactTel' => $contactTel,
        ], false);
        if(!$insertId)return Yii::$app->EC->callback($return, 'insert account data failed');
        return new AbchinaAccount([
            'id' => $insertId,
        ]);
    }

    protected function insertAccountInfo(array $data, $return = 'throw'){
        $abchinaUserid = null;
        $ebizDealerNo = null;
        $userId = null;
        $userType = null;
        $userAccount = null;
        $dealerName = null;
        $contact = null;
        $contactTel = null;
        $className = '';
        $areaName = '';
        $addInfo = '';
        extract($data, EXTR_IF_EXISTS);
        return Yii::$app->RQ->AR(new AbchinaAccountAR)->insert([
            'abchina_userid' => $abchinaUserid,
            'ebiz_dealer_no' => $ebizDealerNo,
            'user_id' => $userId,
            'user_type' => $userType,
            'user_account' => $userAccount,
            'dealer_name' => $dealerName,
            'contact' => $contact,
            'contact_tel' => $contactTel,
            'class_name' => $className,
            'area_name' => $areaName,
            'add_info' => $addInfo,
        ], $return);
    }

    protected function addDealerToAbchina(string $dealerNo, string $dealerName, string $contact, string $contactTel){
        $result = self::$_abc->addDealer($dealerNo, $dealerName, $contact, $contactTel);
        return isset($result['msg']['Value']['EbizDealerNO']) ? $result['msg']['Value']['EbizDealerNO'] : false;
    }
}
