<?php
namespace common\validators\gpubs;

use common\models\Validator;
use common\models\parts\gpubs\GpubsGroup;

class groupValidator extends Validator{

    public $message;
    public $customerId = false;
    public $supplierId = false;

    protected function validateValue($no){
        try{
            $group = new GpubsGroup(['groupNumber' => $no]);
            if($this->customerId !== false){
                if($this->customerId == $group->custom_user_id){
                    $result = true;
                }else{
                    $result = $this->message;
                }
            }else{
                $result = true;
            }
            if($result !== true)return $result;
            if($this->supplierId !== false){
                if($this->supplierId != $group->supply_user_id){
                    $result = $this->message;
                }
            }
            return $result;
        }catch(\Exception $e){
            return $this->message;
        }
    }
}
