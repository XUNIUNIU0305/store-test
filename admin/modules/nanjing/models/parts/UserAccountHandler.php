<?php
namespace admin\modules\nanjing\models\parts;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\BusinessUserWalletAR;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;

class UserAccountHandler extends Handler{

    const FUND_TYPE_ALL = 0;
    const FUND_TYPE_NORMAL = 1;
    const FUND_TYPE_FROZEN = 2;

    public static function getUsersFund($accountType = null, $fundType = 0, $return = 'throw'){
        if(!is_null($accountType)){
            if(!in_array($accountType, AccountAbstract::getAccountTypes()))return Yii::$app->EC->callback($return, 'unavailable account type');
        }
        if(!self::validateFundType($fundType))return Yii::$app->EC->callback($return, 'unavailable fund type');
        $fund = [];
        switch($accountType){
        case AccountAbstract::ACCOUNT_TYPE_CUSTOM:
            break;

        case AccountAbstract::ACCOUNT_TYPE_SUPPLY:
            break;

        case AccountAbstract::ACCOUNT_TYPE_BUSINESS:
            $fund[AccountAbstract::ACCOUNT_TYPE_BUSINESS] = self::getBusinessFund($fundType);
            break;

        case AccountAbstract::ACCOUNT_TYPE_ADMIN:
            break;

        case null:
            $fund = [
                AccountAbstract::ACCOUNT_TYPE_BUSINESS => self::getBusinessFund($fund),
            ];
            break;

        default:
            break;
        }
        return $fund;
    }

    public static function getBusinessFund($fundType = 0, $return = 'throw'){
        if(!self::validateFundType($fundType))return Yii::$app->EC->callback($return, 'unavailable fund type');
        $query = function($normal){
            $rmbType = $normal ? 'rmb' : 'frozen_rmb';
            return function(bool $query)use($rmbType){
                if($query){
                    return Yii::$app->RQ->AR(new BusinessUserWalletAR)->sum([
                        'select' => [$rmbType],
                    ], $rmbType);
                }else{
                    return '0.00';
                }
            };
        };
        $normalFund = $query(true);
        $frozenFund = $query(false);
        switch($fundType){
            case self::FUND_TYPE_ALL:
                $isGetBalance = true;
                $isGetFrozen = true;
                break;

            case self::FUND_TYPE_NORMAL:
                $isGetBalance = true;
                $isGetFrozen = false;
                break;

            case self::FUND_TYPE_FROZEN:
                $isGetBalance = false;
                $isGetFrozen = true;
                break;

            default:
                $isGetBalance = false;
                $isGetFrozen = false;
                break;
        }
        return [
            'balance' => $normalFund($isGetBalance),
            'frozen' => $frozenFund($isGetFrozen),
        ];
    }

    protected static function validateFundType($type){
        if(in_array($type, self::getFundTypes())){
            return true;
        }else{
            return false;
        }
    }

    public static function getFundTypes(){
        return [
            self::FUND_TYPE_ALL,
            self::FUND_TYPE_NORMAL,
            self::FUND_TYPE_FROZEN,
        ];
    }
}
