<?php
namespace common\models\parts\trade\recharge\abc\account;

use Yii;
use common\models\ObjectAbstract;
use common\models\parts\custom\CustomUser;
use common\ActiveRecord\AbchinaAccountAR;
use yii\base\InvalidConfigException;

class AbchinaAccount extends ObjectAbstract{

    public $id;

    const ACCOUNT_TYPE_CUSTOM = 1;
    const ACCOUNT_TYPE_SUPPLY = 2;
    const ACCOUNT_TYPE_BUSINESS = 3;
    const ACCOUNT_TYPE_ADMIN = 4;

    public function init(){
        if(!$this->AR = AbchinaAccountAR::findOne($this->id))throw new InvalidConfigException;
    }

    protected function _gettingList() : array{
        return [
            'abchina_userid',
            'ebiz_dealer_no',
            'user_id',
            'user_type',
            'user_account',
            'dealer_name',
            'contact',
            'contact_tel',
            'class_name',
            'area_name',
            'add_info',
        ];
    }

    protected function _settingList() : array{
        return [];
    }

    public static function generate($account, int $type = null, $return = 'throw'){
        if(is_numeric($account)){
            switch($type){
                case self::ACCOUNT_TYPE_CUSTOM:
                    try{
                        $_account = new CustomUser([
                            'account' => $account,
                        ]);
                    }catch(\Exception $e){
                        return Yii::$app->EC->callback($return, $e);
                    }
                    break;

                default:
                    return Yii::$app->EC->callback($return, 'unknown account type');
            }
        }elseif($account instanceof CustomUser){
            $_account = $account;
        }else{
            return Yii::$app->EC->callback($return, 'unavailable account');
        }
        return (new AbchinaAccountGenerator([
            'account' => $_account,
        ]))->generate($return);
    }

    public static function getAccountTypes(){
        return [
            self::ACCOUNT_TYPE_CUSTOM,
            self::ACCOUNT_TYPE_ADMIN,
            self::ACCOUNT_TYPE_SUPPLY,
            self::ACCOUNT_TYPE_BUSINESS,
        ];
    }
}
