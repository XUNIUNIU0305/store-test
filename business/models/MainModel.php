<?php
namespace business\models;

use Yii;
use common\models\Model;
use business\models\parts\Menu;
use common\components\handler\Handler;

class MainModel extends Model{

    public static function logout(){
        return Yii::$app->user->logout();
    }

    public static function getMenu(){
        return Yii::$app->BusinessUser->menu->fullMenu;
    }

    public static function getUserInfo(){
        return Handler::getMultiAttributes(Yii::$app->BusinessUser->account, [
            'account',
            'name',
            'mobile',
            'role',
            '_func' => [
                'role' => function($role){
                    return $role->name;
                }
            ],
        ]);
    }
}
