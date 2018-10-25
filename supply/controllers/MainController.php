<?php
namespace supply\controllers;

use Yii;
use common\controllers\Controller;
use supply\models\MainModel;

class MainController extends Controller{

    public $layout = 'main';

    protected $access = [
        'index' => ['@', 'get'],
        'menu' => [null, 'get'],
    ];

    /**
     * 展示/main页面
     */
    public function actionIndex(){
        return $this->render('index');
    }

    /**
     * 获取菜单
     */
    public function actionMenu(){
        return $this->success(MainModel::getMenu());
    }
}
