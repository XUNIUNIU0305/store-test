<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 16:12
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class MobileAdvertiseAR extends ActiveRecord
{


    public static  function tableName()
    {
        return '{{%mobile_advertise}}';
    }

}