<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 上午11:49
 */

namespace mobile\modules\lottery;


class Module extends \yii\base\Module
{
    public function init()
    {
        $this->layout = 'main';
        $this->defaultRoute = 'index';
        $this->controllerNamespace = 'mobile\modules\lottery\controllers';
        \Yii::$app->urlManager->addRules([
            $this->id . '/<controller:(\w|-)+>/<action:(\w|-)+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}