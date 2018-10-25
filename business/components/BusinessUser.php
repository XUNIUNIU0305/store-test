<?php
namespace business\components;

use Yii;
use common\components\basic\LoggedUserAbstract;
use common\ActiveRecord\BusinessRoleAR;

class BusinessUser extends LoggedUserAbstract{

    private $_role;

    protected function getUserComponents(){
        return [
            'menu' => [
                'class' => 'business\models\parts\Menu',
                'level' => Yii::$app->user->identity->level,
            ],
            'account' => [
                'class' => 'business\models\parts\Account',
                'id' => Yii::$app->user->id,
            ],
            'statement' => 'business\components\UserStatement'
        ];
    }
}
