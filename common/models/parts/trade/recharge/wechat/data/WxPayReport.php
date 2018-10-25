<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayReport extends WxPayDataBase{

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
	* 设置微信支付分配的终端设备号，商户自定义
	**/
	public function SetDeviceInfo($value){
		$this->values['device_info'] = $value;
	}

	/**
	* 获取微信支付分配的终端设备号，商户自定义的值
	**/
	public function GetDeviceInfo(){
		return $this->values['device_info'];
	}

	/**
	* 判断微信支付分配的终端设备号，商户自定义是否存在
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
	* 设置上报对应的接口的完整URL，类似：https://api.mch.weixin.qq.com/pay/unifiedorder对于被扫支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次被扫行为进行一次单独的整体上报，上报URL指定为：https://api.mch.weixin.qq.com/pay/micropay/total关于两种接入模式具体可参考本文档章节：被扫支付商户接入模式其它接口调用仍然按照调用一次，上报一次来进行。
	**/
	public function SetInterfaceUrl($value){
		$this->values['interface_url'] = $value;
	}

	/**
	* 获取上报对应的接口的完整URL，类似：https://api.mch.weixin.qq.com/pay/unifiedorder对于被扫支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次被扫行为进行一次单独的整体上报，上报URL指定为：https://api.mch.weixin.qq.com/pay/micropay/total关于两种接入模式具体可参考本文档章节：被扫支付商户接入模式其它接口调用仍然按照调用一次，上报一次来进行。的值
	**/
	public function GetInterfaceUrl(){
		return $this->values['interface_url'];
	}

	/**
	* 判断上报对应的接口的完整URL，类似：https://api.mch.weixin.qq.com/pay/unifiedorder对于被扫支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次被扫行为进行一次单独的整体上报，上报URL指定为：https://api.mch.weixin.qq.com/pay/micropay/total关于两种接入模式具体可参考本文档章节：被扫支付商户接入模式其它接口调用仍然按照调用一次，上报一次来进行。是否存在
	**/
	public function IsInterfaceUrlSet(){
		return array_key_exists('interface_url', $this->values);
	}

	/**
	* 设置接口耗时情况，单位为毫秒
	**/
	public function SetExecuteTime($value){
		$this->values['execute_time_'] = $value;
	}

	/**
	* 获取接口耗时情况，单位为毫秒的值
	**/
	public function GetExecuteTime(){
		return $this->values['execute_time_'];
	}

	/**
	* 判断接口耗时情况，单位为毫秒是否存在
	**/
	public function IsExecuteTimeSet(){
		return array_key_exists('execute_time_', $this->values);
	}

	/**
	* 设置SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断
	**/
	public function SetReturnCode($value){
		$this->values['return_code'] = $value;
	}

	/**
	* 获取SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断的值
	**/
	public function GetReturnCode(){
		return $this->values['return_code'];
	}

	/**
	* 判断SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断是否存在
	**/
	public function IsReturnCodeSet(){
		return array_key_exists('return_code', $this->values);
	}

	/**
	* 设置返回信息，如非空，为错误原因签名失败参数格式校验错误
	**/
	public function SetReturnMsg($value){
		$this->values['return_msg'] = $value;
	}

	/**
	* 获取返回信息，如非空，为错误原因签名失败参数格式校验错误的值
	**/
	public function GetReturnMsg(){
		return $this->values['return_msg'];
	}

	/**
	* 判断返回信息，如非空，为错误原因签名失败参数格式校验错误是否存在
	**/
	public function IsReturnMsgSet(){
		return array_key_exists('return_msg', $this->values);
	}

	/**
	* 设置SUCCESS/FAIL
	**/
	public function SetResultCode($value){
		$this->values['result_code'] = $value;
	}

	/**
	* 获取SUCCESS/FAIL的值
	**/
	public function GetResultCode(){
		return $this->values['result_code'];
	}
    
	/**
	* 判断SUCCESS/FAIL是否存在
	**/
	public function IsResultCodeSet(){
		return array_key_exists('result_code', $this->values);
	}

	/**
	* 设置ORDERNOTEXIST—订单不存在SYSTEMERROR—系统错误
	**/
	public function SetErrCode($value){
		$this->values['err_code'] = $value;
	}

	/**
	* 获取ORDERNOTEXIST—订单不存在SYSTEMERROR—系统错误的值
	**/
	public function GetErrCode(){
		return $this->values['err_code'];
	}

	/**
	* 判断ORDERNOTEXIST—订单不存在SYSTEMERROR—系统错误是否存在
	**/
	public function IsErrCodeSet(){
		return array_key_exists('err_code', $this->values);
	}

	/**
	* 设置结果信息描述
	**/
	public function SetErrCodeDes($value){
		$this->values['err_code_des'] = $value;
	}

	/**
	* 获取结果信息描述的值
	**/
	public function GetErrCodeDes(){
		return $this->values['err_code_des'];
	}

	/**
	* 判断结果信息描述是否存在
	**/
	public function IsErrCodeDesSet(){
		return array_key_exists('err_code_des', $this->values);
	}

	/**
	* 设置商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。 
	**/
	public function SetOutTradeNo($value){
		$this->values['out_trade_no'] = $value;
	}

	/**
	* 获取商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。 的值
	**/
	public function GetOutTradeNo(){
		return $this->values['out_trade_no'];
	}

	/**
	* 判断商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。 是否存在
	**/
	public function IsOutTradeNoSet(){
		return array_key_exists('out_trade_no', $this->values);
	}

	/**
	* 设置发起接口调用时的机器IP 
	**/
	public function SetUserIp($value){
		$this->values['user_ip'] = $value;
	}

	/**
	* 获取发起接口调用时的机器IP 的值
	**/
	public function GetUserIp(){
		return $this->values['user_ip'];
	}

	/**
	* 判断发起接口调用时的机器IP 是否存在
	**/
	public function IsUserIpSet(){
		return array_key_exists('user_ip', $this->values);
	}

	/**
	* 设置系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则
	**/
	public function SetTime($value){
		$this->values['time'] = $value;
	}

	/**
	* 获取系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则的值
	**/
	public function GetTime(){
		return $this->values['time'];
	}

	/**
	* 判断系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则是否存在
	**/
	public function IsTimeSet(){
		return array_key_exists('time', $this->values);
	}
}
