<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 10:36
 */

namespace custom\modules\membrane;

class Module extends \yii\base\Module
{
    public $defaultRoute = 'home';
    public $layout = 'main';

    public function init()
    {
        $this->controllerNamespace = 'custom\modules\membrane\controllers';
        \Yii::$app->urlManager->addRules([
            $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}