<?php
namespace custom\modules\temp;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface{

    public $layout = 'account';

    public function init(){
        parent::init();
        $this->setLayoutPath('@account/views/layouts');
    }

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
