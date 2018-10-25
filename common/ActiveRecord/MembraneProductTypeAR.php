<?php
/**
 * 产品与型号关联
 * User: wangli
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class MembraneProductTypeAR
 * @package common\ActiveRecord
 * @property int $id
 * @property int $membrane_product_id
 * @property int $membrane_type_id
 */
class MembraneProductTypeAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_product_type}}';
    }
}