<?php
/**
 * Created by PhpStorm.
 * User: wangli
 * Date: 2017/7/26 0026
 * Time: 15:49
 */

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

/**
 * Class MembraneProductBlockAR
 * @package common\ActiveRecord
 * @property int $id
 * @property string $name
 */
class MembraneBlockAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_block}}';
    }
}