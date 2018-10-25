<?php
namespace api\controllers;

use Yii;

class Controller extends \common\controllers\Controller{

    protected function returnJson($code, $param, $convert){
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET');
        return parent::returnJson($code, $param, $convert);
    }
}
