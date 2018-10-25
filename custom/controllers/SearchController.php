<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/12
 * Time: 下午4:51
 */

namespace custom\controllers;

use common\controllers\Controller;
use custom\models\SearchModel;

class SearchController extends Controller {
    public $layout = 'header_footer_search';

    protected $access = [
        'index'=>[null,'get'],
        'result'=>[null,'get'],
        'category'=>[null,'get'],
        'category-attribute'=>[null,'get'],
        'category-goods'=>[null,'get']
    ];
    protected $actionUsingDefaultProcess = [
        'result'=>SearchModel::SCE_RESULT,
        'category-attribute'=>SearchModel::SCE_CATEGORY_ATTRIBUTE,
        'category-goods'=>SearchModel::SCE_CATEGORY_GOODS,
        '_model'=>'custom\models\SearchModel'
    ];


    //搜索显示页面
    public function actionIndex(){
        return $this->render('index');
    }

    //分类显示页面
    public function actionCategory(){
        return $this->render('category');
    }

}