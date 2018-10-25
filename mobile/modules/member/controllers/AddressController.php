<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 11:51
 * Desc:
 */

namespace mobile\modules\member\controllers;

use mobile\modules\member\models\AddressModel;

class AddressController extends Controller
{


    public $access=[
        'index'=>['@','get'],//地址列表
        'add'=>['@','get'],//新增地址
        'edit-view'=>['@','get'],//当前编辑地址信息
        'get-default-address'=>['@','get'],//获取用户默认地址
        'get-address-list'=>['@','get'],//获取用户地址列表
        'delete'=>['@','get'],//删除用户地址
        'set-default'=>['@','get'],//设置默认地址
        'add-address'=>['@','post'],
        'edit-address'=>['@','post'],
    ];

    public $actionUsingDefaultProcess=[
        'get-default-address'=>[
            'scenario' => AddressModel::SCE_GET_DEFAULT_ADDRESS,
            'convert' => false,
        ],
        'delete'=>AddressModel::SCE_REMOVE_ADDRESS,
        'set-default'=>AddressModel::SCE_SET_DEFAULT,
        'edit-view'=>[
            'scenario' => AddressModel::SCE_EDIT_VIEW,
            'convert' => false,
        ],
        'add-address'=>AddressModel::SCE_ADD_ADDRESS,
        'edit-address'=>AddressModel::SCE_EDIT_ADDRESS,
        '_model'=>'mobile\modules\member\models\AddressModel',
    ];


    /**
     *====================================================
     * 新增地址页面
     * @return string
     * @author shuang.li
     *====================================================
     */
    public function actionAdd(){
        return $this->render('add');
    }


    /**
     * Author:JiangYi
     * Date:2017/5/23
     * Desc:地址列表视图输出
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
    }

    /**
     * Author:JiangYi
     * Date:2017/5/23
     * Desc:获取地址列表
     * @return array
     */
    public function actionGetAddressList(){
        return $this->success(AddressModel::displayList());
    }

}
