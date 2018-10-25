<?php
namespace business\modules\leader\models;

use Yii;
use common\models\Model;
use business\models\handler\AccountHandler;
use business\models\parts\Role;
use business\models\parts\Account;
use business\models\parts\Area;
use common\ActiveRecord\BusinessUserAchievementDayAR;
use common\ActiveRecord\BusinessUserAR;

class PersonModel extends Model{

    const SCE_ADD_USER = 'add_user';
    const SCE_LIST_USER = 'list_user';
    const SCE_REMOVE_USER = 'remove_user';
    const SCE_RESET_USER = 'reset_user';
    const SCE_GET_USER_REMARK = 'get_user_remark';
    const SCE_GET_USER_POSITION = 'get_user_position';
    const SCE_MODIFY_USER = 'modify_user';
    const SCE_GET_USER_ACHIEVEMENT = 'get_user_achievement';

    public $name;
    public $remark;
    public $user;
    public $current_page;
    public $page_size;
    public $users_id;
    public $user_id;

    public function scenarios(){
        return [
            self::SCE_ADD_USER => [
                'name',
                'remark',
            ],
            self::SCE_LIST_USER => [
                'user',
                'current_page',
                'page_size',
            ],
            self::SCE_REMOVE_USER => [
                'users_id',
            ],
            self::SCE_RESET_USER => [
                'users_id',
            ],
            self::SCE_GET_USER_REMARK => [
                'user_id',
            ],
            self::SCE_GET_USER_POSITION => [
                'user_id',
            ],
            self::SCE_MODIFY_USER => [
                'user_id',
                'name',
                'remark',
            ],
            self::SCE_GET_USER_ACHIEVEMENT => [
                'user_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['remark', 'user'],
                'default',
                'value' => '',
            ],
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['name', 'users_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['user_id'],
                'required',
                'message' => 9001,
                'on' => self::SCE_GET_USER_ACHIEVEMENT,
            ],
            [
                ['name'],
                'string',
                'length' => [2, 30],
                'tooShort' => 13011,
                'tooLong' => 13011,
                'message' => 13011,
            ],
            [
                ['remark'],
                'string',
                'length' => [0, 255],
                'tooShort' => 13012,
                'tooLong' => 13012,
                'message' => 13012,
            ],
            [
                ['user'],
                'string',
                'length' => [0, 30],
                'tooShort' => 13021,
                'tooLong' => 13021,
                'message' => 13021,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['users_id'],
                'each',
                'rule' => [
                    'business\validators\AccountValidator',
                    'role' => $this->scenario == self::SCE_REMOVE_USER ? [
                        Role::TOP,
                        Role::SECONDARY,
                        Role::TERTIARY,
                        Role::QUATERNARY,
                        Role::FIFTH_LEADER,
                        Role::FIFTH_COMMISSAR,
                        Role::UNDEFINED,
                    ] : [
                        Role::TOP,
                        Role::SECONDARY,
                        Role::TERTIARY,
                        Role::QUATERNARY,
                        Role::FIFTH_LEADER,
                        Role::FIFTH_COMMISSAR,
                    ],
                    'status' => [
                        Account::STATUS_NORMAL,
                        Account::STATUS_UNREGISTERED,
                    ],
                    'level' => Yii::$app->BusinessUser->account->level,
                    'topArea' => ($topArea = Yii::$app->BusinessUser->account->topArea)->level->level == Area::LEVEL_UNDEFINED ? null : [
                        $topArea->id,
                        Area::LEVEL_UNDEFINED,
                    ],
                ],
                'allowMessageFromRule' => false,
                'message' => 13031,
            ],
            [
                ['user_id'],
                'business\validators\AccountValidator',
                'role' => [
                    Role::TOP,
                    Role::SECONDARY,
                    Role::TERTIARY,
                    Role::QUATERNARY,
                    Role::FIFTH_LEADER,
                    Role::FIFTH_COMMISSAR,
                    Role::UNDEFINED,
                ],
                'status' => [
                    Account::STATUS_NORMAL,
                    Account::STATUS_UNREGISTERED,
                ],
                'message' => 13051,
            ],
        ];
    }

    public function getUserAchievement(){
        $userAR = BusinessUserAR::findOne($this->user_id);
        $yesterday = date('Y-m-d', time() - 86400);
        if($achievementAR = BusinessUserAchievementDayAR::findOne(['business_user_id' => $this->user_id, 'record_date' => $yesterday])){
            $achievement = $achievementAR->normal_rmb + $achievementAR->refund_rmb + $achievementAR->reject_rmb;
        }else{
            $achievement = 0;
        }
        return [
            'yesterday' => $achievement,
            'life' => $userAR->all_rmb,
        ];
    }

    public function addUser(){
        return AccountHandler::create($this->name, $this->remark, false) ? true : false;
    }

    public function listUser(){
        if(!$provider = AccountHandler::provide($this->user, (int)$this->current_page, (int)$this->page_size, false)){
            $this->addError('listUser', 13022);
            return false;
        }
        return AccountHandler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'data' => 'models',
            '_func' => [
                'models' => function($models){
                    return array_map(function($userId){
                        $account = new Account(['id' => $userId]);
                        return AccountHandler::getMultiAttributes($account, [
                            'id',
                            'account',
                            'name',
                            'mobile',
                            'role',
                            '_func' => [
                                'mobile' => function($mobile){
                                    return $mobile ? : '';
                                },
                                'role' => function($role){
                                    return $role->name;
                                },
                            ],
                        ]);
                    }, $models);
                },
            ],
        ]);
    }

    public function removeUser(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($this->users_id as $userId){
                AccountHandler::remove(new Account(['id' => $userId]));
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('removeUser', 13032);
            return false;
        }
    }

    public function resetUser(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($this->users_id as $userId){
                $account = new Account(['id' => $userId]);
                $account->resetRole();
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollback();
            $this->addError('resetUser', 13041);
            return false;
        }
    }

    public function getUserRemark(){
        $account = new Account(['id' => $this->user_id]);
        return [
            'remark' => $account->remark,
        ];
    }

    public function getUserPosition(){
        $account = new Account(['id' => $this->user_id]);
        return AccountHandler::getMultiAttributes($account, [
            'role',
            'area',
            '_func' => [
                'role' => function($role){
                    return $role->name;
                },
                'area' => function($area){
                    return array_map(function($area){
                        return $area->name;
                    }, $area->fullArea);
                },
            ],
        ]);
    }

    public function modifyUser(){
        $account = new Account(['id' => $this->user_id]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $account->name = $this->name;
            $account->remark = $this->remark;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('modifyUser', 13211);
            return false;
        }
    }
}
