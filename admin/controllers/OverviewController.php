<?php
namespace admin\controllers;

use Yii;


class OverviewController extends \common\controllers\Controller
{

    public $layout = 'overview';

    protected $access = [
        'index' => ['@', 'get'],
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}
