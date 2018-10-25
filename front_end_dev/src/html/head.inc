
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>demo</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=1220">
    <meta http-equiv="X-UA-Compatible" content="edge" />
    <meta name="renderer" content="webkit">
    <!-- build:css({.tmp/serve,src}) styles/vendor.css -->
    <!-- bower:css -->
    <!-- run `gulp inject` to automatically populate bower styles dependencies -->
    <!-- endbower -->
    <!-- endbuild -->
    <!-- build:css({.tmp/serve,src}) styles/app.css -->
    <!-- inject:css -->
    <!-- css files will be automatically insert here -->
    <!-- endinject -->
    <!-- endbuild -->
</head>

<body>
<!-- 客服列表 -->
<div class="apx-service-list">
    <ul class="btn-group">
        <li class="btn-group-bg">
            <span id="service_online" onclick="qimoChatClick();" class="zx_online"></span>
            <div class="title">点击在线咨询</div>
        </li>
        <li class="btn-group-bg">
            <span id="service_phone"></span>
            <div class="title">电话客服：400-0318-119</div>
        </li>
        <li class="btn-group-bg">
            <span id="service_email"></span>
            <div class="title">邮件客服：kf@9daye.com.cn</div>
        </li>
        <li class="btn-group-bg">
            <span id="service_wechat" class="zx_weixin test_btn_weixin">
                
            </span>
            <div class="title">
                <img src="/images/service/bg.png" alt="" class="bg">
                <img src="/images/qrcode.jpg" class="qr">
                <p class="text">微信二维码<br>扫一扫，<span>全知道</span></p>
            </div>
        </li>
    </ul>
</div>