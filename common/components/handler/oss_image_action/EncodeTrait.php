<?php
namespace common\components\handler\oss_image_action;

trait EncodeTrait{

    protected function urlSafeBase64Encode(string $string){
        if(empty($string)){
            return false;
        }else{
            return str_replace(['+', '/'], ['-', '_'], base64_encode($string));
        }
    }

}

