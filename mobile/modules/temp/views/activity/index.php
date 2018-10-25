<?php
$this->params = [
    'css' => 'css/activity.css',
    'js' => 'js/activity.js',
];
?>
<!-- main -->
<div class="container">
    <div class="turnplate">
        <div id="wheelcanvas">
            <canvas></canvas>
        </div>
        <div class="pointer"></div>
    </div>
</div>
<!-- <button onclick="showMask();">111</button> -->
<!-- main -->
<!-- mask -->
<div class="mask">
    <div class="bg" data-dismiss></div>
    <div class="cnt">
        <div class="coupon-container">
            <div class="title">恭喜您获得</div>
            <div class="coupon">
                <div class="top">
                    <div class="left" id="J_win_title">
                        
                    </div>
                    <div class="right">
                        <span>￥</span>
                        <strong id="J_win_money"></strong>
                    </div>
                </div>
                <div class="bottom" id="J_win_detail">
                    
                </div>
            </div>
            <p>发放方式：门店三天兑换</p>
            <a class="btn" href="javascript:void(0);" data-dismiss>我知道了</a>
        </div>
    </div>
</div>
