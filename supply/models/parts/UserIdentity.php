<?php
namespace supply\models\parts;

use common\ActiveRecord\SupplyUserAR;
use yii\web\IdentityInterface;

class UserIdentity extends SupplyUserAR implements IdentityInterface{
    
    /**
     * inherit
     * return an identity by the given ID
     * @return IdentityInterface
     */
    public static function findIdentity($id){
        return static::findOne($id);
    }

    /**
     * inherit
     * @return int user id
     */
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
