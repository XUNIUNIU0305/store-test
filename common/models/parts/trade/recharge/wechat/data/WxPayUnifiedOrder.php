<?php
namespace common\models\parts\trade\recharge\wechat\data;

use Yii;

class WxPayUnifiedOrder extends WxPayDataBase{

    /**
     * 设置微信分配的公众账号ID
     */
    public function setAppid($value){
        $this->values['appid'] = $value;
    }

    /**
     * 获取微信分配的公众账号ID
     */
    public function getAppid(){
        return $this->values['appid'];
    }

    /**
     * 判断微信分配的公众账号ID是否存在
     */
    public function isAppidSet(){
        return array_key_exists('appid', $this->values);
    }

    /**
     * 设置微信支付分配的商户号
     */
    public function setMchid($mchid){
        $this->values['mch_id'] = $mchid;
    }

    /**
     * 获取微信支付分配的商户号
     */
    public function getMchid(){
        return $this->values['mch_id'];
    }

    /**
     * 判断微信支付分配的商户号是否存在
     */
    public function isMchidSet(){
        return array_key_exists('mch_id', $this->values);
    }

    /**
     * 设置微信支付分配的终端设备号，商户自定义
     */
    public function setDeviceInfo($value){
        $this->values['device_info'] = $value;
    }

    /**
     * 获取微信支付分配的终端设备号，商户自定义
     */
    public function getDeviceInfo(){
        return $this->values['device_info'];
    }

    /**
     * 判断微信支付分配的终端设备号是否存在
     */
    public function isDeviceInfoSet(){
        return array_key_exists('device_info', $this->values);
    }

    /**
     * 设置随机字符串，不长于32位
     */
    public function setNonceStr(string $value){
        if(strlen($value) > 32)throw new \Exception('string length must less than 32');
        $this->values['nonce_str'] = $value;
    }

    /**
     * 获取随机字符串
     */
    public function getNonceStr(){
        return $this->values['nonce_str'];
    }

    /**
     * 判断随机字符串是否存在
     */
    public function isNonceStrSet(){
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置商品或支付单简述
     */
    public function setBody($value){
        $this->values['body'] = $value;
    }

    /**
     * 获取商品或支付单简述
     */
    public function getBody(){
        return $this->values['body'];
    }

    /**
     * 判断商品或支付单简述是否存在
     */
    public function isBodySet(){
        return array_key_exists('body', $this->values);
    }

    /**
     * 设置商品名称明细列表
     */
    public function setDetail($value){
        $this->values['detail'] = $value;
    }

    /**
     * 获取商品名称明细列表
     */
    public function getDetail(){
        return $this->values['detail'];
    }

    /**
     * 判断商品名称明细列表是否存在
     */
    public function isDetailSet(){
        return array_key_exists('detail', $this->values);
    }

    /**
     * 设置附件数据，在查询API和支付通知中原样返回
     */
    public function setAttach($value){
        $this->values['attach'] = $value;
    }

    /**
     * 获取附件数据
     */
    public function getAttach(){
        return $this->values['attach'];
    }

    /**
     * 判断附加数据是否存在
     */
    public function isAttachSet(){
        return array_key_exists('attach', $this->values);
    }

    public function setOutTradeNo(string $value){
        if(strlen($value) > 32)throw new \Exception('string length must less than 32');
        $this->values['out_trade_no'] = $value;
    }

    public function getOutTradeNo(){
        return $this->values['out_trade_no'];
    }

    public function isOutTradeNoSet(){
        return array_key_exists('out_trade_no', $this->values);
    }

    /**
     * 设置符合ISO 4217标准的货币类型，三位字母代码；默认人民币：CNY
     */
    public function setFeeType($value){
        $this->values['fee_type'] = $value;
    }

    /**
     * 获取货币类型
     */
    public function getFeeType(){
        return $this->values['fee_type'];
    }

    /**
     * 判断货币类型是否存在
     */
    public function isFeeTypeSet(){
        return array_key_exists('fee_type', $this->values);
    }

    /**
     * 设置订单总金额，只能为整数
     */
    public function setTotalFee($value){
        $this->values['total_fee'] = $value;
    }

    /**
     * 获取订单总金额
     */
    public function getTotalFee(){
        return $this->values['total_fee'];
    }

    /**
     * 判断订单总金额是否存在
     */
    public function isTotalFeeSet(){
        return array_key_exists('total_fee', $this->values);
    }

    /**
     * 设置APP和网页支付提交用户端IP
     */
    public function setSpbillCreateIp($value){
        $this->values['spbill_create_ip'] = $value;
    }

    /**
     * 获取APP和网页支付提交用户端IP
     */
    public function getSpbillCreateIp(){
        return $this->values['spbill_create_ip'];
    }

    /**
     * 判断APP和网页支付提交用户端IP是否存在
     */
    public function isSpbillCreateIpSet(){
        return array_key_exists('spbill_create_ip', $this->values);
    }

    /**
     * 设置订单生成时间，格式：yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010
     */
    public function setTimeStart($value){
        $this->values['time_start'] = $value;
    }

    /**
     * 获取订单生成时间
     */
    public function getTimeStart(){
        return $this->values['time_start'];
    }

    /**
     * 判断订单生成时间是否存在
     */
    public function isTimeStartSet(){
        return array_key_exists('time_start', $this->values);
    }

    /**
     * 设置订单失效时间，格式：yyyyMMddHHmmss
     */
    public function setTimeExpire($value){
        $this->values['time_expire'] = $value;
    }

    /**
     * 获取订单失效时间
     */
    public function getTimeExpire(){
        return $this->values['time_expire'];
    }

    /**
     * 判断订单失效时间是否存在
     */
    public function isTimeExpireSet(){
        return array_key_exists('time_expire', $this->values);
    }

    /**
     * 设置商品标记，代金券或立减优惠功能的参数
     */
    public function setGoodsTag($value){
        $this->values['goods_tag'] = $value;
    }

    /**
     * 获取商品标记
     */
    public function getGoodsTag(){
        return $this->values['goods_tag'];
    }

    /**
     * 判断商品标记是否存在
     */
    public function isGoodsTagSet(){
        return array_key_exists('goods_tag', $this->values);
    }

    /**
     * 设置接收微信支付异步通知回调地址
     */
    public function setNotifyUrl($value){
        $this->values['notify_url'] = $value;
    }

    /**
     * 获取接收微信支付异步通知回调地址
     */
    public function getNotifyUrl(){
        return $this->values['notify_url'];
    }

    /**
     * 判断接收微信支付异步通知回调地址是否存在
     */
    public function isNotifyUrlSet(){
        return array_key_exists('notify_url', $this->values);
    }

    /**
     * 设置交易类型，取值：JSAPI、NATIVE、APP
     */
    public function setTradeType($value){
        $this->values['trade_type'] = $value;
    }

    /**
     * 获取交易类型
     */
    public function getTradeType(){
        return $this->values['trade_type'];
    }

    /**
     * 判断交易类型是否存在
     */
    public function isTradeTypeSet(){
        return array_key_exists('trade_type', $this->values);
    }

    /**
     * 设置商品ID；当交易类型为NATIVE时此参数必传；此ID为二维码中包含的商品ID
     */
    public function setProductId($value){
        $this->values['product_id'] = $value;
    }

    /**
     * 获取商品ID
     */
    public function getProductId(){
        return $this->values['product_id'];
    }

    /**
     * 判断商品ID是否存在
     */
    public function isProductIdSet(){
        return array_key_exists('product_id', $this->values);
    }

    /**
     * 设置OPENID，用户在商户appid下的唯一标示；当交易类型为JSAPI时此参数必传；下单前需要调用【网页授权获取用户信息】接口获取用户的OPENID
     */
    public function setOpenId($value){
        $this->values['openid'] = $value;
    }

    /**
     * 获取OPENID
     */
    public function getOpenId(){
        return $this->values['openid'];
    }

    /**
     * 判断OPENID是否存在
     */
    public function isOpenIdSet(){
        return array_key_exists('openid', $this->values);
    }
}
