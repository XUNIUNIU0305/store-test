<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/8/20
 * Time: 15:03
 */

namespace mobile\modules\gpubs\controllers;

use common\controllers\Controller;
use mobile\modules\gpubs\models\RecommendModel;

class RecommendController extends Controller
{
    public $access = [
        'hot-list'  => [null, 'get'],
        'get-group' => ['@', 'get'],
    ];

    public $actionUsingDefaultProcess = [
        'hot-list'      =>  RecommendModel::SCE_GET_HOT_LIST,
        'get-group'     =>  RecommendModel::SCE_GET_SET_GROUP,
        '_model'        =>  RecommendModel::class,
    ];

}