<?php
/**
 * User: wangli
 */

namespace common\ActiveRecord;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class MembraneProductAR
 * @package common\ActiveRecord
 * @property int $id
 * @property string $name
 * @property float $price
 * @property array $image
 * @property string $remark
 */
class MembraneProductAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_product}}';
    }
}