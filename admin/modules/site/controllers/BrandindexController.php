<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/27
 * Time: 下午4:27
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\BrandindexModel;

class BrandindexController extends Controller
{
    protected $access = [
        'index'=>['@','get'],
        'list-header-adv' => ['@', 'get'],//主广告
        'list-big-small-adv' => ['@', 'get'],//主广告
        'list-hot-brand' => ['@', 'get'],//热销品牌
        'list-brand-album' => ['@', 'get'],//品牌特辑

        'create-header-adv'=>['@','post'],
        'create-hot-brand'=>['@','post'],
        'create-brand-album'=>['@','post'],

        'edit-header-adv'=>['@','post'],
        'edit-hot-brand'=>['@','post'],
        'edit-brand-album'=>['@','post'],

        'remove-header-adv'=>['@','post'],
        'remove-brand'=>['@','post'],
        'hot-brand-status'=>['@','post'],

    ];

    protected $actionUsingDefaultProcess = [
        'list-header-adv' => BrandindexModel::SCE_HEADER_ADV_LIST,
        'list-big-small-adv' => BrandindexModel::SCE_BIG_SMALL_ADV_LIST,
        'list-hot-brand' => BrandindexModel::SCE_HOT_BRAND_LIST,
        'list-brand-album' => BrandindexModel::SCE_BRAND_ALBUM_LIST,

        'create-header-adv'=> BrandindexModel::SCE_HEADER_ADV_CREATE,
        'create-hot-brand'=> BrandindexModel::SCE_HOT_BRAND_CREATE,
        'create-brand-album'=> BrandindexModel::SCE_BRAND_ALBUM_CREATE,

        'edit-header-adv'=> BrandindexModel::SCE_HEADER_ADV_EDIT,
        'edit-hot-brand'=> BrandindexModel::SCE_HOT_BRAND_EDIT,
        'edit-brand-album'=> BrandindexModel::SCE_BRAND_ALBUM_EDIT,

        'remove-header-adv'=> BrandindexModel::SCE_HEADER_ADV_REMOVE,
        'remove-brand'=> BrandindexModel::SCE_BRAND_REMOVE,
        'hot-brand-status'=> BrandindexModel::SCE_SET_HOT_BRAND_STATUS,

        '_model' => '\admin\modules\site\models\BrandindexModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}