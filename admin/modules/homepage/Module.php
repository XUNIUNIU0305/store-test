<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 上午11:51
 */

namespace admin\modules\homepage;


class Module extends \yii\base\Module
{
    public $defaultRoute = 'index';

    public $layout = '/global';

    public function init()
    {
        $this->controllerNamespace = 'admin\modules\homepage\controllers';
        \Yii::$app->urlManager->addRules([
            $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}