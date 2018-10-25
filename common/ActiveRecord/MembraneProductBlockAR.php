<?php
/**
 * 产品与档位关联
 * User: wangli
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class MembraneProductBlockAR
 * @package common\ActiveRecord
 * @property int $id;
 * @property int $membrane_product_id
 * @property int $membrane_block_id
 */
class MembraneProductBlockAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_product_block}}';
    }
}