<?php
namespace admin\modules\fund\models;

use Yii;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use business\models\parts\Account;
use common\components\handler\Handler;
use admin\modules\fund\models\parts\DepositAndDrawTicket;

class DepositAndDrawApplicationModel extends Model{

    const SCE_GET_USER_INFO = 'get_user_info';
    const SCE_CREATE = 'create';

    public $account;
    public $operate_type;
    public $amount;
    public $operate_brief;
    public $operate_detail;

    public function scenarios(){
        return [
            self::SCE_GET_USER_INFO => [
                'account',
            ],
            self::SCE_CREATE => [
                'account',
                'operate_type',
                'amount',
                'operate_brief',
                'operate_detail',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['account', 'operate_type', 'amount', 'operate_brief', 'operate_detail'],
                'required',
                'message' => 9001,
            ],
            [
                ['account'],
                'integer',
                'min' => 10000000,
                'max' => 999999999,
                'tooSmall' => 5431,
                'tooBig' => 5431,
                'message' => 5431,
            ],
            [
                ['operate_type'],
                'in',
                'range' => [
                    DepositAndDrawTicket::OPERATE_TYPE_DEPOSIT,
                    DepositAndDrawTicket::OPERATE_TYPE_DRAW,
                ],
                'message' => 5441,
            ],
            [
                ['amount'],
                'number',
                'min' => 0.01,
                'max' => 100000,
                'tooSmall' => 5442,
                'tooBig' => 5442,
                'message' => 5442,
            ],
            [
                ['operate_brief'],
                'string',
                'length' => [1, 255],
                'tooShort' => 5443,
                'tooLong' => 5443,
                'message' => 5443,
            ],
            [
                ['operate_detail'],
                'string',
                'length' => [1, 65530],
                'tooShort' => 5444,
                'tooLong' => 5444,
                'message' => 5444,
            ],
        ];
    }

    public function create(){
        if(!$targetUser = $this->getAccount($this->account, true)){
            $this->addError('create', 5445);
            return false;
        }
        $result = DepositAndDrawTicket::generate([
            'operateUser' => Yii::$app->AdminUser->menus,
            'targetUser' => $targetUser,
            'operateType' => $this->operate_type,
            'amount' => $this->amount,
            'operateBrief' => $this->operate_brief,
            'operateDetail' => $this->operate_detail,
        ], false);
        if($result){
            return true;
        }else{
            $this->addError('create', 5446);
            return false;
        }
    }

    public function getUserInfo(){
        if(!$account = $this->getAccount($this->account, false)){
            $this->addError('getUserInfo', 5432);
            return false;
        }
        if($account instanceof Account){
            $info = Handler::getMultiAttributes($account, [
                'account',
                'mobile',
                'name',
                'role',
                'area',
                'rmb' => 'wallet',
                'status',
                '_func' => [
                    'mobile' => function($mobile){
                        return $mobile ? : '';
                    },
                    'wallet' => function($wallet){
                        return $wallet->rmb;
                    },
                    'role' => function($role){
                        return $role->name;
                    },
                    'area' => function($area){
                        return $this->areaToStr($area);
                    },
                ],
            ]);
            $info['type'] = DepositAndDrawTicket::TARGET_USER_TYPE_BUSINESS;
        }else{
            $info = Handler::getMultiAttributes($account, [
                'account',
                'mobile',
                'name' => 'nickName',
                'area',
                'rmb' => 'wallet',
                'status',
                '_func' => [
                    'area' => function($area){
                        return $this->areaToStr($area);
                    },
                    'wallet' => function($wallet){
                        return $wallet->rmb;
                    },
                ],
            ]);
            $info['role'] = '门店';
            $info['type'] = DepositAndDrawTicket::TARGET_USER_TYPE_CUSTOM;
        }
        return $info;
    }

    private function areaToStr($area){
        $fullArea = $area->fullArea;
        $area = [];
        foreach($fullArea as $v){
            $area[] = $v->name;
        }
        return implode(' - ', array_reverse($area));
    }

    private function getAccount($accountNumber, bool $verify){
        switch(strlen($accountNumber)){
            case 8:
                try{
                    $account = new Account([
                        'account' => $accountNumber,
                    ]);
                    if($verify){
                        return $account->status == Account::STATUS_NORMAL ? $account : false;
                    }else{
                        return $account;
                    }
                }catch(\Exception $e){
                    return false;
                }
                break;

            case 9:
                try{
                    $account = new CustomUser([
                        'account' => $accountNumber,
                    ]);
                    if($verify){
                        if($account->isAuthorized && $account->status == CustomUser::STATUS_NORMAL){
                            return $account;
                        }else{
                            return false;
                        }
                    }else{
                        return $account;
                    }
                }catch(\Exception $e){
                    return false;
                }
                break;

            default:
                return false;
        }
    }
}
