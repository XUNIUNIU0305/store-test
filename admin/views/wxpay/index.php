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
          var callback_success = "<?= $callback['success'] ?>";
          var callback_fail = "<?= $callback['fail'] ?>";
          //window.location.href = '/member/order/index?status=';     
          if(res.err_msg == "get_brand_wcpay_request:ok" ) {
            window.location.href = callback_success
          }else{
            window.location.href = callback_fail
          }
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
