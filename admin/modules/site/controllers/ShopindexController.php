<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/2
 * Time: 下午4:22
 */

namespace admin\modules\site\controllers;
use admin\controllers\Controller;
use admin\modules\site\models\ShopindexModel;

class ShopindexController extends Controller
{

    protected $access = [
        'list'=>['@','get'],
        'edit'=>['@','post'],
        'create'=>['@','post'],
    ];

    protected $actionUsingDefaultProcess = [
        'list'=>ShopindexModel::SCE_LIST,
        'edit'=>ShopindexModel::SCE_EDIT,
        'create'=>ShopindexModel::SCE_CREATE,
        '_model' => '\admin\modules\site\models\ShopindexModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}