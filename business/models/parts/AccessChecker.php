<?php
namespace business\models\parts;

use Yii;
use yii\rbac\CheckAccessInterface;
use yii\base\Object;

class AccessChecker extends Object implements CheckAccessInterface{

    public function checkAccess($userId, $permissionName, $params = []){
        if(Yii::$app->user->isGuest){
            return false;
        }else{
            if(strpos($permissionName, '!') === 0){
                $privilege = substr($permissionName, 1);
                return Yii::$app->user->identity->level == $privilege;
            }else{
                return Yii::$app->user->identity->level >= $permissionName;
            }
        }
    }
}
