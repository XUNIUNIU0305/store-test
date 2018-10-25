<?php
namespace common\models\parts\partner;

use Yii;

class AccessRule extends \yii\filters\AccessRule{

    public function allows($action, $user, $request){
        if($action->controller->id == 'auth' || $action->id == 'upload' || $action->id == 'permission'){
            if ($this->matchAction($action)
                && $this->matchRole($user)
                && $this->matchIP($request->getUserIP())
                && $this->matchVerb($request->getMethod())
                && $this->matchController($action->controller)
                && $this->matchCustom($action)
            ) {
                return $this->allow ? true : false;
            } else {
                return null;
            }
        }else{
            if ($this->matchAction($action)
                && $this->matchAdvanceRole($user)
                && $this->matchIP($request->getUserIP())
                && $this->matchVerb($request->getMethod())
                && $this->matchController($action->controller)
                && $this->matchCustom($action)
            ) {
                return $this->allow ? true : false;
            } else {
                return null;
            }
        }
    }

    protected function matchAdvanceRole($user){
        if(parent::matchRole($user)){
            if(!$user->isGuest){
                if($user->identity->authorized){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}
