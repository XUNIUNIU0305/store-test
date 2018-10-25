<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 下午3:39
 */

namespace common\models\parts\homepage;


use common\ActiveRecord\HomepageColumnAR;
use common\models\Object;

class Column extends Object
{
    /**
     * @param $id
     * @return static
     */
    public static function getInstanceById($id)
    {
        if($ins = HomepageColumnAR::findOne($id)){
            return new static([
                'ar' => $ins
            ]);
        }
        throw new \RuntimeException;
    }

    public function updateName($name)
    {
        $this->AR->name = $name;
        $this->AR->update(false);
    }
}