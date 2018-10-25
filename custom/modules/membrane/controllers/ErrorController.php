<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/8/11 0011
 * Time: 16:43
 */

namespace custom\modules\membrane\controllers;

use yii\web\Controller;

class ErrorController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}