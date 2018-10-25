<?php
namespace admin\modules\count;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface{

    public $layout = 'count';

    public function bootstrap($app){
        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'pattern' => $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>',
                'route' => $this->id . '/<controller>/<action>',
            ]
        ]);
    }
}
