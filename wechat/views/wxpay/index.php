<?php
$this->title = '微信支付';
?>
<script>
function onBridgeReady(){
   WeixinJSBridge.invoke(
       'getBrandWCPayRequest', {
           "appId":"<?= $params['appid'] ?>",     //公众号名称，由商户传入     
           "timeStamp":"<?= $params['time_stamp'] ?>",         //时间戳，自1970年以来的秒数     
           "nonceStr":"<?= $params['nonce_str'] ?>", //随机串     
           "package":"<?= $params['package'] ?>",     
           "signType":"<?= $params['sign_type'] ?>",         //微信签名方式：     
           "paySign":"<?= $params['pay_sign'] ?>" //微信签名 
       },
       function(res){
          window.location.href = '/member/order/index?status=';     
          if(res.err_msg == "get_brand_wcpay_request:ok" ) {
            // alert('pay success');
          }else{
            // alert(JSON.stringify(res));
            // alert(res.err_msg);
          }
// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
       }
   ); 
}
if (typeof WeixinJSBridge == "undefined"){
   if( document.addEventListener ){
       document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
   }else if (document.attachEvent){
       document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
       document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
   }
}else{
   onBridgeReady();
}
</script>
