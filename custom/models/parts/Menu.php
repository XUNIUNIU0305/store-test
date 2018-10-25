<?php
namespace custom\models\parts;

use Yii;
use yii\base\Object;
use common\models\RapidQuery;
use common\ActiveRecord\CustomAccountTopMenuAR;
use common\ActiveRecord\CustomAccountSecondaryMenuAR;

class Menu extends Object{

    public function getTopMenu($fields = ['id', 'title']){
        return (new RapidQuery(new CustomAccountTopMenuAR))->all([
            'select' => $fields,
            'orderBy' => ['sort' => SORT_ASC],
        ]);
    }

    public function getSecondaryMenu($fields = ['id', 'custom_account_top_menu_id', 'title', 'url'], $topId = null){
        return (new RapidQuery(new CustomAccountSecondaryMenuAR))->all([
            'select' => $fields,
            'filterWhere' => ['custom_account_top_menu_id' => $topId],
            'orderBy' => ['sort' => SORT_ASC],
        ]);
    }

    public function getFullMenu(){
        $topMenu = $this->getTopMenu();
        $secondaryMenu = $this->getSecondaryMenu();
        return array_map(function($menu)use($secondaryMenu){
            foreach($secondaryMenu as $k => $sMenu){
                if($menu['id'] == $sMenu['custom_account_top_menu_id']){
                    $menu['children'][] = $sMenu;
                    unset($secondaryMenu[$k]);
                }
            }
            return $menu;
        }, $topMenu);
    }
}
