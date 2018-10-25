<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/28 0028
 * Time: 11:48
 */

namespace mobile\modules\membrane\controllers;

use common\models\parts\custom\CustomUser;
use business\models\parts\Area;

class Controller extends \common\controllers\Controller
{
    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            if(\Yii::$app->user->isGuest){
                return false;
            }
            $user = new CustomUser([
                'id' => \Yii::$app->user->id,
            ]);
            if($user->getLevel() < CustomUser::LEVEL_PARTNER || $user->getArea()->id == Area::DEFAULT_FIFTH_ID)
                return $this->redirect(['error/index']);
            return true;
        }
        return false;
    }
}
