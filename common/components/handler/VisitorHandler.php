<?php
namespace common\components\handler;

use Yii;

class VisitorHandler extends Handler{

    public static function collectRequestHeader(){
        $allHeaders = Yii::$app->request->headers;
        $handledHeaders = [];
        foreach($allHeaders as $headerName => $headerValueArray){
            $handledHeaders[$headerName] = implode(' | ', $headerValueArray);
        }
        return $handledHeaders;
    }

    public static function collectUserIp(){
        $ip = Yii::$app->request->userIP;
        if(is_null($ip)){
            return 0;
        }else{
            return static::IpStrToLong($ip);
        }
    }
}
