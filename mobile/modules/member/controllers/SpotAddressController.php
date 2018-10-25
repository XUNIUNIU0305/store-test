<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 11:51
 * Desc:
 */

namespace mobile\modules\member\controllers;

use mobile\modules\member\models\SpotAddressModel;

class SpotAddressController extends Controller
{


    public $access=[
        'gpubs-index'=>['@','get'],//地址列表 页面
        'gpubs-add'=>['@','get'],//新增自提点 页面

        'gpubs-list' => ['@','get'],//自提点地址列表
        'get-default-address' => ['@','get'],//获取自提点默认地址
        'get-gpubs-address' => ['@','get'],//获取自提点信息

        'set-default'=>['@','post'],//设置默认自提点
        'add-gpubs-address'=>['@','post'],//添加自提点信息
        'edit-gpubs-address'=>['@','post'],//编辑自提点信息
        'gpubs-delete'=>['@','post'],//删除自提点地址
    ];

    public $actionUsingDefaultProcess=[
        'gpubs-list'=>SpotAddressModel::SCE_LIST_GPUBS_ADDRESS,
        'get-default-address'=>SpotAddressModel::SCE_GET_DEFAULT_ADDRESS,
        'get-gpubs-address' =>SpotAddressModel::SCE_GET_GPUBS_ADDRESS,

        'set-default'=>SpotAddressModel::SCE_SET_DEFAULT,
        'add-gpubs-address' => SpotAddressModel::SCE_ADD_GPUBS_ADDRESS,
        'edit-gpubs-address' => SpotAddressModel::SCE_EDIT_GPUBS_ADDRESS,
        'gpubs-delete' => SpotAddressModel::SCE_DELETE_GPUBS_ADDRESS,

        '_model'=>'mobile\modules\member\models\SpotAddressModel',
    ];

    public function actionGpubsIndex(){
        return $this->render('gpubs-index');
    }
    public function actionGpubsAdd(){
        return $this->render('gpubs-add');
    }

}
