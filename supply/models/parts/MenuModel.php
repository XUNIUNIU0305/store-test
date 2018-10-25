<?php
namespace supply\models\parts;

use Yii;
use common\models\Model;
use common\ActiveRecord\SupplyTopMenuAR;
use common\ActiveRecord\SupplySecondaryMenuAR;

class MenuModel extends Model{

    /**
     * 获取完整的菜单分类
     *
     * @return array
     */
    public static function getMenu(){
        $topMenu = self::getTopMenu();
        $secondaryMenu = self::getSecondaryMenu();
        return array_map(function($menu)use($secondaryMenu){
            $menu['children'] = array_values(
                array_filter($secondaryMenu, function($secondary)use($menu){
                    return $menu['id'] == $secondary['parent_id'];
                })
            );
            return $menu;
        }, $topMenu);
    }

    /**
     * 获取顶级菜单
     *
     * @return array
     */
    public static function getTopMenu(){
        return SupplyTopMenuAR::find()->select(['id', 'title', 'img'])->orderBy(['sort' => SORT_ASC])->asArray()->all();
    }

    /**
     * 获取二级菜单
     *
     * @return array
     */
    public static function getSecondaryMenu(){
        return SupplySecondaryMenuAR::find()->select(['id', 'parent_id' => 'supply_top_menu_id', 'title', 'url'])->orderBy(['sort' => SORT_ASC])->asArray()->all();
    }
}
