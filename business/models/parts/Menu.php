<?php
namespace business\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\BusinessSecondaryMenuAR;
use common\ActiveRecord\BusinessTopMenuAR;
use yii\base\InvalidConfigException;

class Menu extends Object{

    const TYPE_NOT_ABSOLUTE = 0;
    const TYPE_ABSOLUTE = 1;

    public $level;

    private $_level;
    private $_secondaryMenu;
    private $_topMenu;
    private $_fullMenu;

    public function init(){
        if(($this->_level = (int)$this->level) < 0)throw new InvalidConfigException('unavailable privilege level');
    }

    public function getSecondaryMenu(){
        return is_null($this->_secondaryMenu) ? ($this->_secondaryMenu = $this->generateSecondaryMenu($this->_level)) : $this->_secondaryMenu;
    }

    public function getTopMenu(){
        return is_null($this->_topMenu) ? ($this->_topMenu = $this->generateTopMenu($this->getSecondaryMenu())) : $this->_topMenu;
    }

    public function getFullMenu(){
        if(is_null($this->_fullMenu)){
            $topMenu = $this->getTopMenu();
            $secondaryMenu = $this->getSecondaryMenu();
            $this->_fullMenu = empty($topMenu) ? [] : array_map(function($topMenu)use($secondaryMenu){
                $topMenu['secondary'] = $secondaryMenu[$topMenu['id']];
                return $topMenu;
            }, $topMenu);
        }
        return $this->_fullMenu;
    }

    protected function generateTopMenu(array $secondaryMenu){
        if(empty($secondaryMenu))return [];
        $topMenuIds = array_keys($secondaryMenu);
        return ($this->_topMenu = Yii::$app->RQ->AR(new BusinessTopMenuAR)->all([
            'select' => ['id', 'name'],
            'where' => ['id' => $topMenuIds],
        ]));
    }

    protected function generateSecondaryMenu(int $level){
        $commonMenu = Yii::$app->RQ->AR(new BusinessSecondaryMenuAR)->all([
            'select' => ['id', 'business_top_menu_id', 'name', 'url'],
            'where' => ['<=', 'level', $level],
            'andWhere' => ['is_absolute' => self::TYPE_NOT_ABSOLUTE],
        ]);
        $personalMenu = Yii::$app->RQ->AR(new BusinessSecondaryMenuAR)->all([
            'select' => ['id', 'business_top_menu_id', 'name', 'url'],
            'where' => ['level' => $level],
            'andWhere' => ['is_absolute' => self::TYPE_ABSOLUTE],
        ]);
        $menu = array_merge($commonMenu, $personalMenu);
        $topMenuIds = array_unique(array_column($menu, 'business_top_menu_id'));
        $secondaryMenu = [];
        foreach($topMenuIds as $topMenuId){
            $secondaryMenu[$topMenuId] = array_filter($menu, function($menu)use($topMenuId){
                return $topMenuId == $menu['business_top_menu_id'];
            });
        }
        return empty($secondaryMenu) ? $secondaryMenu : array_map(function($topMenu){
            return array_map(function($secondaryMenu){
                unset($secondaryMenu['business_top_menu_id']);
                return $secondaryMenu;
            }, $topMenu);
        }, $secondaryMenu);
    }
}
