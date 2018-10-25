<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayBizPayUrl extends WxPayDataBase{

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
	* 设置支付时间戳
	**/
	public function SetTimeStamp($value){
		$this->values['time_stamp'] = $value;
	}

	/**
	* 获取支付时间戳的值
	**/
	public function GetTimeStamp(){
		return $this->values['time_stamp'];
	}

	/**
	* 判断支付时间戳是否存在
	**/
	public function IsTimeStampSet(){
		return array_key_exists('time_stamp', $this->values);
	}
	
	/**
	* 设置随机字符串
	**/
	public function SetNonceStr($value){
		$this->values['nonce_str'] = $value;
	}

	/**
	* 获取随机字符串的值
	**/
	public function GetNonceStr(){
		return $this->values['nonce_str'];
	}

	/**
	* 判断随机字符串是否存在
	**/
	public function IsNonceStrSet(){
		return array_key_exists('nonce_str', $this->values);
	}
	
	/**
	* 设置商品ID
	**/
	public function SetProductId($value){
		$this->values['product_id'] = $value;
	}

	/**
	* 获取商品ID的值
	**/
	public function GetProductId(){
		return $this->values['product_id'];
	}

	/**
	* 判断商品ID是否存在
	**/
	public function IsProductIdSet(){
		return array_key_exists('product_id', $this->values);
	}
}
