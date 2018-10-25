<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 上午11:53
 */

namespace admin\modules\homepage\controllers;

use admin\modules\homepage\models\IndexModel;
use admin\controllers\Controller;

class IndexController extends Controller
{
    protected $access = [
        'search-brand' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'search-brand'  => IndexModel::SCE_SEARCH_BRAND,
        '_model' => IndexModel::class
    ];
}