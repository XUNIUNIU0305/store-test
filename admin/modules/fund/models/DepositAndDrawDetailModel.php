<?php
namespace admin\modules\fund\models;

use Yii;
use common\models\Model;
use common\components\handler\Handler;
use common\ActiveRecord\NonTransactionDepositAndDrawAR;
use admin\modules\fund\models\parts\DepositAndDrawTicket;
use admin\modules\fund\models\parts\DepositAndDrawOperateLog;

class DepositAndDrawDetailModel extends Model{

    const SCE_GET_DETAIL = 'get_detail';
    const SCE_GET_OPERATE_INFO = 'get_operate_info';
    const SCE_PASS = 'pass';
    const SCE_CANCEL = 'cancel';

    public $id;
    public $cancel_reason;
    public $authorize_password;

    public function scenarios(){
        return [
            self::SCE_GET_DETAIL => [
                'id',
            ],
            self::SCE_GET_OPERATE_INFO => [
                'id',
            ],
            self::SCE_PASS => [
                'id',
                'authorize_password',
            ],
            self::SCE_CANCEL => [
                'id',
                'cancel_reason',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['id'],
                'required',
                'message' => 9001,
            ],
            [
                ['id'],
                'exist',
                'targetClass' => NonTransactionDepositAndDrawAR::className(),
                'targetAttribute' => 'id',
                'message' => 5461,
            ],
            [
                ['cancel_reason'],
                'string',
                'length' => [1, 255],
                'tooShort' => 5481,
                'tooLong' => 5481,
                'message' => 5481,
            ],
            [
                ['authorize_password'],
                'string',
                'length' => [1, 100],
                'tooShort' => 5472,
                'tooLong' => 5472,
                'message' => 5472,
            ],
        ];
    }

    public function cancel(){
        $ticket = new DepositAndDrawTicket([
            'id' => $this->id,
        ]);
        if($ticket->fillCancelReason($this->cancel_reason, false) &&
            $ticket->setStatus(DepositAndDrawTicket::STATUS_CANCEL, Yii::$app->AdminUser->menus, false)){
            return true;
        }else{
            $this->addError('cancel', 5482);
            return false;
        }
    }

    public function pass(){
        $ticket = new DepositAndDrawTicket([
            'id' => $this->id,
        ]);
        if(DepositAndDrawTicket::isRequireAuthorizePassword()){
            if(!$authorizePassword = (string)$this->authorize_password){
                $this->addError('pass', 5474);
                return false;
            }
            $ticket->fillOuterAuthorizePassword($authorizePassword);
            if(!$ticket->validateAuthorizePassword()){
                $this->addError('pass', 5473);
                return false;
            }
        }
        if($ticket->setStatus(DepositAndDrawTicket::STATUS_AUTHORIZED, Yii::$app->AdminUser->menus, false)){
            return true;
        }else{
            $this->addError('pass', 5471);
            return false;
        }
    }

    public function getOperateInfo(){
        $ticket = new DepositAndDrawTicket([
            'id' => $this->id,
        ]);
        $logs = [];
        foreach($ticket->logs as $type => $log){
            $data = Handler::getMultiAttributes($log, [
                'user_info' => 'user',
                'user_ip' => 'userIp',
                'user_request_header' => 'userRequestHeader',
                '_func' => [
                    'user' => function($user){
                        return Handler::getMultiAttributes($user, [
                            'account',
                            'name',
                            'mobile',
                            'email',
                            'status',
                            'department',
                            'role' => 'roles',
                            '_func' => [
                                'status' => function($status){
                                    return $status ? 1 : 0;
                                },
                                'department' => function($department){
                                    if($department){
                                        return $department->name;
                                    }
                                },
                                'roles' => function($roles){
                                    $names = array_map(function($role){
                                        return $role->roleName;
                                    }, $roles);
                                    return $names ? implode(' | ', $names) : '';
                                },
                            ],
                        ]);
                    },
                    'userRequestHeader' => function($header){
                        $headerList = [];
                        foreach($header as $name => $value){
                            $headerList[] = htmlspecialchars($name . ': ' . $value);
                        }
                        return $headerList;
                    },
                ],
            ]);
            $logs[$type] = $data;
        }
        foreach([
            DepositAndDrawOperateLog::OPERATE_TYPE_CREATE,
            DepositAndDrawOperateLog::OPERATE_TYPE_PASS,
            DepositAndDrawOperateLog::OPERATE_TYPE_CANCEL,
        ] as $type){
            if(!array_key_exists($type, $logs)){
                $logs[$type] = false;
            }
        }
        return $logs;
    }

    public function getDetail(){
        $ticket = new DepositAndDrawTicket([
            'id' => $this->id,
        ]);
        return Handler::getMultiAttributes($ticket, [
            'id',
            'operate_type' => 'operateType',
            'user_type' => 'targetUserType',
            'user_id' => 'targetUserId',
            'user_account' => 'targetUserAccount',
            'amount',
            'operate_brief' => 'operateBrief',
            'operate_detail' => 'operateDetail',
            'cancel_reason' => 'cancelReason',
            'status',
            'create_time' => 'createTime',
            'pass_time' => 'passTime',
            'cancel_time' => 'cancelTime',
            'operate_time' => 'operateTime',
        ]);
    }
}
