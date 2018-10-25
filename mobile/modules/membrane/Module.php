<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/28 0028
 * Time: 11:45
 */

namespace mobile\modules\membrane;

class Module extends \yii\base\Module
{
    public $layout = 'main';
    public $defaultRoute = 'home';

    public function init()
    {
        $this->controllerNamespace = 'mobile\modules\membrane\controllers';
        \Yii::$app->urlManager->addRules([
            $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}