<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 15:21
 */

namespace common\components\handler;


use common\ActiveRecord\MembraneProductAR;
use common\models\parts\MembraneProduct;

class MembraneProductHandler extends Handler
{
    public static function findAll()
    {
        $res = MembraneProductAR::find()->all();
        return array_map(function($p){
            return new MembraneProduct(['AR' => $p]);
        }, $res);
    }
}
