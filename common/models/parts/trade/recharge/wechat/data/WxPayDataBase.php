<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;
use yii\base\Object;

class WxPayDataBase extends Object{

    protected $values = [];

    /**
     * 设置签名
     */
    public function setSign(){
        $sign = $this->makeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }

    /**
     * 生成签名
     * 签名步骤：
     * 1、按字典序排序参数
     * 2、在string后加入KEY
     * 3、MD5加密
     * 4、所有字符转为大写
     */
    public function makeSign(){
        ksort($this->values);
        $string = $this->toUrlParams();
        $string = $string . '&key=' . Yii::$app->params['WECHAT_Key'];
        $string = md5($string);
        $sign = strtoupper($string);
        return $sign;
    }

    /**
     * 格式化参数为URL参数
     */
    public function toUrlParams(){
        $params = [];
        foreach($this->values as $k => $v){
            if($k != 'sign' && $v != '' && !is_array($v)){
                $params[] = $k . '=' . $v;
            }
        }
        $urlParams = implode('&', $params);
        return $urlParams;
    }

    /**
     * 获取签名
     */
    public function getSign(){
        return $this->values['sign'] ?? false;
    }

    /**
     * 判断签名是否已设置
     */
    public function isSignSet(){
        return array_key_exists('sign', $this->values);
    }

    /**
     * 输出XML字符
     */
    public function toXml(){
        if(!is_array($this->values) || count($this->values) <= 0){
            throw new \Exception('data error');
        }
        $xml = '<xml>';
        foreach($this->values as $k => $v){
            if(is_numeric($v)){
                $xml .= "<{$k}>{$v}</{$k}>";
            }else{
                $xml .= "<{$k}><![CDATA[{$v}]]></{$k}>";
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 将XML转换为Array
     */
    public function fromXml($xml){
        if(!$xml){
            throw new \Exception;
        }
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     * 获取设置的值
     */
    public function getValues(){
        return $this->values;
    }
}
