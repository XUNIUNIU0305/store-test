<?php
namespace custom\models\parts;

use common\ActiveRecord\CustomUserAR;
use yii\web\IdentityInterface;

class UserIdentity extends CustomUserAR implements IdentityInterface{

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
