<?php
namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\account\models\WechatModel;

class WechatController extends Controller{

    public function actionIndex(){
        return $this->render('index');
    }
}
