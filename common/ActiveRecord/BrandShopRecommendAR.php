<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 31/05/18
 * Time: 11:01
 */

namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BrandShopRecommendAR extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%brand_shop_recommend}}";
    }
}