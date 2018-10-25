<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/6/23
 * Time: 13:51
 */

namespace mobile\modules\temp\controllers;

class SpikeController extends Controller
{
    public function actionIndex(){
        return $this->render('index');
    }
}