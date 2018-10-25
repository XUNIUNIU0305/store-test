<?php
namespace custom\modules\account;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface{

    public $defaultRoute = 'index/index';

    public $layout = 'account';

    public function bootstrap($app){
        $app->getUrlManager()->addRules([
            ['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/<controller:[a-z-]+>/<action:[a-z-]+>', 'route' => $this->id . '/<controller>/<action>'],
        ]);
    }
}
