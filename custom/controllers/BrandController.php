<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/8
 * Time: 上午9:54
 */

namespace custom\controllers;


use common\controllers\Controller;
use custom\models\BrandModel;

class BrandController extends Controller
{
    public $layout = 'header_footer_search';

    protected $access = [
        'index' => ['@', 'get'],
        'get-top-adv' => ['@', 'get'],
        'get-big-small-adv' => ['@', 'get'],
        'get-hot-brand' => ['@', 'get'],
        'get-brand-album' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-top-adv' => BrandModel::SCE_TOP_ADV,
        'get-big-small-adv' => BrandModel::SCE_BIG_SMALL_ADV,
        'get-hot-brand' =>BrandModel::SCE_HOT_BRAND,
        'get-brand-album' => BrandModel::SCE_BRAND_ALBUM,
        '_model' => '\custom\models\BrandModel',
    ];

    public function actionIndex()
    {
        return $this->render("index");
    }
}