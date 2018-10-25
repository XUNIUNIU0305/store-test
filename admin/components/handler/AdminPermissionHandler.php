<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 11:38
 */

namespace admin\components\handler;


use common\ActiveRecord\AdminPermissionAR;
use common\components\handler\Handler;
use Yii;
use yii\data\ActiveDataProvider;

class AdminPermissionHandler extends Handler
{


    //获取部门列表
    public static function getPermissionList($currentPage, $pageSize, $isMenu = null, $parentId = null)
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        /*
         * Modify By:JiangYi
         * Date:2017/3/22
         * Desc:添加查询条件，配置，当parentId取值为-1时，
         */
        return  new ActiveDataProvider([
            'query' => AdminPermissionAR::find()->select([
                'id',
                'name',
                'module_name',
                'controller_name',
                'action_name',
                'parent',
                'is_menu'
            ])->where($isMenu!=null?['is_menu'=>$isMenu]:[])
                ->andWhere(($parentId!=null&&$parentId==-1)?['>','parent','0']:[])
                ->andWhere(($parentId!=null&&$parentId>=0)?['parent'=>$parentId]:[])
                ->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

    }


}