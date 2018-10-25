<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/31 0031
 * Time: 16:53
 */

namespace mobile\modules\customization;


class Module extends \yii\base\Module
{
    public function init()
    {
        $this->layout = 'main';
        $this->defaultRoute = 'home';
        $this->controllerNamespace = 'mobile\modules\customization\controllers';
        \Yii::$app->urlManager->addRules([
            $this->id . '/<controller:(\w|-)+>/<action:(\w|-)+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}