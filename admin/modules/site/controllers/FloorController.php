<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/9
 * Time: 下午6:58
 */

namespace admin\modules\site\controllers;


use admin\modules\site\models\FloorModel;
use admin\controllers\Controller;

class FloorController extends Controller
{
    protected $access = [
        //楼层列表
        'get-list' => [
            '@',
            'get'
        ],

        //新增，保存楼层 name url color
        'edit-floor' => [
            '@',
            'post'
        ],

        //新增 group_name  保存 group_id,group_name
        'edit-group' => [
            '@',
            'post'
        ],


        //index_image ,view_image ,title,sell_point
        'edit-product' => [
            '@',
            'post'
        ],

        //搜索商品 product_name
        'product-list' => [
            '@',
            'get'
        ],


        //获取商品 product_id
        'product-info' => [
            '@',
            'get'
        ],

        //获取楼层商品
        'product-detail' => ['@', 'get'],

        //获取楼层信息  floor_id
        'floor-info' => [
            '@',
            'get'
        ],

        //删除楼层 floor_id
        'delete-floor' => [
            '@',
            'post'
        ],
        //删除商品 product_id
        'delete-product' => [
            '@',
            'post'
        ],

        //group_id
        'delete-group' => [
            '@',
            'post'
        ],

        //设置显示隐藏  floor_id
        'set-floor-status' => [
            '@',
            'post'
        ],

        //设置商品排序 {id:1,sort:3}
        'set-product-sort'=> [
            '@',
            'post'
        ]
    ];

    protected $actionUsingDefaultProcess = [
        'get-list' => ['scenario' => FloorModel::FL_FLOOR_LIST,'convert' => false],
        'edit-floor' => FloorModel::FL_EDIT_FLOOR,
        'edit-group' => FloorModel::FL_EDIT_GROUP,
        'edit-product' => FloorModel::FL_EDIT_PRODUCT,
        'product-list' => FloorModel::FL_SEARCH_PRODUCT,
        'product-info' => FloorModel::FL_PRODUCT_INFO,
        'product-detail' => FloorModel::FL_FLOOR_DETAIL,
        'floor-info' => ['scenario' => FloorModel::FL_FLOOR_INFO, 'convert' => false,],
        'delete-floor' => FloorModel::FL_DELETE_FLOOR,
        'delete-product' => FloorModel::FL_DELETE_PRODUCT,
        'delete-group' => FloorModel::FL_DELETE_GROUP,
        'set-floor-status' => FloorModel::FL_FLOOR_STATUS,
        'set-product-sort' => FloorModel::FL_PRODUCT_SORT,
         '_model' => '\admin\modules\site\models\FloorModel',

    ];
}
