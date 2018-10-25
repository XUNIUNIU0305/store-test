<?php
/**
 * 产品型号与档位关联
 * User: wangli
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;


class MembraneTypeBlockAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%membrane_type_block}}';
    }
}