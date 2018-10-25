<?php
namespace custom\modules\temp\controllers;

use Yii;
use common\controllers\Controller;

class AlphabetController extends Controller{

    public $layout = 'empty';

    public function init(){
        parent::init();
        $this->module->layoutPath = '@temp/views/layouts';
    }

    public function actionA(){
        throw new \yii\web\NotFoundHttpException;
    }

    //public function actionB(){
        //return $this->render('b');
    //}

    public function actionC(){
        return $this->render('c');
    }


    public function actionD(){
        return $this->render('d');
    }

    public function actionE(){
        return $this->render('e');
    }

    public function actionF(){
        return $this->render('f');
    }

    public function actionG() {
        return $this->render('g');
    }

    public function actionH() {
        return $this->render('h');
    }

    public function actionI() {
        return $this->render('i');
    }

    public function actionJ(){
        $this->layout = 'blank';
        return $this->render('j');
    }

    public function actionK(){
        $this->layout = 'blank';
        return $this->render('k');
    }

    public function actionL(){
        $this->layout = 'blank';
        return $this->render('l');
    }

    public function actionM(){
        $this->layout = 'blank';
        return $this->render('m');
    }

    public function actionN(){
        $this->layout = 'blank';
        return $this->render('n');
    }

    public function actionO(){
        $this->layout = 'blank';
        return $this->render('o');
    }
    
    public function actionP(){
        $this->layout = 'blank';
        return $this->render('p');
    }
    
    public function actionQ(){
        $this->layout = 'blank';
        return $this->render('q');
    }

    public function actionR(){
        $this->layout = 'blank';
        return $this->render('r');
    }
    
    // 存在bug 禁止使用
    // public function actionS(){
    //     return $this->render('s');
    // }
    
    public function actionT(){
        return $this->render('t');
    }
    
    public function actionU(){
        return $this->render('u');
    }

    public function actionV(){
        return $this->render('v');
    }
    
    public function actionW(){
        $this->layout = 'blank';
        return $this->render('w');
    }
}
