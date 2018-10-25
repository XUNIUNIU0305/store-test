<?php
namespace admin\modules\count\controllers;

//use admin\controllers\Controller;
use admin\controllers\Controller;

class ItemRankController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'detail'=>['@','get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    /*
     * Mod:Jiangyi
     * Date:2017/04/14
     * Desc:添加单品销量页面
     */
    public function actionDetail(){
        return $this->render('detail');
    }
}
