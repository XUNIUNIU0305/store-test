<?php
namespace business\models\parts;

use Yii;
use yii\base\Object;
use common\components\handler\Handler;

class UndefinedArea extends Object{

    public $id;
    public $display;
    public $can_modify;
    public $parent_business_area_id;
    public $business_user_asleader_id;
    public $business_user_ascommissar_id;

    private static $_customQuantity;

    public function init(){
        $this->id = Area::LEVEL_UNDEFINED;
        $this->display = Area::DISPLAY_OFF;
        $this->can_modify = Area::MODIFY_ON;
        $this->parent_business_area_id = Area::LEVEL_UNDEFINED;
        $this->business_user_asleader_id = Area::EMPTY_USER;
        $this->business_user_ascommissar_id = Area::EMPTY_USER;
    }

    public function getName(){
        return Area::UNDEFINED_AREA_NAME;
    }

    public function getLevel(){
        return Area::LEVEL_UNDEFINED;
    }

    public function getCustom_Quantity(){
        if(is_null(self::$_customQuantity)){
            $area = new Area(['id' => Area::LEVEL_UNDEFINED]);
            $topAreas = $area->children;
            self::$_customQuantity = array_sum(array_map(function($area){
                return $area->customQuantity;
            }, $topAreas));
        }
        return self::$_customQuantity;
    }
}
