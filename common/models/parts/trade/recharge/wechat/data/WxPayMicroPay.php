<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayMicroPay extends WxPayDataBase{

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
	* 设置终端设备号(商户自定义，如门店编号)
	**/
	public function SetDeviceInfo($value){
		$this->values['device_info'] = $value;
	}

	/**
	* 获取终端设备号(商户自定义，如门店编号)的值
	**/
	public function GetDeviceInfo(){
		return $this->values['device_info'];
	}

	/**
	* 判断终端设备号(商户自定义，如门店编号)是否存在
	**/
	public function IsDeviceInfoSet(){
		return array_key_exists('device_info', $this->values);
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

	/**
	* 设置商品或支付单简要描述
	**/
	public function SetBody($value){
		$this->values['body'] = $value;
	}

	/**
	* 获取商品或支付单简要描述的值
	**/
	public function GetBody(){
		return $this->values['body'];
	}
    
	/**
	* 判断商品或支付单简要描述是否存在
	**/
	public function IsBodySet(){
		return array_key_exists('body', $this->values);
	}

	/**
	* 设置商品名称明细列表
	**/
	public function SetDetail($value){
		$this->values['detail'] = $value;
	}

	/**
	* 获取商品名称明细列表的值
	**/
	public function GetDetail(){
		return $this->values['detail'];
	}

	/**
	* 判断商品名称明细列表是否存在
	**/
	public function IsDetailSet(){
		return array_key_exists('detail', $this->values);
	}

	/**
	* 设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
	**/
	public function SetAttach($value){
		$this->values['attach'] = $value;
	}

	/**
	* 获取附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据的值
	**/
	public function GetAttach(){
		return $this->values['attach'];
	}

	/**
	* 判断附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据是否存在
	**/
	public function IsAttachSet(){
		return array_key_exists('attach', $this->values);
	}

	/**
	* 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
	**/
	public function SetOutTradeNo($value){
		$this->values['out_trade_no'] = $value;
	}

	/**
	* 获取商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号的值
	**/
	public function GetOutTradeNo(){
		return $this->values['out_trade_no'];
	}

	/**
	* 判断商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号是否存在
	**/
	public function IsOutTradeNoSet(){
		return array_key_exists('out_trade_no', $this->values);
	}

	/**
	* 设置订单总金额，单位为分，只能为整数，详见支付金额
	**/
	public function SetTotalFee($value){
		$this->values['total_fee'] = $value;
	}

	/**
	* 获取订单总金额，单位为分，只能为整数，详见支付金额的值
	**/
	public function GetTotalFee(){
		return $this->values['total_fee'];
	}

	/**
	* 判断订单总金额，单位为分，只能为整数，详见支付金额是否存在
	**/
	public function IsTotalFeeSet(){
		return array_key_exists('total_fee', $this->values);
	}

	/**
	* 设置符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
	**/
	public function SetFeeType($value){
		$this->values['fee_type'] = $value;
	}

	/**
	* 获取符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型的值
	**/
	public function GetFeeType(){
		return $this->values['fee_type'];
	}

	/**
	* 判断符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型是否存在
	**/
	public function IsFeeTypeSet(){
		return array_key_exists('fee_type', $this->values);
	}

	/**
	* 设置调用微信支付API的机器IP 
	**/
	public function SetSpbillCreateIp($value){
		$this->values['spbill_create_ip'] = $value;
	}

	/**
	* 获取调用微信支付API的机器IP 的值
	**/
	public function GetSpbillCreateIp(){
		return $this->values['spbill_create_ip'];
	}

	/**
	* 判断调用微信支付API的机器IP 是否存在
	**/
	public function IsSpbillCreateIpSet(){
		return array_key_exists('spbill_create_ip', $this->values);
	}

	/**
	* 设置订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则
	**/
	public function SetTimeStart($value){
		$this->values['time_start'] = $value;
	}

	/**
	* 获取订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则的值
	**/
	public function GetTimeStart(){
		return $this->values['time_start'];
	}

	/**
	* 判断订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则是否存在
	**/
	public function IsTimeStartSet(){
		return array_key_exists('time_start', $this->values);
	}

	/**
	* 设置订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。详见时间规则
	**/
	public function SetTimeExpire($value){
		$this->values['time_expire'] = $value;
	}

	/**
	* 获取订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。详见时间规则的值
	**/
	public function GetTimeExpire(){
		return $this->values['time_expire'];
	}

	/**
	* 判断订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。详见时间规则是否存在
	**/
	public function IsTimeExpireSet(){
		return array_key_exists('time_expire', $this->values);
	}

	/**
	* 设置商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
	**/
	public function SetGoodsTag($value){
		$this->values['goods_tag'] = $value;
	}

	/**
	* 获取商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠的值
	**/
	public function GetGoodsTag(){
		return $this->values['goods_tag'];
	}

	/**
	* 判断商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠是否存在
	**/
	public function IsGoodsTagSet(){
		return array_key_exists('goods_tag', $this->values);
	}

	/**
	* 设置扫码支付授权码，设备读取用户微信中的条码或者二维码信息
	**/
	public function SetAuthCode($value){
		$this->values['auth_code'] = $value;
	}

	/**
	* 获取扫码支付授权码，设备读取用户微信中的条码或者二维码信息的值
	**/
	public function GetAuthCode(){
		return $this->values['auth_code'];
	}

	/**
	* 判断扫码支付授权码，设备读取用户微信中的条码或者二维码信息是否存在
	**/
	public function IsAuthCodeSet(){
		return array_key_exists('auth_code', $this->values);
	}
}
