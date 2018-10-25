<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayCloseOrder extends WxPayDataBase{

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
	* 设置商户系统内部的订单号
	**/
	public function SetOutTradeNo($value){
		$this->values['out_trade_no'] = $value;
	}

	/**
	* 获取商户系统内部的订单号的值
	**/
	public function GetOutTradeNo(){
		return $this->values['out_trade_no'];
	}

	/**
	* 判断商户系统内部的订单号是否存在
	**/
	public function IsOutTradeNoSet(){
		return array_key_exists('out_trade_no', $this->values);
	}

	/**
	* 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
	**/
	public function SetNonceStr($value){
		$this->values['nonce_str'] = $value;
	}

	/**
	* 获取商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号的值
	**/
	public function GetNonceStr(){
		return $this->values['nonce_str'];
	}

	/**
	* 判断商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号是否存在
	**/
	public function IsNonceStrSet(){
		return array_key_exists('nonce_str', $this->values);
	}
}
