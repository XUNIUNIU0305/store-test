<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/27 0027
 * Time: 15:23
 */

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

/**
 * Class MembraneOrderItemAR
 * @package common\ActiveRecord
 * @property int $id
 * @property int $membrane_order_id
 * @property int $membrane_product_id
 * @property int $membrane_product_params_id
 * @property string $name
 * @property float $price
 * @property string $image
 * @property string $remark
 */
class MembraneOrderItemAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_order_item}}';
    }
}