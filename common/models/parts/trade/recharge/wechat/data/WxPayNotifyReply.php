<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayNotifyReply extends WxPayDataBase{

    /**
     * 设置错误码
     * FAIL 或 SUCCESS
     */
    public function setReturnCode($returnCode){
        $this->values['return_code'] = $returnCode;
    }

    /**
     * 获取错误码
     * FAIL 或 SUCCESS
     */
    public function getReturnCode(){
        return $this->values['return_code'];
    }

    /**
     * 设置错误信息
     */
    public function setReturnMsg($returnMsg){
        $this->values['return_msg'] = $returnMsg;
    }

    /**
     * 获取错误信息
     */
    public function getReturnMsg(){
        return $this->values['return_msg'];
    }

    /**
     * 设置返回参数
     */
    public function setData($key, $value){
        $this->values[$key] = $value;
    }
}
