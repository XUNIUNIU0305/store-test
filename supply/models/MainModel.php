<?php
namespace supply\models;

use Yii;
use common\models\Model;
use supply\models\parts\MenuModel;

class MainModel extends Model{
    
    /**
     * 获取菜单
     *
     * @return array
     */
    public static function getMenu(){
        return MenuModel::getMenu();
    }
}
