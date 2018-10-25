<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/22
 * Time: 上午10:05
 */

namespace admin\models\parts;

use common\ActiveRecord\ProductSPUOptionAR;
use Yii;
use yii\base\Object;

class ProductSpuOption extends Object
{
    public $id;
    private $AR;

    public function init()
    {

        $exist = Yii::$app->RQ->AR(new ProductSPUOptionAR())->exists([
            'where' => "id = $this->id ",
            'limit' => 1,
        ]);

        if ($this->id && $exist)
        {
            $this->AR = Yii::$app->RQ->AR(ProductSPUOptionAR::findOne(['id' => $this->id]));
        }
        else
        {
            return false;
        }
    }


    //设置display
    public function setDisPlay($display)
    {
        if(!in_array($display, [ProductSPUOptionAR::DISPLAY, ProductSPUOptionAR::HIDE]))return false;
        return $this->AR->update([
            'display' => $display,
        ]);
    }
}
