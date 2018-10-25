<?php
namespace common\models\parts\trade\recharge\wechat;

use Yii;
use yii\base\Object;
use common\models\parts\trade\recharge\wechat\data\WxPayNotifyReply;

class WxPayNotify extends WxPayNotifyReply{

    final public function handle($needSign = true){
        $msg = 'OK';
        $result = WxPayApi::notify([$this, 'notifyCallBack'], $msg);
        if($result == false){
            $this->setReturnCode('FAIL');
            $this->setReturnMsg($msg);
            $this->replyNotify(false);
            return;
        }else{
            $this->setReturnCode('SUCCESS');
            $this->setReturnMsg('OK');
        }
        $this->replyNotify($needSign);
    }

    public function notifyProcess($data, &$msg){
        return true;
    }

    final private function replyNotify($needSign = true){
        if($needSign == true && $this->getReturnCode() == 'SUCCESS'){
            $this->setSign();
        }
        WxPayApi::replyNotify($this->toXml());
    }
}
