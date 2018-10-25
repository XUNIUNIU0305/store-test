<?php
namespace business\modules\leader\models;

use Yii;
use common\models\Model;
use business\models\parts\Area;
use business\models\parts\AreaLevel;
use common\components\handler\Handler;
use business\models\parts\Account;
use business\models\parts\record\BusinessAreaRecord;

class AreaModel extends Model{

    const SCE_GET_AREA_LIST = 'get_area_list';
    const SCE_ADD_AREA = 'add_area';
    const SCE_GET_ROLE = 'get_role';
    const SCE_APPOINT_USER = 'appoint_user';
    const SCE_MODIFY_AREA = 'modify_area';
    const SCE_GET_AREA_CHART = 'get_area_chart';

    public $parent_id;
    public $name;
    public $area_id;
    public $role;
    public $user_id;
    public $date_from;
    public $date_to;
    public $date_type;

    public function scenarios(){
        return [
            self::SCE_GET_AREA_LIST => [
                'parent_id',
            ],
            self::SCE_ADD_AREA => [
                'parent_id',
                'name',
            ],
            self::SCE_GET_ROLE => [
                'area_id',
            ],
            self::SCE_APPOINT_USER => [
                'area_id',
                'role',
                'user_id',
            ],
            self::SCE_MODIFY_AREA => [
                'area_id',
                'name',
            ],
            self::SCE_GET_AREA_CHART => [
                'area_id',
                'date_from',
                'date_to',
                'date_type',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['parent_id', 'name', 'area_id','user_id', 'date_from', 'date_to', 'date_type'],
                'required',
                'message' => 9001,
            ],
            [
                ['parent_id'],
                'integer',
                'min' => 0,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['name'],
                'string',
                'length' => [1, 20],
                'tooShort' => 13061,
                'tooLong' => 13061,
                'message' => 13061,
            ],
            [
                ['parent_id'],
                'business\validators\ParentAreaValidator',
                'hasChild' => $this->scenario == self::SCE_ADD_AREA ? true : null,
                'canModify' => $this->scenario == self::SCE_ADD_AREA ? true : null,
                'message' => 13062,
            ],
            [
                ['role'],
                'in',
                'range' => [Area::PERSON_LEADER, Area::PERSON_COMMISSAR],
                'message' => 13081,
            ],
            [
                ['area_id'],
                'business\validators\AreaValidator',
                'role' => ($this->scenario == self::SCE_GET_ROLE || $this->scenario == self::SCE_GET_AREA_CHART) ? null : $this->role,
                'canModify' => ($this->scenario == self::SCE_GET_ROLE || $this->scenario == self::SCE_GET_AREA_CHART) ? null : true,
                'topArea' => ($topArea = Yii::$app->BusinessUser->account->topArea)->level->level == Area::LEVEL_UNDEFINED ? null : $topArea->id,
                'userArea' => Yii::$app->BusinessUser->account->area->id,
                'message' => 13071,
            ],
            [
                ['user_id'],
                'business\validators\AccountValidator',
                'status' => [Account::STATUS_NORMAL, Account::STATUS_UNREGISTERED],
                'level' => Yii::$app->BusinessUser->account->level,
                'topArea' => ($topArea = Yii::$app->BusinessUser->account->topArea)->level->level == Area::LEVEL_UNDEFINED ? null : [$topArea->id, Area::LEVEL_UNDEFINED],
                'message' => 13083,
            ],
            [
                ['date_from'],
                'business\validators\DateValidator',
                'beforeDate' => $this->date_to,
                'message' => 13231,
            ],
            [
                ['date_to'],
                'business\validators\DateValidator',
                'message' => 13231,
            ],
            [
                ['date_type'],
                'in',
                'range' => [1, 2, 3],
                'message' => 13232,
            ],
        ];
    }

    public function getAreaChart(){
        $record = new BusinessAreaRecord(['id' => $this->area_id]);
        switch($this->date_type){
            case 1:
                $chart = $record->generateChartMonth($this->date_from, $this->date_to);
                break;

            case 2:
                $chart = $record->generateChartWeek($this->date_from, $this->date_to);
                break;

            case 3:
                $chart = $record->generateChartDay($this->date_from, $this->date_to);
                break;

            default:
                return [];
                break;
        }
        return $chart;
    }

    public function modifyArea(){
        $area = new Area(['id' => $this->area_id]);
        if(Yii::$app->BusinessUser->account->area->level->level >= $area->level->level){
            $this->addError('addArea', 13221);
            return false;
        }
        if($area->setName($this->name, false)){
            return true;
        }else{
            $this->addError('modifyArea', 13221);
            return false;
        }
    }

    public function appointUser(){
        $area = new Area(['id' => $this->area_id]);
        $account = new Account(['id' => $this->user_id]);
        if($area->setUser($this->role, $account, true, 'throw')){
            return true;
        }else{
            $this->addError('appointUser', 13082);
            return false;
        }
    }

    public function getRole(){
        $area = new Area(['id' => $this->area_id]);
        $role = [];
        foreach([Area::PERSON_LEADER, Area::PERSON_COMMISSAR] as $person){
            if($areaRole = $area->getRole($person)){
                $role[] = [
                    'id' => $person,
                    'name' => $areaRole->name,
                    'user' => ($account = $area->getUser($person)) ? Handler::getMultiAttributes($account, [
                        'id',
                        'name',
                    ]) : false,
                ];
            }
        }
        return $role;
    }

    public function addArea(){
        $parentArea = new Area(['id' => $this->parent_id]);
        if(Yii::$app->BusinessUser->account->area->level->level >= $parentArea->level->childLevel){
            $this->addError('addArea', 13063);
            return false;
        }
        if($parentArea->addChild($this->name)){
            return true;
        }else{
            $this->addError('addArea', 13063);
            return false;
        }
    }

    public function getAreaList(){
        $area = new Area(['id' => $this->parent_id]);
        $userAreaLevel = Yii::$app->BusinessUser->account->area->level->level;
        if($area->level->level >= $userAreaLevel ||
            $userAreaLevel == Area::LEVEL_UNDEFINED
        ){
            if(!$area->level->hasChild){
                $this->addError('getAreaList', 13111);
                return false;
            }
            return Handler::getMultiAttributes($area, [
                'level' => 'level',
                'has_child' => 'level',
                'list' => 'children',
                '_func' => [
                    'level' => function($level, $callbackName){
                        if($callbackName == 'level'){
                            return $level->childLevel;
                        }else{
                            if(!$childLevel = $level->childLevel)return false;
                            try{
                                (new AreaLevel(['level' => $childLevel + 1]));
                                return true;
                            }catch(\Exception $e){
                                return false;
                            }
                        }
                    },
                    'children' => function($areaList){
                        return array_map(function($area){
                            return Handler::getMultiAttributes($area, [
                                'id',
                                'name',
                            ]);
                        }, $areaList);
                    }
                ],
            ]);
        }else{
            $differLevel = $userAreaLevel - $area->level->level - 1;
            $area = Yii::$app->BusinessUser->account->area;
            for($i = 0; $i < $differLevel; $i++){
                $area = $area->parent;
            }
            return [
                'level' => $area->level->level,
                'has_child' => $area->level->hasChild,
                'list' => [
                    [
                        'id' => $area->id,
                        'name' => $area->name,
                    ],
                ],
            ];
        }
    }

    public static function getAreaLevelList(){
        return AreaLevel::getLevelList();
    }
}
