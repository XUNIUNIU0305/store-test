<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayShortUrl extends WxPayDataBase{

	/**
	* 设置微信分配的公众账号ID
	**/
	public function SetAppId($value){
		$this->values['appid'] = $value;
	}

	/**
	* 获取微信分配的公众账号ID的值
	**/
	public function GetAppId(){
		return $this->values['appid'];
	}

	/**
	* 判断微信分配的公众账号ID是否存在
	**/
	public function IsAppIdSet(){
		return array_key_exists('appid', $this->values);
	}

	/**
	* 设置微信支付分配的商户号
	**/
	public function SetMchId($value){
		$this->values['mch_id'] = $value;
	}

	/**
	* 获取微信支付分配的商户号的值
	**/
	public function GetMchId(){
		return $this->values['mch_id'];
	}

	/**
	* 判断微信支付分配的商户号是否存在
	**/
	public function IsMchIdSet(){
		return array_key_exists('mch_id', $this->values);
	}

	/**
	* 设置需要转换的URL，签名用原串，传输需URL encode
	**/
	public function SetLongUrl($value){
		$this->values['long_url'] = $value;
	}

	/**
	* 获取需要转换的URL，签名用原串，传输需URL encode的值
	**/
	public function GetLongUrl(){
		return $this->values['long_url'];
	}

	/**
	* 判断需要转换的URL，签名用原串，传输需URL encode是否存在
	**/
	public function IsLongUrlSet(){
		return array_key_exists('long_url', $this->values);
	}

	/**
	* 设置随机字符串，不长于32位。推荐随机数生成算法
	**/
	public function SetNonceStr($value){
		$this->values['nonce_str'] = $value;
	}

	/**
	* 获取随机字符串，不长于32位。推荐随机数生成算法的值
	**/
	public function GetNonceStr(){
		return $this->values['nonce_str'];
	}

	/**
	* 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
	**/
	public function IsNonceStrSet(){
		return array_key_exists('nonce_str', $this->values);
	}
}
