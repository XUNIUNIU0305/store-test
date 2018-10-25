<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/2
 * Time: 11:10
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\SupplyModel;

class SupplyController extends Controller
{


    protected $access=[

        'get-supply-list'=>['@','get'],
    ];

    protected $actionUsingDefaultProcess=[
        'get-supply-list'=>SupplyModel::SCE_GET_SUPPLY_LIST,
        '_model'=>'\admin\modules\site\models\SupplyModel',
    ];



}