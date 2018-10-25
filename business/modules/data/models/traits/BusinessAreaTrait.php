<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-29
 * Time: 下午3:32
 */

namespace business\modules\data\models\traits;


use business\models\handler\BusinessAreaHandler;
use business\models\parts\Area;

trait BusinessAreaTrait
{
    /**
     * 查询有效的五级区域
     * @param $area
     * @return array
     */
    private function getValidAreaItem($area)
    {
        $mainArea = $this->getMainArea();
        if($area->level <= $mainArea->level){
            return BusinessAreaHandler::findAreaByLevel($mainArea, Area::LEVEL_FIFTH);
        }
        if($area->level > $mainArea->level){
            $children = BusinessAreaHandler::findAreaByLevel($mainArea, $area->level);
            foreach ($children as $item){
                if($item['id'] == $area->id){
                    return BusinessAreaHandler::findAreaByLevel($area, Area::LEVEL_FIFTH);
                    break;
                }
            }
            return [];
        }
    }
}