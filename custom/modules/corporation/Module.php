<?php
namespace custom\modules\corporation;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface{

    public $layout = 'empty';

    public function bootstrap($app){
        $app->urlManager->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'pattern' => $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>',
                'route' => $this->id . '/<controller>/<action>',
            ],
        ]);
    }
}
