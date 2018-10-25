<?php
namespace business\models;

use Yii;
use common\models\Model;

class UserModel extends Model{

    public static function getUserBalance(){
        if(Yii::$app->user->isGuest){
            return [
                'rmb' => '0.00',
            ];
        }else{
            return [
                'rmb' => sprintf('%.2f', Yii::$app->BusinessUser->account->wallet->rmb),
            ];
        }
    }
}
