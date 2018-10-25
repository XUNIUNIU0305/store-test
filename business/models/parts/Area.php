<?php
namespace business\models\parts;

use common\components\handler\quality\BusinessAreaTechnicanHandler;
use common\models\parts\quality\Technican;
use Yii;
use yii\base\Object;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserRegistercodeAR;
use yii\base\InvalidConfigException;
use yii\base\InvalidCallException;
use custom\components\handler\RegistercodeHandler;
use custom\models\parts\RegisterCode;

class Area extends Object{

    const DEFAULT_FIFTH_ID = 5;

    const LEVEL_UNDEFINED = 0;
    const LEVEL_TOP = 1;
    const LEVEL_SECONDARY = 2;
    const LEVEL_TERTIARY = 3;
    const LEVEL_QUATERNARY = 4;
    const LEVEL_FIFTH = 5;

    const UNDEFINED_AREA_NAME = '未定义区域';

    const DISPLAY_ON = 1;
    const DISPLAY_OFF = 0;

    const MODIFY_ON = 1;
    const MODIFY_OFF = 0;

    const EMPTY_USER = 0;

    const PERSON_LEADER = 1;
    const PERSON_COMMISSAR = 2;

    public static $levels = [
        self::LEVEL_TOP,
        self::LEVEL_SECONDARY,
        self::LEVEL_TERTIARY,
        self::LEVEL_QUATERNARY,
        self::LEVEL_FIFTH
    ];

    public $id;

    protected $AR;

    protected $areaRole = [
        self::LEVEL_UNDEFINED => [
            self::PERSON_LEADER => Role::UNDEFINED
        ],
        self::LEVEL_TOP => [
            self::PERSON_LEADER => Role::TOP
        ],
        self::LEVEL_SECONDARY => [
            self::PERSON_LEADER => Role::SECONDARY
        ],
        self::LEVEL_TERTIARY => [
            self::PERSON_LEADER => Role::TERTIARY
        ],
        self::LEVEL_QUATERNARY => [
            self::PERSON_LEADER => Role::QUATERNARY
        ],
        self::LEVEL_FIFTH => [
            self::PERSON_LEADER => Role::FIFTH_LEADER,
            self::PERSON_COMMISSAR => Role::FIFTH_COMMISSAR,
        ],
    ];

    public function init(){
        if($this->id){
            if(!$this->AR = BusinessAreaAR::findOne($this->id))throw new InvalidConfigException('unavailable area id');
        }else{
            $this->AR = new UndefinedArea;
        }
        $this->id=$this->AR->id;
    }



    public function getName(){
        return $this->AR->name;
    }

    public function setName(string $name, $return = 'throw'){
        if(empty($name))return Yii::$app->EC->callback($return, 'string');
        if(!$this->canModify)return Yii::$app->EC->callback($return, 'this area can not be modified');
        return Yii::$app->RQ->AR($this->AR)->update([
            'name' => $name,
        ], $return);
    }

    public function getLevel(){
        return new AreaLevel(['level' => $this->AR->level]);
    }

    public function getFullArea(){
        if($this->AR->level == self::LEVEL_UNDEFINED){
            return [
                self::LEVEL_UNDEFINED => $this,
            ];
        }else{
            $fullArea = [];
            $area = $this;
            while($area->level->level != self::LEVEL_UNDEFINED){
                $fullArea[$area->level->level] = $area;
                $area = $area->getParent();
            }
            return $fullArea;
        }
    }

    public function getTopArea(){
        if($this->AR->level == self::LEVEL_UNDEFINED){
            return new Area(['id' => self::LEVEL_UNDEFINED]);
        }else{
            $area = $this;
            while($area->parent->level->level != self::LEVEL_UNDEFINED){
                $area = $area->getParent();
            }
            return $area;
        }
    }

    public function getParent(){
        return new Area(['id' => $this->AR->parent_business_area_id]);
    }

    public function getChildren(){
        $areaIds = Yii::$app->RQ->AR(new BusinessAreaAR)->column([
            'select' => ['id'],
            'where' => [
                'parent_business_area_id' => $this->id,
                'display' => self::DISPLAY_ON,
            ],
        ]);
        return array_map(function($areaId){
            return new Area(['id' => $areaId]);
        }, $areaIds);
    }

    public function getHasChild(){
        return Yii::$app->RQ->AR(new BusinessAreaAR)->exists([
            'select' => ['id'],
            'where' => [
                'parent_business_area_id' => $this->id,
            ],
            'limit' => 1,
        ]);
    }

    public function addChild(string $name, $return = 'throw'){
        if(empty($name))return Yii::$app->EC->callback($return, 'string');
        if(!$childLevel = $this->getLevel()->childLevel)return Yii::$app->EC->callback($return, 'this area can not add child area');
        if(!$this->canModify)return Yii::$app->EC->callback($return, 'this area can not be modified');
        $childId = Yii::$app->RQ->AR(new BusinessAreaAR)->insert([
            'name' => $name,
            'level' => $childLevel,
            'parent_business_area_id' => $this->id,
            'business_user_asleader_id' => self::EMPTY_USER,
            'business_user_ascommissar_id' => self::EMPTY_USER,
            'can_modify' => self::MODIFY_ON,
            'display' => self::DISPLAY_ON,
        ], false);
        if($childId){
            return new Area(['id' => $childId]);
        }else{
            return Yii::$app->EC->callback($return, 'mysql');
        }
    }

    public function getRole(int $personType){
        switch($personType){
            case self::PERSON_LEADER:
            case self::PERSON_COMMISSAR:
                $role = $this->areaRole[$this->AR->level][$personType] ?? null;
                break;

            default:
                return false;
        }
        if(is_null($role)){
            return false;
        }else{
            return new Role(['id' => $role]);
        }
    }

    public function getCommissar(){
        if($this->AR->business_user_ascommissar_id){
            return new Account(['id' => $this->AR->business_user_ascommissar_id]);
        }else{
            return false;
        }
    }

    public function getLeader(){
        if($this->AR->business_user_asleader_id){
            return new Account(['id' => $this->AR->business_user_asleader_id]);
        }else{
            return false;
        }
    }

    public function getUser(int $personType){
        switch($personType){
            case self::PERSON_LEADER:
                return $this->getLeader();

            case self::PERSON_COMMISSAR:
                return $this->getCommissar();

            default:
                return false;
        }
    }

    public function setUser(int $personType, Account $account, bool $replace = false, $return = 'throw'){
        if(!in_array($personType, [self::PERSON_LEADER, self::PERSON_COMMISSAR]))return Yii::$app->EC->callback($return, 'unavailable admin type');
        if($account->status == Account::STATUS_REMOVE)return Yii::$app->EC->callback($return, 'unavailable account status');
        if(!$this->canModify)return Yii::$app->EC->callback($return, 'this area can not be modified');
        if(!$role = $this->getRole($personType))return Yii::$app->EC->callback('this area have not role: ' . $personType);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($originalAccount = $this->getUser($personType)){
                if($replace){
                    $originalAccount->resetRole();
                }else{
                    throw new InvalidCallException('this area have the user: ' . $personType);
                }
            }
            if($account->area->level->level != self::LEVEL_UNDEFINED){
                if($replace){
                    $account->resetRole();
                }else{
                    throw new InvalidCallException('the user has been set');
                }
            }
            $account->setRole($role, $this);
            Yii::$app->RQ->AR($this->AR)->update([
                $personType == self::PERSON_LEADER ? 'business_user_asleader_id' : 'business_user_ascommissar_id' => $account->id,
            ]);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function removeUser(Account $account, $return = 'throw'){
        if(!$this->canModify)return Yii::$app->EC->callback($return, 'this area can not be modified');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($leader = $this->getLeader()){
                if($leader->id == $account->id){
                    $account->setRole(new Role(['id' => Role::UNDEFINED]), new Area(['id' => self::LEVEL_UNDEFINED]));
                    Yii::$app->RQ->AR($this->AR)->update([
                        'business_user_asleader_id' => self::EMPTY_USER,
                    ]);
                }
            }
            if($commissar = $this->getCommissar()){
                if($commissar->id == $account->id){
                    $account->setRole(new Role(['id' => Role::UNDEFINED]), new Area(['id' => self::LEVEL_UNDEFINED]));
                    Yii::$app->RQ->AR($this->AR)->update([
                        'business_user_ascommissar_id' => self::EMPTY_USER,
                    ]);
                }
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'mysql');
        }
    }

    public function addCustomRegistercode(int $number, $return = 'throw'){
        return RegistercodeHandler::createPartnerCode($number, $this, $return);
    }

    public function bindCustom($account, $return = 'throw'){
        if($this->getLevel()->hasChild)return Yii::$app->EC->callback($return, 'this area has child area');
        if(!$customUser = CustomUserAR::findOne(['account' => $account]))return Yii::$app->EC->callback($return, 'unavailable account');
        if($customUser->business_area_id == $this->id){
            return true;
        }else{
            $customUser->business_area_id = $this->id;
            $customUser->business_quaternary_area_id = $this->getParentBusinessAreaId($customUser->business_area_id);
            $customUser->business_tertiary_area_id = $this->getParentBusinessAreaId($customUser->business_quaternary_area_id);
            $customUser->business_secondary_area_id = $this->getParentBusinessAreaId($customUser->business_tertiary_area_id);
            $customUser->business_top_area_id = $this->getParentBusinessAreaId($customUser->business_secondary_area_id);
            if ($customUser->business_quaternary_area_id === false || $customUser->business_tertiary_area_id === false
                || $customUser->business_secondary_area_id === false || $customUser->business_top_area_id === false) {
                return false;
            }
            return $customUser->update() ? true : Yii::$app->EC->callback($return, 'mysql');
        }
    }

    public function setAdmin(Account $account, bool $replace = true, $return = 'throw'){
        if($this->getLevel()->level != self::LEVEL_TOP)return Yii::$app->EC->callback($return, 'this area can not add the administrator');
        if($account->role->id != Role::UNDEFINED)return Yii::$app->EC->callback($return, 'this account has been set as another role');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($originalAdmin = BusinessUserAR::findOne([
                'business_role_id' => Role::ADMIN,
                'business_area_id' => $this->id,
            ])){
                if($replace){
                    $originalAccount = new Account(['id' => $originalAdmin->id]);
                    $originalAccount->resetRole();
                }
            }
            $account->setRole(new Role(['id' => Role::ADMIN]), $this);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'mysql');
        }
    }

    public static function removeAdmin(Account $account, $return = 'throw'){
        if($account->role->id != Role::ADMIN)return Yii::$app->EC->callback($return, 'this account is not an administrator');
        if($account->resetRole(false)){
            return true;
        }else{
            return Yii::$app->EC->callback($return, 'resetting the user role failed');
        }
    }

    public function getCustomQuantity(){
        return $this->AR->custom_quantity;
    }

    public function getCanModify(){
        return $this->AR->can_modify == self::MODIFY_ON ? true : false;
    }

    public function getIsDisplay(){
        return $this->AR->display == self::DISPLAY_ON ? true : false;
    }


    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:获取区域下所有技师列表
     * @param int $pageSize
     * @param int $currentPage
     * @param array $sort
     * @return array
     */
    public function getTechnical(int $pageSize, int $currentPage, $sort=['id'=>SORT_DESC]){
        return BusinessAreaTechnicanHandler::getList($pageSize,$currentPage,$this,$sort);
    }

    /*
     * 获得父级的 area_id
     * @param string $businessAreaId
     * @return string|bool parent_business_area_id
     */
    public function getParentBusinessAreaId($businessAreaId)
    {
        $select = 'parent_business_area_id';
        $result = BusinessAreaAR::find()
            ->select($select)
            ->where(['id' => $businessAreaId])
            ->scalar();
        return isset($result) ? $result : false;
    }
}
