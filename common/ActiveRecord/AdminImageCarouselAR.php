<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午4:42
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class AdminImageCarouselAR extends ActiveRecord
{

    public static function tableName(){
        return '{{%admin_image_carousel}}';
    }
}