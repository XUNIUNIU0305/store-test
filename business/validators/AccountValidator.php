<?php
namespace business\validators;

use Yii;
use common\models\Validator;
use business\models\parts\Account;

class AccountValidator extends Validator{

    public $message;

    //验证用户角色是否满足条件
    public $role;

    //验证用户状态是否满足条件
    public $status;

    //验证用户权限是否满足条件
    public $level;

    public $topArea;

    protected function validateValue($userId){
        try{
            $account = new Account(['id' => $userId]);
        }catch(\Exception $e){
            return $this->message;
        }
        if(!is_null($this->role)){
            if(is_array($this->role)){
                if(!in_array($account->role->role, $this->role))return $this->message;
            }else{
                if($account->role->role != $this->role)return $this->message;
            }
        }
        if(!is_null($this->status)){
            if(is_array($this->status)){
                if(!in_array($account->status, $this->status))return $this->message;
            }else{
                if($account->status != $this->status)return $this->message;
            }
        }
        if(!is_null($this->level)){
            if($account->level >= $this->level)return $this->message;
        }
        if(!is_null($this->topArea)){
            if(is_array($this->topArea)){
                if(!in_array($account->topArea->id, $this->topArea))return $this->message;
            }else{
                if($account->topArea->id != $this->topArea)return $this->message;
            }
        }
        return true;
    }
}
