<?php
namespace custom\modules\quality;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{

    public $defaultRoute = 'index/index';

    public $layout = 'quality';
    public function bootstrap($app){
        $app->getUrlManager()->addRules([
            ['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>', 'route' => $this->id . '/<controller>/<action>'],
        ]);
    }
}
