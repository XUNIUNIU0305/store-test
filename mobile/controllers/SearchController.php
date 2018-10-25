<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/10/9
 * Time: 下午6:04
 */

namespace mobile\controllers;
use custom\models\SearchModel;

class SearchController extends Controller
{
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

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionCategory(){
        return $this->render('category');
    }

    public function actionCategoryDetail(){
        return $this->render('detail');
    }
}