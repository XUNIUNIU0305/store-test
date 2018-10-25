<?php
namespace admin\controllers;


use Yii;


class MainController extends \common\controllers\Controller
{

    public $layout = 'menu';

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}
