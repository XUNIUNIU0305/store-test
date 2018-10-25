<?php
namespace common\components\handler\oss_image_action;

trait ValidatorTrait{

    protected function validateColor(string $color){
        return preg_match('/^[0-9A-F]{6}$/', $color) ? true : false;
    }
}
