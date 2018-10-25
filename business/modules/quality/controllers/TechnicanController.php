<?php
/**
 * User: JiangYi
 * Date: 2017/5/28
 * Time: 15:52
 * Desc:
 */

namespace business\modules\quality\controllers;


use business\controllers\Controller;
use business\modules\quality\models\TechnicanModel;

class TechnicanController  extends  Controller
{

    public $access=[
        'index'=>['20','get'],//技师列表首页
        'get-list'=>['20','get'],//获取支师列表
        'remove'=>['20','get'],//删除技师信息
        'save'=>['20','post'],//保存更新
        'add'=>['20','post'],//添加
    ];


    public $actionUsingDefaultProcess=[
        'get-list'=>TechnicanModel::SCE_GET_LIST,
        'remove'=>TechnicanModel::SCE_REMOVE,
        'save'=>TechnicanModel::SCE_SAVE,
        'add'=>TechnicanModel::SCE_ADD,
        '_model'=>'business\modules\quality\models\TechnicanModel',
    ];

    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:技术列表首页
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
    }

}