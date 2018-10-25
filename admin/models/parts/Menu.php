<?php
namespace admin\models\parts;

use admin\models\parts\role\AdminPermission;
use common\ActiveRecord\AdminPermissionAR;
use Yii;
use yii\base\Object;


class Menu extends Object
{

    public static function getTopMenu(array $fields = [])
    {
        /*获取用户拥有权限列表*/

        return array_map(function ($item) {
            return [
                'id' => $item["id"],
                'title' => $item['name'],
                'url' => '/' . $item['module_name']
            ];

        }, Yii::$app->RQ->AR(new AdminPermissionAR())->all([
            'select'=>['id','name','module_name'],
            'where'=>['is_menu'=>AdminPermission::IS_MENU,'parent'=>0]
        ]));


        /*
        return Yii::$app->RQ->AR(new AdminTopMenuAR)->all([
            'select' => $fields,
            'orderBy' => ['sort' => SORT_ASC],
        ]);*/
    }

    public static function getSecondaryMenu(int $topId = null, array $fields = [])
    {
        return array_map(function ($item) {
            return [
                'id' => $item->id,
                'admin_top_menu_id' => $item->getParent()->id,
                'title' => $item->getName(),
                'url' => '/' . $item->getModuleName() . "/" . $item->getControllerName() . "/" . $item->getActionName(),
            ];
        },  Yii::$app->AdminUser->menus->getUserMenus($topId));

    }



}
