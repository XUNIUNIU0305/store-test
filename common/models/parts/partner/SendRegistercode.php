<?php
namespace common\models\parts\partner;

use Yii;
use admin\models\parts\sms\SmsCaptcha;
use common\models\parts\sms\SmsSender;
use common\components\amqp\AmqpTaskAbstract;

class SendRegistercode extends AmqpTaskAbstract{

    public $mobile;
    public $registerCode;

    public function run(){
        sleep(300);
        $smsSender = new SmsSender;
        $sms = new SmsCaptcha([
            'mobile' => $this->mobile,
            'signName' => '九大爷平台',
            'templateCode' => 'SMS_78540086',
            'param' => [
                'captcha' => $this->registerCode,
            ],
        ]);
        $smsSender->send($sms, false);
        return true;
    }
}
