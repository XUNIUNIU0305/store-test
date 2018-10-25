<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 07/06/18
 * Time: 15:14
 */

namespace business\modules\data\controllers;

use business\modules\data\models\ConsumedCustomModel;

class ConsumedCustomController extends Controller
{
    public $access = [
        'get-info' => ['', 'get'],
    ];

    public $actionUsingDefaultProcess = [
        'get-info' => ConsumedCustomModel::SCE_GET_INFO,
        '_model' => ConsumedCustomModel::class,
    ];
}