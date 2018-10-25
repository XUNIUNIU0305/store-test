<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayResults extends WxPayDataBase{

    /**
     * 检测签名
     */
    public function checkSign(){
        if(!$this->isSignSet()){
            throw new \Exception('sign error');
        }
        $sign = $this->makeSign();
        if($this->getSign() == $sign){
            return true;
        }else{
            throw new \Exception('sign error');
        }
    }

    /**
     * 使用数组初始化
     */
    public function fromArray(array $array){
        $this->values = $array;
    }

    /**
     * 使用数组初始化对象
     */
    public static function initFromArray(array $array, $checkSign = true){
        $obj = new self();
        $obj->fromArray($array);
        if($checkSign){
            $obj->checkSign();
        }
        return $obj;
    }

    /**
     * 设置参数
     */
    public function setData($key, $value){
        $this->values[$key] = $value;
    }

    /**
     * 将XML转换为Array
     */
    public static function initialize($xml){
        $obj = new self();
        $obj->fromXml($xml);
        if($obj->values['return_code'] != 'SUCCESS'){
            return $obj->getValues();
        }
        $obj->checkSign();
        return $obj->getValues();
    }
}
