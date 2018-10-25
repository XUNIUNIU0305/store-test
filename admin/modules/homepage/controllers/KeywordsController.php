<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-6
 * Time: 下午3:07
 */

namespace admin\modules\homepage\controllers;


use admin\modules\homepage\models\KeywordsModel;
use admin\controllers\Controller;

class KeywordsController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'create' => ['@', 'post'],
        'update' => ['@', 'post'],
        'delete' => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'index' => ['scenario' => KeywordsModel::SCE_GET_LIST, 'convert' => false,],
        'create' => KeywordsModel::SCE_CREATE,
        'update' => KeywordsModel::SCE_UPDATE,
        'delete' => KeywordsModel::SCE_DELETE,
        '_model' => KeywordsModel::class
    ];
}