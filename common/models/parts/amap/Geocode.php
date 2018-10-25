<?php
namespace common\models\parts\amap;

use Yii;

class Geocode extends AmapAbstract{

    protected function requiredParams() : array{
        return [
            'address' => true,
            'city' => false,
            'batch' => false,
        ];
    }

    protected function action() : string{
        return static::ACTION_GEOCODE;
    }
}
