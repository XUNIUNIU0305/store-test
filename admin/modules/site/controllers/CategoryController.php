<?php
namespace admin\modules\site\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\site\models\CategoryModel;

class CategoryController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'add' => ['@', 'post'],
        'edit' => ['@', 'post'],
        'remove' => ['@', 'post'],
        'attributes' => ['@', 'get'],
        'add-attribute' => ['@', 'post'],
        'delete-option' => ['@', 'post'],
        'edit-attribute' => ['@', 'post'],
        'add-keyword' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'add' => CategoryModel::SCE_ADD_CATEGORY,
        'edit' => CategoryModel::SCE_MODIFY_CATEGORY,
        'remove' => CategoryModel::SCE_REMOVE_CATEGORY,
        'attributes' => CategoryModel::SCE_GET_CATEGORY_ATTRIBUTES,
        'add-attribute' => CategoryModel::SCE_ADD_CATEGORY_ATTRIBUTE,
        'delete-option' => CategoryModel::SCE_DELETE_OPTION,
        'edit-attribute' => CategoryModel::SCE_EDIT_ATTRIBUTE,
        'add-keyword' => CategoryModel::SCE_ADD_KEYWORD,
        '_model' => '\admin\modules\site\models\CategoryModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
