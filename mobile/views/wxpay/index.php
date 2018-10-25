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
           var success = "/trade/balance?q=<?= $params['total_fee'] ?>";
           if(getSearchAtrr("group_id") && getSearchAtrr("p_id")){
                var pid = getSearchAtrr("p_id");
                var gid = getSearchAtrr("group_id");
                success = success + "&group_id="+gid+"&p_id="+pid;
            }

//          window.location.href = '/member/order/index?status=';
          if(res.err_msg == "get_brand_wcpay_request:ok" ) {
              window.location.href = success;
             //alert('pay success');
          }else{
              window.location.href = "/trade/fail";
             //alert(JSON.stringify(res));
             //alert(res.err_msg);
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
function getSearchAtrr(attr){
        var attrArr = window.location.search.substr(1).split('&');
        var newArr = attrArr.map((item) => item.split('='));
        var i, len = newArr.length;
        for(i = 0 ; i < len ; i++) {
            if(newArr[i][0] == attr){
                return newArr[i][1];
            }
        }
}
</script>
