<?php
namespace business\modules\site\models;

use Yii;
use common\models\Model;
use business\models\handler\AdminHandler;
use business\models\parts\Account;
use business\models\parts\Area;
use business\models\parts\Role;

class AdminModel extends Model{

    const SCE_GET_ADMIN_LIST = 'get_admin_list';
    const SCE_SET_ADMIN = 'set_admin';
    const SCE_REMOVE_ADMIN = 'remove_admin';

    public $account;
    public $is_admin;
    public $current_page;
    public $page_size;
    public $area_id;

    public function scenarios(){
        return [
            self::SCE_GET_ADMIN_LIST => [
                'account',
                'is_admin',
                'current_page',
                'page_size',
            ],
            self::SCE_SET_ADMIN => [
                'account',
                'area_id',
            ],
            self::SCE_REMOVE_ADMIN => [
                'account',
            ],
        ];
    }

    public function rules(){
        return [
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
                ['is_admin', 'current_page', 'page_size', 'area_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['is_admin'],
                'in',
                'range' => [0, 1],
                'message' => 9002,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['account'],
                'integer',
                'min' => 10000000,
                'max' => 99999999,
                'tooSmall' => 13181,
                'tooBig' => 13181,
                'message' => 13181,
                'on' => self::SCE_GET_ADMIN_LIST,
            ],
            [
                ['account'],
                'business\validators\AccountValidator',
                'role' => $this->scenario == self::SCE_REMOVE_ADMIN ? Role::ADMIN : Role::UNDEFINED,
                'status' => [Account::STATUS_NORMAL, Account::STATUS_UNREGISTERED],
                'on' => [self::SCE_SET_ADMIN, self::SCE_REMOVE_ADMIN],
                'message' => 13191,
            ],
            [
                ['area_id'],
                'business\validators\AreaValidator',
                'topArea' => $this->area_id,
                'message' => 13192,
            ],
        ];
    }

    public function removeAdmin(){
        $account = new Account(['id' => $this->account]);
        if(Area::removeAdmin($account, false)){
            return true;
        }else{
            $this->addError('removeAdmin', 13201);
            return false;
        }
    }

    public function setAdmin(){
        $area = new Area(['id' => $this->area_id]);
        $account = new Account(['id' => $this->account]);
        if($area->setAdmin($account, false, false)){
            return true;
        }else{
            $this->addError('setAdmin', 13193);
            return false;
        }
    }

    public function getAdminList(){
        $provider = AdminHandler::provide(
            $this->is_admin ? true : false,
            (int)$this->current_page,
            (int)$this->page_size,
            empty($this->account) ? null : (int)$this->account
        );
        return AdminHandler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($list){
                    return array_map(function($one){
                        $account = new Account(['id' => $one['id']]);
                        return AdminHandler::getMultiAttributes($account, [
                            'id',
                            'account',
                            'name',
                            'mobile',
                            'province' => 'area',
                            '_func' => [
                                'area' => function($area){
                                    if($area->level->level == Area::LEVEL_TOP){
                                        return $area->name;
                                    }else{
                                        return '';
                                    }
                                },
                            ],
                        ]);
                    }, $list);
                },
            ],
        ]);
    }
}
