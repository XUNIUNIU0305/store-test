<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 下午2:59
 */

namespace common\models\parts;


use common\ActiveRecord\ExpressCorporationAR;
use common\ActiveRecord\SupplyUserExpressAR;
use common\models\Object;

class SupplyUserExpress extends Object
{
    //最大常用数
    const MAX_NUMBER = 6;

    const TYPE_DEFAULT = 1;
    const TYPE_UNIVERSAL_POSTAL_UNION = 2;

    /**
     * 用户常用总数
     * @param $uid
     * @return int|string
     */
    public static function queryCount($uid)
    {
        return SupplyUserExpressAR::find()
            ->where(['user_id' => $uid])
            ->count();
    }

    /**
     * 用户设置常用列表
     * @param $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserItems($uid)
    {
        $id = SupplyUserExpressAR::find()
            ->select(['express_id'])
            ->where(['user_id' => $uid])
            ->column();

        $models = ExpressCorporationAR::find()
            ->select(['id', 'name'])
            ->where(['id' => $id])
            ->asArray()->all();

        return $models;
    }
}