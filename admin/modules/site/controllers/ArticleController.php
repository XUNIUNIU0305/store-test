<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/4
 * Time: 上午10:01
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\ArticleModel; 
class ArticleController extends Controller
{
    protected $access = [
        'index'=>['@','get'],
        'create'=>['@','get'],
        'list'=>['@','get'],
        'content'=>['@','get'],
        'insert'=>['@','post'],
        'edit'=>['@','post'],
        'remove'=>['@','post'],
     ];

    protected $actionUsingDefaultProcess = [
        'list'=>ArticleModel::SCE_LIST,
        'content' => [
            'scenario'=>ArticleModel::SCE_CONTENT,
            'convert'=>false,
        ],
        'insert'=>ArticleModel::SCE_INSERT,
        'edit'=>ArticleModel::SCE_EDIT,
        'remove'=>ArticleModel::SCE_REMOVE,
        '_model' => '\admin\modules\site\models\ArticleModel',

    ];

    public function actionIndex(){
        return $this->render("index");
    }

    public function actionCreate(){
        return $this->render('create');
    }


}