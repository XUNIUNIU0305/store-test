<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 15:27
 */

namespace custom\modules\membrane\controllers;

use common\models\parts\custom\CustomUser;
use business\models\parts\Area;

class Controller extends \common\controllers\Controller
{
    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            $user = \Yii::$app->CustomUser->CurrentUser;
            if($user->getLevel() < CustomUser::LEVEL_PARTNER || $user->getArea()->id == Area::DEFAULT_FIFTH_ID)
                return $this->redirect(['error/index']);
            return true;
        }
        return false;
    }
}
