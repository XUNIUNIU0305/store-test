<?php
namespace common\models\parts\amap;

use Yii;

class Distance extends AmapAbstract{

    protected function requiredParams() : array{
        return [
            'origins' => true,
            'destination' => true,
            'type' => '0',
        ];
    }

    protected function action() : string{
        return static::ACTION_DISTANCE;
    }
}
