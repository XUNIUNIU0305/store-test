<?php
namespace business\modules\membrane;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module
{
    public $defaultRoute = 'home';
    public $layout = 'main';

    public function init()
    {
        parent::init();
        Yii::$app->urlManager->addRules([
            $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>' => $this->id . '/<controller>/<action>'
        ]);
    }
}
