<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/6
 * Time: 下午6:00
 */

namespace admin\modules\site\controllers;


use admin\modules\site\models\CarouselModel;
use common\controllers\Controller;

class CarouselController extends Controller
{
    protected $access = [
        'index'              => ['@', 'get'],
        'goods'              => ['@', 'get'],
        'get-carousel'       => ['@', 'get'],
        'update-carousel'    => ['@', 'post'],
        'insert-carousel'    => ['@', 'post'],
        'delete-carousel'    => ['@', 'post'],
        'get-oss-permission' => ['@', 'get'],
        'sort'               => ['@', 'post']
    ];


    protected $actionUsingDefaultProcess = [
        'get-carousel'       => CarouselModel::CAR_GET_CAROUSEL,
        'update-carousel'    => CarouselModel::CAR_UPDATE_CAROUSEL,
        'insert-carousel'    => CarouselModel::CAR_INSERT_CAROUSEL,
        'delete-carousel'    => CarouselModel::CAR_DELETE_CAROUSEL,
        'sort'               => CarouselModel::SCE_SORT,
        'get-oss-permission' => CarouselModel::CAR_GET_OSS_PERMISSION,
        '_model'             => '\admin\modules\site\models\CarouselModel',

    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGoods()
    {
        return $this->render('goods');
    }

    public function actionPost()
    {
        return $this->render('post');
    }
}