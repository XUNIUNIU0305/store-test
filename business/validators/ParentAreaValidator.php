<?php
namespace business\validators;

use Yii;
use common\models\Validator;
use business\models\parts\Area;

class ParentAreaValidator extends Validator{

    public $message;

    public $hasChild;

    public $canModify;

    protected function validateValue($parentAreaId){
        try{
            $parentArea = new Area(['id' => $parentAreaId]);
        }catch(\Exception $e){
            return $this->message;
        }
        if(!is_null($this->hasChild)){
            if($parentArea->level->hasChild != (boolean)$this->hasChild)return $this->message;
        }
        if(!is_null($this->canModify)){
            if($parentArea->canModify != (boolean)$this->canModify)return $this->message;
        }
        return true;
    }
}
