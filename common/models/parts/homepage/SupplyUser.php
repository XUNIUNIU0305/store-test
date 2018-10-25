<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-8
 * Time: 下午5:09
 */

namespace common\models\parts\homepage;


use common\ActiveRecord\SupplyUserAR;
use common\models\Object;

class SupplyUser extends Object
{
    public static function queryById($id)
    {
        if($ar = SupplyUserAR::findOne($id)){
            return new static(['ar' => $ar]);
        }
        throw new \RuntimeException('无效ID');
    }

    public static function queryBrandByName($name = null)
    {
        $res = SupplyUserAR::find()
            ->filterWhere(['like', 'brand_name', $name])
            ->limit(20)
            ->all();
        foreach ($res as &$record){
            $record = new static([
                'ar' => $record
            ]);
        }
        return $res;
    }
}