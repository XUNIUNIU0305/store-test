<?php
$this->params = ['css' => 'css/n2.css', 'js' => 'js/n2.js'];
?>
<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
<script>
$(function(){
    $(".times a").click(function(){
        var sortid = $(this).data("id");
        $(".item-list-center").addClass("dn");
        $("#child_"+sortid).removeClass("dn");
    });

    $('#bac').click(function(){
        $('#bac').css('background',"red");

    },function(){

    });

})

</script>
<div class="container">
    <nav class="header-nav">
        <ul>
            <li>
                <a href="#miaosha">限时秒杀</a>
            </li>
            <li>
                <a href="#qingjie">清洁养护</a>
            </li>
            <li>
                <a href="#zhuangshi">精品装饰</a>
            </li>
            <li>
                <a href="#chuxing">安全出行</a>
            </li>
            <li id="backtotop">
                <a href="#top">返回顶部</a>
            </li>

    </nav>
    <div class="banner"><a href="javascript:;"></a></div>
    <div class="floor floor0">
        <div class="title"><a id="miaosha" href="javascript:;">限时秒杀</a></div>
        <div class="content">
            <div class="content_center">
                <div class="activity_time_title">
                    <div class="time_img">
                        <div id="CountMsg" class="HotDate">
                            <span style="font-size:0.6rem;color:#E36147" id="active-info">距离活动开始&nbsp;</span>
                            <label id="active-time">
                                <span id="t_d">00</span><span style="font-size:0.6rem;color:#E36147">&nbsp;天</span>
                                <span id="t_h">00</span><span style="font-size:0.6rem;color:#E36147">&nbsp;时</span>
                                <span id="t_m">00</span><span style="font-size:0.6rem;color:#E36147">&nbsp;分</span>
                                <span id="t_s">00</span><span style="font-size:0.6rem;color:#E36147">&nbsp;秒</span>
                            </label>
                        </div>
                    </div>

                    <div class="times">
                        <a style="color:rgb(227, 97, 71);background-color:#fff" id="bac" href="javascript:;" data-id="1" data-start-time="2018/09/19 10:00:00" data-end-time="2018/09/19 23:59:59">
                            09.19
                        </a>
                        <a id="bac" href="javascript:;" data-id="2" data-start-time="2018/09/22 10:00:00" data-end-time="2018/09/22 23:59:59">
                            09.22
                        </a>
                        <a id="bac" href="javascript:;" data-id="3" data-start-time="2018/09/25 10:00:00" data-end-time="2018/09/25 23:59:59">
                            09.25
                        </a>
                        <a id="bac" href="javascript:;" data-id="4" data-start-time="2018/09/28 10:00:00" data-end-time="2018/09/28 23:59:59">
                            09.28
                        </a>
                    </div>
                    <div class="item-list nav-item-box">
                        <div class="item-list-left prev">
                            <img src="/images/180919/jiantou-L@2x.png"/>
                        </div>
                        <div class="item-list-right next">
                            <img src="/images/180919/jiantou-R@2x.png"/>
                        </div>
                        <div  class="item-list-center" id="child_1">
                            <div class="box">
                            	<div class="list">
                            		<ul>
                            			<li class="p1 item" data-id="1550">
                                            <a href="/goods/detail?id=1550" data-src="/goods/detail?id=1550">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img" alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                            			<li class="p2 item" data-id="1896">
                                            <a href="/goods/detail?id=1896" data-src="/goods/detail?id=1896">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img"  alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><li class="p3 item" data-id="2318">
                                            <a href="/goods/detail?id=2318" data-src="/goods/detail?id=2318">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img" alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                            		</ul>
                            	</div>
                            </div>
                        </div>
                        <div  class="item-list-center dn" id="child_2">
                            <div class="box">
                                <div class="list">
                                    <ul>
                                        <li class="p1 item" data-id="2382">
                                            <a href="/goods/detail?id=2382" data-src="/goods/detail?id=2382">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img" alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="p2 item" data-id="1872">
                                            <a href="/goods/detail?id=1872" data-src="/goods/detail?id=1872">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img"  alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><li class="p3 item" data-id="2320">
                                            <a href="/goods/detail?id=2320" data-src="/goods/detail?id=2320">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img" alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div  class="item-list-center dn" id="child_3">
                            <div class="box">
                                <div class="list">
                                    <ul>
                                        <li class="p1 item" data-id="1830">
                                            <a href="/goods/detail?id=1830" data-src="/goods/detail?id=1830">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img"  alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="p2 item" data-id="1472">
                                            <a href="/goods/detail?id=1472" data-src="/goods/detail?id=1472">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img"  alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><li class="p3 item" data-id="2312">
                                            <a href="/goods/detail?id=2312" data-src="/goods/detail?id=2312">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img"  alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div  class="item-list-center dn" id="child_4">
                            <div class="box">
                                <div class="list">
                                    <ul>
                                        <li class="p1 item" data-id="2341">
                                            <a href="/goods/detail?id=2341" data-src="/goods/detail?id=2341">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img"  alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="p2 item" data-id="2313">
                                            <a href="/goods/detail?id=2313" data-src="/goods/detail?id=2313">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img"  alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><li class="p3 item" data-id="1730">
                                            <a href="/goods/detail?id=1730" data-src="/goods/detail?id=1730">
                                                <div class="box-layout">
                                                    <div class="box-layout-img"><img class="J_pro_img" alt=""></div>
                                                    <div class="box-layout-txt">
                                                        <p class="J_pro_title"></p>
                                                        <p class="J_pro_price"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id='qingjie' style="height:2rem;"></div>
    <div class=" floor floor2" id="one">
        <div class="title"><a href="javascript:;">清洁养护</a></div>
        <div class="content_center1">
            <div class="col-3">
                <div class="item" data-id="1583">
                    <a href="/goods/detail?id=1583">
                        <div class="img-box">
                            <img class="J_pro_img" >
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                            <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1523">
                    <a href="/goods/detail?id=1523">
                        <div class="img-box">
                            <img class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2148">
                    <a href="/goods/detail?id=2148">
                        <div class="img-box">
                            <img class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1743">
                    <a href="/goods/detail?id=1743">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1300">
                    <a href="/goods/detail?id=1300">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1888">
                    <a href="/goods/detail?id=1888">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1755">
                    <a href="/goods/detail?id=1755">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1891">
                    <a href="/goods/detail?id=1891">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1895">
                    <a href="/goods/detail?id=1895">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1714">
                    <a href="/goods/detail?id=1714">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2241">
                    <a href="/goods/detail?id=2241">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1723">
                    <a href="/goods/detail?id=1723">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id='zhuangshi' style="height:2rem;"></div>
    <div class=" floor floor3" id="one">
        <div class="title"><a href="javascript:;">精品装饰</a></div>
        <div class="content_center1">
            <div class="col-3">
                <div class="item" data-id="2195">
                    <a href="/goods/detail?id=2195">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1055">
                    <a href="/goods/detail?id=1055">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1909">
                    <a href="/goods/detail?id=1909">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1151">
                    <a href="/goods/detail?id=1151">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1801">
                    <a href="/goods/detail?id=1801">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2085">
                    <a href="/goods/detail?id=2085">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2163">
                    <a href="/goods/detail?id=2163">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1264">
                    <a href="/goods/detail?id=1264">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1630">
                    <a href="/goods/detail?id=1630">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2053">
                    <a href="/goods/detail?id=2053">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1707">
                    <a href="/goods/detail?id=1707">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1053">
                    <a href="/goods/detail?id=1053">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id='chuxing' style="height:2rem;"></div>
    <div class=" floor floor4" id="one">
        <div class="title"><a href="javascript:;">安全出行</a></div>
        <div class="content_center1">
            <div class="col-3">
                <div class="item" data-id="2213">
                    <a href="/goods/detail?id=2213">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1293">
                    <a href="/goods/detail?id=1293">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2310">
                    <a href="/goods/detail?id=2310">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1289">
                    <a href="/goods/detail?id=1289">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2381">
                    <a href="/goods/detail?id=2381">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2314">
                    <a href="/goods/detail?id=2314">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1610">
                    <a href="/goods/detail?id=1610">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1273">
                    <a href="/goods/detail?id=1273">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1617">
                    <a href="/goods/detail?id=1617">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1540">
                    <a href="/goods/detail?id=1540">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="2214">
                    <a href="/goods/detail?id=2214">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <div class="item" data-id="1747">
                    <a href="/goods/detail?id=1747">
                        <div class="img-box">
                            <img  class="J_pro_img">
                        </div>
                        <p class="J_pro_title"></p>
                        <div class="detail">
                            <div class="left">
                                <strong class="J_pro_price"></strong>
                            </div>
                           <span class="btn-buy right">立即抢购</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>



</div>
