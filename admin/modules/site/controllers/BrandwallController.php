<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午5:25
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\BrandwallModel;


class BrandwallController extends Controller
{
    protected $access = [
        'set-brand' => [
            '@',
            'post'
        ],
        'confirm'   => [
            '@',
            'post'
        ],

        'get-list' => [
            '@',
            'get'
        ],


    ];


    protected $actionUsingDefaultProcess = [
        'set-brand' => BrandwallModel::BRAND_SET_BRAND,
        'get-list'  => BrandwallModel::BRAND_GET_LIST,
        'confirm'   => BrandwallModel::BRAND_CONFIRM,
        '_model'    => '\admin\modules\site\models\BrandwallModel',

    ];


}