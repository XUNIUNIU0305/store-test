<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//获取短信验证码
class NanjingCaptcha extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => true,
            'MsgType' => true,
            'MobilePhone' => false,
            'TransAmount' => false,
            'FeeAmount' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_CAPTCHA;
    }
}
