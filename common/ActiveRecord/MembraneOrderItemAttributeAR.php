<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/27 0027
 * Time: 15:25
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class MembraneOrderItemAttribute
 * @package common\ActiveRecord
 * @property int $id
 * @property int $membrane_order_item_id
 * @property int $membrane_item_block_id
 * @property string $membrane_item_block
 * @property string $membrane_item_type
 */
class MembraneOrderItemAttributeAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_order_item_attribute}}';
    }
}