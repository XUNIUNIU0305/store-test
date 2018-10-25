<?php
/**
 * Created by PhpStorm.
 * User: wangli
 * Date: 2017/7/26 0026
 * Time: 15:02
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class MembraneProductTypeAR
 * @package common\ActiveRecord
 * @property int $id
 * @property string $name
 */
class MembraneTypeAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_type}}';
    }
}