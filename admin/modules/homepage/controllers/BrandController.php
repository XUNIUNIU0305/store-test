<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-8
 * Time: 上午10:09
 */

namespace admin\modules\homepage\controllers;


use admin\modules\homepage\models\BrandModel;
use admin\controllers\Controller;

class BrandController extends Controller
{
    protected $access = [
        'index'  => ['@', 'get'],
        'create' => ['@', 'post'],
        'update' => ['@', 'post'],
        'one'    => ['@', 'get'],
        'delete' => ['@', 'post'],
        'sort'   => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'index'  => BrandModel::SCE_GET_LIST,
        'create' => BrandModel::SCE_CREATE,
        'update' => BrandModel::SCE_UPDATE,
        'one'    => BrandModel::SCE_GET_BRAND,
        'delete' => BrandModel::SCE_DELETE,
        'sort'   => BrandModel::SCE_SORT,
        '_model' => BrandModel::class
    ];
}