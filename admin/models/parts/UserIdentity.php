<?php
namespace admin\models\parts;

use common\ActiveRecord\AdminUserAR;
use yii\web\IdentityInterface;;

class UserIdentity extends AdminUserAR implements IdentityInterface{

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public function getId(){
        return $this->id;
    }

    /**
     * inherit
     * ignore
     */
    public static function findIdentityByAccessToken($token, $type = null){
    
    }

    /**
     * inherit
     * ignore
     */
    public function getAuthKey(){
    
    }

    /**
     * inherit
     * ignore
     */
    public function validateAuthKey($authKey){
    
    }
}
