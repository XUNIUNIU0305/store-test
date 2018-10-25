<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-16
 * Time: 下午3:27
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class MembraneProductParamsAR
 * @package common\ActiveRecord
 * @property $id int
 */
class MembraneProductParamsAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_product_params}}';
    }
}