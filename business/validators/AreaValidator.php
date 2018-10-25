<?php
namespace business\validators;

use Yii;
use common\models\Validator;
use business\models\parts\Area;

class AreaValidator extends Validator{

    public $message;

    public $role;

    public $hasChild;

    public $canModify;

    public $topArea;

    public $userArea;

    public $display = true;

    protected function validateValue($areaId){
        try{
            $area = new Area(['id' => $areaId]);
        }catch(\Exception $e){
            return $this->message;
        }
        if(!is_null($this->hasChild)){
            if($area->level->hasChild != (boolean)$this->hasChild)return $this->message;
        }
        if(!is_null($this->canModify)){
            if($area->canModify != (boolean)$this->canModify)return $this->message;
        }
        if(!is_null($this->role)){
            if(!$area->getRole($this->role))return $this->message;
        }
        if(!is_null($this->topArea)){
            if($area->topArea->id != $this->topArea)return $this->message;
        }
        if(!is_null($this->display)){
            if($area->isDisplay != $this->display)return $this->message;
        }
        if(!is_null($this->userArea)){
            $userArea = new Area(['id' => $this->userArea]);
            $userAreaLevel = $userArea->level->level;
            if($userAreaLevel != Area::LEVEL_UNDEFINED){
                $areaLevel = $area->level->level;
                if($userAreaLevel > $areaLevel)return $this->message;
                $differLevel = $areaLevel - $userAreaLevel;
                $differArea = $area;
                for($i = 0; $i < $differLevel; $i++){
                    $differArea = $differArea->parent;
                }
                if($userArea->id != $differArea->id)return $this->message;
            }
        }
        return true;
    }
}
