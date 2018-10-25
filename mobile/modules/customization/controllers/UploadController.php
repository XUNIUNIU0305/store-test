<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/8/9 0009
 * Time: 14:32
 */

namespace mobile\modules\customization\controllers;

use mobile\modules\customization\models\UploadModel;

class UploadController extends Controller
{
    protected $access = [
        'permission' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'permission' => UploadModel::SCE_PERMISSION,
        '_model' => UploadModel::class
    ];
}