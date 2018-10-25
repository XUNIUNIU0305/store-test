<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 上午10:35
 */

namespace mobile\modules\lottery\controllers;

class IndexController extends Controller
{
    protected $access = [
        'index' => ['@', 'get']
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }
}