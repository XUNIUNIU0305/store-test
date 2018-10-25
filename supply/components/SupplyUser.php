<?php
namespace supply\components;

use Yii;
use common\components\basic\LoggedUserAbstract;

class SupplyUser extends LoggedUserAbstract{

    protected function getUserComponents(){
        return [
            'order' => 'supply\components\UserOrder',
            'User'=>[
                'class'=>'common\models\parts\supply\SupplyUser',
                'id'=>Yii::$app->user->id,
            ],
            'product' => 'supply\components\handler\ProductHandler',
        ];
    }
}
