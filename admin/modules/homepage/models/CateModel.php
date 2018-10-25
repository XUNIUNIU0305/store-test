<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-9
 * Time: 下午3:43
 */

namespace admin\modules\homepage\models;


use common\ActiveRecord\ProductCategoryAR;
use common\models\Model;
use common\models\parts\ProductCategory;

class CateModel extends Model
{
    const SCE_GET_LIST = 'get_list';

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => []
        ];
    }

    public function rules()
    {
        return [];
    }

    public function getList()
    {
        $items = ProductCategoryAR::find()
            ->select(['id', 'parent_id', 'title', 'is_end'])
            ->where(['display' => ProductCategory::STATUS_DISPLAY])
            ->asArray()->all();

        return static::getSub($items);
    }

    public static function getSub($items, $id = 0, $level = 0)
    {
        $res = [];
        $level++;
        foreach ($items as $key => $item) {
            if ($item['parent_id'] == $id) {
                unset($items[$key]);
                $item['level'] = $level;
                if (!$item['is_end']) {
                    $item['children'] = static::getSub($items, $item['id'], $level);
                }
                $res[] = $item;
            }
        }
        return $res;
    }
}