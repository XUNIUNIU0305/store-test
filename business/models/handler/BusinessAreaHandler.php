<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 下午3:35
 */

namespace business\models\handler;


use business\models\parts\Area;
use business\modules\data\models\BusinessArea;
use common\ActiveRecord\BusinessAreaAR;

class BusinessAreaHandler
{
    /**
     * @param $id
     * @param $level
     * @param $res
     */
    public static function findChildArea($id, $level, &$res)
    {
        $items = static::queryAllArea();
        foreach ($items as $item){
            if($item['parent_business_area_id'] == $id){
                if($item['level'] < $level){
                    static::findChildArea($item['id'], $level, $res);
                } elseif(intval($item['level']) == $level){
                    $res[] = [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'level' => $item['level']
                    ];
                }
            }
        }
    }

    /**
     * 查找制定等级区域
     * @param $area
     * @param $level
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAreaByLevel($area, $level)
    {
        $res = [];
        if($area['level'] < $level){
            static::findChildArea($area['id'], $level, $res);
        } elseif ($area['level'] == $level){
            $res[] = [
                'id' => $area['id'],
                'name' => $area['name'],
                'level' => $area['level']
            ];
        }
        return $res;
    }

    /**
     * @param $area
     * @param $level
     * @return array
     */
    public static function findParentLevel($area, $level)
    {
        if($area['level'] == $level){
            return [
                [
                    'id' => $area['id'],
                    'name' => $area['name'],
                    'level' => $area['level']
                ]
            ];
        }
        if($area['level'] > $level){
            $items = static::queryAllArea();
            foreach ($items as $item){
                if($item['id'] == $area['parent_business_area_id']){
                    return static::findParentLevel($item, $level);
                }
            }
        }
        return [];
    }

    private static $area = false;

    /**
     * @return array|bool
     */
    public static function queryAllArea()
    {
        if(static::$area === false){
            static::$area = BusinessAreaAR::find()
                ->select(['id', 'name', 'level', 'parent_business_area_id'])
                ->where(['display' => 1])
                ->asArray()->all() ?? [];
        }
        return static::$area;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryAreaByParentId($id)
    {
        return BusinessAreaAR::find()
            ->select(['id', 'name'])
            ->where(['parent_business_area_id' => $id])
            ->asArray()->all() ?? [];
    }
}