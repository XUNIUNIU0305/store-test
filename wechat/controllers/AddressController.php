<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/17
 * Time: 16:15
 */

namespace wechat\controllers;


use wechat\models\AddressModel;

class AddressController extends Controller
{
    protected $access = [
        'list' => ['@', 'get'],
        'one' => ['@', 'get'],
        'add'=>['@','post'],
        'edit'=>['@','post'],
        'delete'=>['@','post'],
        'default'=>['@','post']

    ];

    protected $actionUsingDefaultProcess = [
        'list' => AddressModel::SCE_ADDRESS_LIST,
        'one' => AddressModel::SCE_ADDRESS_ONE,
        'add' => AddressModel::SCE_ADD_ADDRESS,
        'edit' => AddressModel::SCE_EDIT_ADDRESS,
        'delete' => AddressModel::SCE_REMOVE_ADDRESS,
        'default' => AddressModel::SCE_SET_DEFAULT,
        '_model' => AddressModel::class
    ];
}