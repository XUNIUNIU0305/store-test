<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 上午11:08
 */

namespace business\modules\data;


class Module extends \yii\base\Module
{
    public $defaultRoute = 'home';
    public $controllerNamespace = 'business\modules\data\controllers';

    public function init()
    {
        \Yii::$app->urlManager->addRules([
            $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}