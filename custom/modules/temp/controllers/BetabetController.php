<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 21/06/18
 * Time: 14:49
 */

namespace custom\modules\temp\controllers;

use common\controllers\Controller;
use yii;
class BetabetController extends Controller
{
    public $layout = 'empty';

    public function init()
    {
        parent::init();
        $this->module->layoutPath = '@temp/views/layouts';
    }

    public function actionA() {
        $this->layout = 'blank';
        return $this->render('a');
    }

    public function actionB()
    {
        $this->layout = 'blank';
    return $this->render('b');
    }

    public function actionC()
    {
        $this->layout = 'blank';
        return $this->render('c');
    }


    public function actionD()
    {
        $this->layout = 'blank';
        return $this->render('d');
    }

    public function actionE()
    {
        $this->layout = 'blank';
        return $this->render('e');
    }

    public function actionF()
    {
        $this->layout = 'blank';
        return $this->render('f');
    }

    public function actionG() {
        $this->layout = 'blank';
        return $this->render('g');
    }

    public function actionH() {
        $this->layout = 'blank';
        return $this->render('h');
    }

    public function actionI() {
        $this->layout = 'blank';
        return $this->render('i');
    }

    public function actionJ()
    {
        if(yii::$app->user->getIsGuest())yii::$app->user->loginRequired();
        $this->layout = 'blank';
        return $this->render('j');
    }

    public function actionK()
    {
        $this->layout = 'blank';
        return $this->render('k');
    }

    public function actionL()
    {
        $this->layout = 'blank';
        return $this->render('l');
    }

    public function actionM()
    {
        $this->layout = 'blank';
        return $this->render('m');
    }

    public function actionN()
    {
        $this->layout = 'blank';
        return $this->render('n');
    }

    public function actionO()
    {
        $this->layout = 'blank';
        return $this->render('o');
    }

    public function actionP()
    {
        $this->layout = 'blank';
        return $this->render('p');
    }

    public function actionQ()
    {
        $this->layout = 'blank';
        return $this->render('q');
    }

    public function actionR()
    {
        $this->layout = 'blank';
        return $this->render('r');
    }

    // 存在bug 禁止使用
    // public function actionS()
    // {
    //     return $this->render('s');
    // }

    public function actionT()
    {
        $this->layout = 'blank';
        return $this->render('t');
    }

    public function actionU()
    {
        $this->layout = 'blank';
        return $this->render('u');
    }

    public function actionV()
    {
        $this->layout = 'blank';
        return $this->render('v');
    }

    public function actionW()
    {
        $this->layout = 'blank';
        return $this->render('w');
    }
    public function actionX()
    {
        $this->layout = 'blank';
        return $this->render('x');
    }

    public function actionY()
    {
        $this->layout = 'blank';
        return $this->render('y');
    }

    public function actionZ()
    {
        $this->layout = 'blank';
        return $this->render('z');
    }
}
