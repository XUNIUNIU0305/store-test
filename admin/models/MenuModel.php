<?php
namespace admin\models;

use common\ActiveRecord\AdminPermissionAR;
use Yii;
use common\models\Model;
use admin\models\parts\Menu;
use common\ActiveRecord\AdminTopMenuAR;
use common\components\handler\Handler;

class MenuModel extends Model{

    public $top_menu_id;

    const SCE_GET_SECONDARY_MENU = 'get_secondary_menu';

    public function scenarios(){
        return [
            self::SCE_GET_SECONDARY_MENU => [
                'top_menu_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['top_menu_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['top_menu_id'],
                'exist',
                'targetClass' => AdminPermissionAR::className(),
                'targetAttribute' => 'id',
                'message' => 5011,
            ],
        ];
    }

    public function getSecondaryMenu(){
        $secondaryMenu = Menu::getSecondaryMenu(intval($this->top_menu_id));
        return array_map(function($menu){
            return Handler::getMultiAttributes($menu, [
                'id',
                'top_menu_id' => 'admin_top_menu_id',
                'title',
                'url',
            ]);
        }, $secondaryMenu);
    }

    public static function getTopMenu(){
        return Menu::getTopMenu();
    }
}
