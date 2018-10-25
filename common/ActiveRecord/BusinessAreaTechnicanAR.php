<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午4:42
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class BusinessAreaTechnicanAR extends ActiveRecord
{

    const YES_DEL = 1;  //删除
    const NO_DEL = 0;   //正常
    public static function tableName(){
        return '{{%business_area_technican}}';
    }
}