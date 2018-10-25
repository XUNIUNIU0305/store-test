<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-28
 * Time: 上午11:35
 */

namespace mobile\controllers;


use mobile\models\CartModel;

class CartController extends Controller
{
    protected $access = [
        'list'      => ['@', 'get'],
        'change'    => ['@', 'post'],
        'remove'    => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'list'      => CartModel::SCE_GET_LIST,
        'change'    => CartModel::SCE_CHANGE_ITEM,
        'remove'    => CartModel::SCE_REMOVE_ITEM,
        '_model'    => CartModel::class
    ];
}