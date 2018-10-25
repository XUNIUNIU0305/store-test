<?php
namespace custom\components;

use Yii;
use common\components\basic\LoggedUserAbstract;
use common\ActiveRecord\CustomUserAR;

class CustomUser extends LoggedUserAbstract{

    protected function getUserComponents(){
        return [
            'cart' => 'custom\components\ShoppingCart',
            'address' => 'custom\components\UserAddress',
            'wallet' => [
                'class' => 'custom\models\parts\trade\Wallet',
                'userId' => Yii::$app->user->id,
            ],
            'CurrentUser'=>[
                'class'=>'common\models\parts\custom\CustomUser',
                'id'=>Yii::$app->user->id,
            ],
            'order' => 'custom\components\UserOrder',
            'statement' => 'custom\components\UserStatement',
            'article'=>[
                'class'=>'custom\models\parts\ArticleFooter',
                'userId'=>Yii::$app->user->id,
            ],
            'auth'=>[
                'class'=>'common\models\parts\partner\Authorization',
                'userId'=>Yii::$app->user->id,
            ],
        ];
    }

    public function modifyPasswd($origin, $new, $return = 'throw'){
        if(Yii::$app->security->validatePassword($origin, Yii::$app->user->identity->passwd)){
            return Yii::$app->RQ->AR(CustomUserAR::findOne(Yii::$app->user->id))->update([
                'passwd' => Yii::$app->security->generatePasswordHash($new),
            ], $return);
        }else{
            return Yii::$app->EC->callback($return, 'incorrect origin password');
        }
    }
}
