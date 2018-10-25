<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 上午11:02
 */

namespace supply\modules\express;


class Module extends \yii\base\Module
{
    public $defaultRoute = 'index';
//    public $layout = 'main';

    public function init()
    {
        $this->controllerNamespace = 'supply\modules\express\controllers';
        \Yii::$app->urlManager->addRules([
            $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}