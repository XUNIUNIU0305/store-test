<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayJsApiPay extends WxPayDataBase{

	/**
	* 设置微信分配的公众账号ID
	**/
	public function setAppId($value){
		$this->values['appId'] = $value;
	}

	/**
	* 获取微信分配的公众账号ID的值
	**/
	public function getAppId(){
		return $this->values['appId'];
	}

	/**
	* 判断微信分配的公众账号ID是否存在
	**/
	public function isAppIdSet(){
		return array_key_exists('appId', $this->values);
	}

	/**
	* 设置支付时间戳
	**/
	public function setTimeStamp($value){
		$this->values['timeStamp'] = $value;
	}

	/**
	* 获取支付时间戳的值
	**/
	public function getTimeStamp(){
		return $this->values['timeStamp'];
	}

	/**
	* 判断支付时间戳是否存在
	**/
	public function isTimeStampSet(){
		return array_key_exists('timeStamp', $this->values);
	}
	
	/**
	* 随机字符串
	**/
	public function setNonceStr($value){
		$this->values['nonceStr'] = $value;
	}

	/**
	* 获取notify随机字符串值
	**/
	public function getNonceStr(){
		return $this->values['nonceStr'];
	}

	/**
	* 判断随机字符串是否存在
	**/
	public function isNonceStrSet(){
		return array_key_exists('nonceStr', $this->values);
	}

	/**
	* 设置订单详情扩展字符串
	**/
	public function setPackage($value){
		$this->values['package'] = $value;
	}

	/**
	* 获取订单详情扩展字符串的值
	**/
	public function getPackage(){
		return $this->values['package'];
	}

	/**
	* 判断订单详情扩展字符串是否存在
	**/
	public function isPackageSet(){
		return array_key_exists('package', $this->values);
	}
	
	/**
	* 设置签名方式
	**/
	public function setSignType($value){
		$this->values['signType'] = $value;
	}

	/**
	* 获取签名方式
	**/
	public function getSignType(){
		return $this->values['signType'];
	}

	/**
	* 判断签名方式是否存在
	**/
	public function isSignTypeSet(){
		return array_key_exists('signType', $this->values);
	}
	
	/**
	* 设置签名方式
	**/
	public function setPaySign($value){
		$this->values['paySign'] = $value;
	}

	/**
	* 获取签名方式
	**/
	public function getPaySign(){
		return $this->values['paySign'];
	}

	/**
	* 判断签名方式是否存在
	**/
	public function isPaySignSet(){
		return array_key_exists('paySign', $this->values);
	}
}
