<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\Behavior;
use common\components\handler\OSSImageHandler;

class ActionBehavior extends Behavior{

    const POSITION_NW = 'nw'; //左上
    const POSITION_NORTH = 'north'; //中上
    const POSITION_NE = 'ne'; //右上
    const POSITION_WEST = 'west'; //左中
    const POSITION_CENTER = 'center'; //中中
    const POSITION_EAST = 'east'; //右中
    const POSITION_SW = 'sw'; //左下
    const POSITION_SOUTH = 'south'; //中下
    const POSITION_SE = 'se'; //右下

    public function getImage(){
        return ($this->owner instanceof OSSImageHandler) ? $this->owner->image : null;
    }

    protected function validatePosition($position){
        return in_array($position, [
            self::POSITION_NW,
            self::POSITION_NORTH,
            self::POSITION_NE,
            self::POSITION_WEST,
            self::POSITION_CENTER,
            self::POSITION_EAST,
            self::POSITION_SW,
            self::POSITION_SOUTH,
            self::POSITION_SE,
        ]);
    }
}
