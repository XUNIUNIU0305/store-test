<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/28 0028
 * Time: 11:49
 * @var $this \yii\web\View
 */

use mobile\modules\membrane\assets\MembraneAsset;

MembraneAsset::register($this)->addJs('js/index.js')->addCss('css/index.css');
?>

<main class="container">
    <div class="mobile-special-supply-membrane">
        <!-- 头部导航部分 -->
        <ul id="myTab" class="nav-tabs">
            <li class="active" data-type="1"><a href="#">天御车膜</a></li>
            <li data-type="2" class="J_apex_tab hidden"><a href="#">欧帕斯车膜</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <!-- 天御车膜 -->
            <div class="imperial-court active">
                <a href="#"><img src="/images/mobile_membrane/tianyu_img.jpg" alt=""></a>
                <div class="imperial-court-tit">
                    <div class="imperial-court-tit-price">
                        <span>￥<span class="benchmark-price">1180</span><span>.00</span></span>
                        <img src="/images/mobile_membrane/standard_price_label.png" alt="">
                    </div>
                    <div class="introduce hidden">
                        <a href="#">
                            <img src="/images/mobile_membrane/details_60_icon.png" alt="">
                            <span>介 绍</span>
                        </a>
                    </div>
                    <h1 class="imperial-court-tit-txt">天御特效护眼护肤功能膜</h1>
                </div>
                <div class="imperial-court-cont-1">
                    <div class="parameter-sele" id="ty-coefficient">
                        <span>选择<span class="parameter-sele-txt">套餐/系数</span></span>
                        <img id="ty-coefficient" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                    </div>
                    <div class="parameter-sele" id="ty-price-reference">
                        <span>参考<span class="parameter-sele-txt">售价参考</span></span>
                        <img src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                    </div>
                    <div class="parameter-sele ty-receiving-addr">
                        <span>送至
                            <span class="parameter-sele-txt">选择收货地址</span>
                        </span>
                        <img src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                    </div>
                    <div class="address-container">
                        <div>
                            <span class="default J_address_default">默认</span>
                            <span class="J_address_name"></span>
                            <span class="J_address_mobile"></span>
                        </div>
                        <div class="J_address_detail"></div>
                    </div>
                    <div class="parameter-sele-number">
                        <span>数量</span>
                        <div class="calculation-btn btn-disabled" id="ty-del-number" data-val="1">-</div>
                        <div id="ty-purchase-quantity" data-val="1">1</div>
                        <div class="calculation-btn" id="ty-add-number" data-val="2">+</div>
                    </div>
                </div>
                <div class="imperial-court-cont-2">
                    <div class="payment-method-1">选择支付方式</div>
                    <div class="payment-method-2" data-payment="1">
                        <img class="payment-method-icon" src="/images/mobile_membrane/yue_60_icon.png" alt="">
                        <div class="payment-method-cont">
                            <div>余额支付</div>
                            <div class="balance">账户余额 ￥<span class="J_account_blance">0.00</span></div>
                        </div>
                        <div class="payment-method-sele" data-flag="false"></div>
                    </div>
                    <div class="payment-method-2" data-payment="3">
                        <img class="payment-method-icon" src="/images/mobile_membrane/weixinzhifu_60_icon.png" alt="">
                        <div class="payment-method-cont">
                            <div>微信支付</div>
                            <div class="balance balance-alone">微信安全支付</div>
                        </div>
                        <div class="payment-method-sele active" data-flag="false"></div>
                    </div>
                </div>

                <!-- 底部结算部分 -->
                <div class="payment-area">
                    <div class="payment-area-total">
                        <span>共计 <span id="J_ty_num">0</span> 件</span>
                        <span>合计:<span id="J_ty_price">0.00</span></span>
                    </div>
                    <a class="payment-area-btn J_sure_pay_money" href="javascript:void(0)">确认支付</a>
                </div>
            </div>

            <!-- 欧帕斯车膜 -->
            <div class="european-park">
                <a href="#"><img src="/images/mobile_membrane/apex_img.jpg" alt=""></a>
                <div class="imperial-court-tit">
                    <div class="imperial-court-tit-price">
                        <span>
                            ￥<span class="benchmark-price">2354</span><span>.00</span> -
                            ￥<span class="benchmark-price">8184</span><span>.00</span>
                        </span>
                        <img src="/images/mobile_membrane/standard_price_label.png" alt="">
                    </div>
                    <div class="introduce hidden">
                        <a href="javascript:;" id="J_show_apex_detail">
                            <img src="/images/mobile_membrane/details_60_icon.png" alt="">
                            <span>介 绍</span>
                        </a>
                    </div>
                    <h1 class="imperial-court-tit-txt">欧帕斯全功能隔热安全膜</h1>
                </div>
                <div class="imperial-court-cont-1">
                    <div class="parameter-sele" id="ops-coefficient">
                        <span>选择<span class="parameter-sele-txt">套餐/系数</span></span>
                        <img src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                    </div>
                    <div class="parameter-sele" id="ops-price-reference">
                        <span>参考<span class="parameter-sele-txt">售价参考</span></span>
                        <img id="ops-price-reference" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                    </div>
                    <div class="parameter-sele ty-receiving-addr">
                        <span>送至
                            <span class="parameter-sele-txt">选择收货地址</span>
                        </span>
                        <img src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                    </div>
                    <div class="address-container">
                        <div>
                            <span class="default J_address_default">默认</span>
                            <span class="J_address_name"></span>
                            <span class="J_address_mobile"></span>
                        </div>
                        <div class="J_address_detail"></div>
                    </div>
                    <div class="parameter-sele-number">
                        <span>数量</span>
                        <div class="calculation-btn btn-disabled" id="ops-del-number" data-val="1">-</div>
                        <div id="ops-purchase-quantity" data-val="1">1</div>
                        <div class="calculation-btn" id="ops-add-number" data-val="2">+</div>
                    </div>
                </div>
                <div class="imperial-court-cont-2">
                    <div class="payment-method-1">选择支付方式</div>
                    <div class="payment-method-2" data-payment="1">
                        <img class="payment-method-icon" src="/images/mobile_membrane/yue_60_icon.png" alt="">
                        <div class="payment-method-cont">
                            <div>余额支付</div>
                            <div class="balance">账户余额 ￥<span class="J_account_blance">0.00</span></div>
                        </div>
                        <div class="payment-method-sele"></div>
                    </div>
                    <div class="payment-method-2" data-payment="3">
                        <img class="payment-method-icon" src="/images/mobile_membrane/weixinzhifu_60_icon.png" alt="">
                        <div class="payment-method-cont">
                            <div>微信支付</div>
                            <div class="balance balance-alone">微信安全支付</div>
                        </div>
                        <div class="payment-method-sele active"></div>
                    </div>
                </div>
                <!-- 底部结算部分 -->
                <div class="payment-area">
                    <div class="payment-area-total">
                        <span>共计 <span id="J_apex_num">0</span> 件</span>
                        <span>合计:<span id="J_apex_price">0.00</span></span>
                    </div>
                    <a class="payment-area-btn J_sure_pay_money" href="javascript:void(0)">确认支付</a>
                </div>
            </div>
        </div>

        <!-- 天御套餐/系数 -->
        <div class="choose-set-meal-box" id="ty-choose-set-meal-box">
            <div class="choose-set-meal">
                <div class="choose-set-meal-txt">选择套餐</div>
                <div class="stall-parameters" data-package="1">
                    <div class="stall-parameters-tit">
                        <span>Z8 + G15</span>
                        <div data-id="1" class="c-coefficient-pic" data-name="Z8+G15" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>Z8</span></li>
                            <li>后档：<span>G15</span></li>
                            <li>左前档：<span>G15</span></li>
                            <li>右前档：<span>G15</span></li>
                            <li>左后档：<span>G15</span></li>
                            <li>右后档：<span>G15</span></li>
                        </ul>
                    </div>      
                </div>
                <div class="stall-parameters" data-package="2">
                    <div class="stall-parameters-tit">
                        <span>Z8 + G20</span>
                        <div data-id="2" class="c-coefficient-pic" data-name="Z8+G20" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont" id="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>Z8</span></li>
                            <li>后档：<span>G20</span></li>
                            <li>左前档：<span>G20</span></li>
                            <li>右前档：<span>G20</span></li>
                            <li>左后档：<span>G20</span></li>
                            <li>右后档：<span>G20</span></li>
                        </ul>
                    </div>      
                </div>
                <div class="stall-parameters" data-package="3">
                    <div class="stall-parameters-tit">
                        <span>Z8 + G30</span>
                        <div data-id="3" class="c-coefficient-pic" data-name="Z8+G30" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>Z8</span></li>
                            <li>后档：<span>G30</span></li>
                            <li>左前档：<span>G30</span></li>
                            <li>右前档：<span>G30</span></li>
                            <li>左后档：<span>G30</span></li>
                            <li>右后档：<span>G30</span></li>
                        </ul>
                    </div>      
                </div>
                <div class="stall-parameters" data-package="4">
                    <div class="stall-parameters-tit">
                        <span>自定义组合</span>
                        <div class="c-coefficient-pic" data-name="自定义组合" id="custom-combination"></div>
                    </div>
                    <!-- <div class="stall-parameters-cont">
                        <ul id="custom-param-list">
                            <li>前档：<span></span></li>
                            <li>后档：<span></span></li>
                            <li>左前档：<span></span></li>
                            <li>右前档：<span></span></li>
                            <li>左后档：<span></span></li>
                            <li>右后档：<span></span></li>
                        </ul>
                    </div> -->
                </div>
                <div class="stall-parameters-alone">
                    <div class="coefficient-list">
                        <div class="coefficient-list-tit">
                            <span>选择系数</span>
                            <a href="javascript:;" class="J_show_reference"><span class="s-txt">系数参考</span></a>
                        </div>
                        <ul class="coefficient-list-cont" id="ty-coefficient-list-cont">
                            <li class="active">1.00</li>
                            <li>1.08</li>
                            <li>1.12</li>
                            <li>1.20</li>
                            <li>1.50</li>
                            <li>2.00</li>
                        </ul>
                    </div>
                    <div class="coefficient-memo">
                        <span>备注</span>
                        <input type="text" maxlength="100" id="J_ty_remarks" placeholder="选填，请告诉我们特殊要求"/>
                    </div>
                    <a class="parameter-confirm" id="ty-set-meal-confirmation" href="javascript:void(0)">确认</a>
                </div>      
            </div>
        </div>

        <!-- 天御售价参考 -->
        <div class="price-reference-cont" id="ty-price-reference-cont">
            <div class="c-coefficient-box">
                <div class="c-coefficient" data-show="true">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">系数：<span>1.00</span></div>
                        <div data-id="1" class="c-coefficient-pic c-coefficient-pic-active" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont" style="display: block;">
                        <div class="detailed-coefficient">
                            <span>报价</span>
                            <span>￥<span>2480</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-2">
                            <span>最低售价</span>
                            <span>￥<span>1980</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-3">
                            <span>门店采购价</span>
                            <span>￥<span>1180</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">系数：<span>1.08</span></div>
                        <div data-id="2" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>报价</span>
                            <span>￥<span>2678</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>最低售价</span>
                            <span>￥<span>2138</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>门店采购价</span>
                            <span>￥<span>1274</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">系数：<span>1.12</span></div>
                        <div data-id="3" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>报价</span>
                            <span>￥<span>2778</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>最低售价</span>
                            <span>￥<span>2218</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>门店采购价</span>
                            <span>￥<span>1322</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">系数：<span>1.20</span></div>
                        <div data-id="4" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>报价</span>
                            <span>￥<span>2976</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>最低售价</span>
                            <span>￥<span>2376</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>门店采购价</span>
                            <span>￥<span>1416</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">系数：<span>1.50</span></div>
                        <div data-id="5" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>报价</span>
                            <span>￥<span>3720</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>最低售价</span>
                            <span>￥<span>2970</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>门店采购价</span>
                            <span>￥<span>1770</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">系数：<span>2.00</span></div>
                        <div data-id="6" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>报价</span>
                            <span>￥<span>4960</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>最低售价</span>
                            <span>￥<span>3960</span></span>
                        </div>
                        <div class="detailed-coefficient">
                            <span>门店采购价</span>
                            <span>￥<span>2360</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ty-price-close">关闭</div>
        </div>

        <!-- 天御自定义组合 -->
        <div class="custom-combination" id="custom-combination-cont">
            <ul class="custom-combination-list" id="custom-combination-list">
                <li data-label="1" data-value="1" data-name="前档">
                    前档<span id="custom-combination-txt-1">Z8</span>
                    <img data-id="1" class="custom-combination-pic" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                </li>
                <li data-label="2" data-name="后档">
                    后档<span id="custom-combination-txt-2">请选择</span>
                    <img data-id="2" class="custom-combination-pic" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                </li>
                <li data-label="4" data-name="左前档">
                    左前档<span id="custom-combination-txt-3">请选择</span>
                    <img data-id="3" class="custom-combination-pic" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                </li>
                <li data-label="5" data-name="右前档">
                    右前档<span id="custom-combination-txt-4">请选择</span>
                    <img data-id="4" class="custom-combination-pic" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                </li>
                <li data-label="3" data-name="左后档">
                    左后档<span id="custom-combination-txt-5">请选择</span>
                    <img data-id="5" class="custom-combination-pic" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                </li>
                <li data-label="6" data-name="右后档">
                    右后档<span id="custom-combination-txt-6">请选择</span>
                    <img data-id="6" class="custom-combination-pic" src="/images/mobile_membrane/arrows_24_icon.png" alt="">
                </li>
            </ul>
            <div class="gate-gear-cont" id="gate-gear-cont">
                <div class="gate-gear">
                    <div class="gate-parameter">
                        <h1>请选择<span class="J_place_name"></span></h1>
                        <ul id="gate-parameter-list-1">
                            <li data-id="2">G15</li>
                            <li data-id="3">G20</li>
                            <li data-id="4">G30</li>
                        </ul>
                    </div>
                </div>
            </div>
            <a class="parameter-confirm" id="custom" href="javascript:void(0)">确认</a>
        </div>

        <!-- 欧帕斯套餐/系数 -->
        <div class="choose-set-meal-box" id="ops-choose-set-meal-box">
            <div class="choose-set-meal">
                <div class="choose-set-meal-txt">选择套餐</div>
                <div class="stall-parameters" data-type="1">
                    <div class="stall-parameters-tit">
                        <span>吉祥套餐</span>
                        <span>基准价：￥<span data-base-price="6"></span></span>
                        <div data-id="1" class="c-coefficient-pic" data-name="吉祥套餐" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>U9</span></li>
                            <li>后档：<span>U7</span></li>
                            <li>左前档：<span>U7</span></li>
                            <li>右前档：<span>U7</span></li>
                            <li>左后档：<span>U7</span></li>
                            <li>右后档：<span>U7</span></li>
                        </ul>
                    </div>      
                </div>
                <div class="stall-parameters" data-type="2">
                    <div class="stall-parameters-tit">
                        <span>如意套餐</span>
                        <span>基准价：￥<span data-base-price="11"></span></span>
                        <div data-id="2" class="c-coefficient-pic" data-name="如意套餐" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>U9</span></li>
                            <li>后档：<span>I8</span></li>
                            <li>左前档：<span>I8</span></li>
                            <li>右前档：<span>I8</span></li>
                            <li>左后档：<span>I8</span></li>
                            <li>右后档：<span>I8</span></li>
                        </ul>
                    </div>     
                </div>
                <div class="stall-parameters" data-type="3">
                    <div class="stall-parameters-tit">
                        <span>幸福套餐</span>
                        <span>基准价：￥<span data-base-price="16"></span></span>
                        <div data-id="3" class="c-coefficient-pic" data-name="幸福套餐" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>U9</span></li>
                            <li>后档：<span>R5</span></li>
                            <li>左前档：<span>R5</span></li>
                            <li>右前档：<span>R5</span></li>
                            <li>左后档：<span>R5</span></li>
                            <li>右后档：<span>R5</span></li>
                        </ul>
                    </div>     
                </div>
                <div class="stall-parameters" data-type="4">
                    <div class="stall-parameters-tit">
                        <span>平安套餐</span>
                        <span>基准价：￥<span data-base-price="21"></span></span>
                        <div data-id="4" class="c-coefficient-pic" data-name="平安套餐" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>U9</span></li>
                            <li>后档：<span>V5</span></li>
                            <li>左前档：<span>V5</span></li>
                            <li>右前档：<span>V5</span></li>
                            <li>左后档：<span>V5</span></li>
                            <li>右后档：<span>V5</span></li>
                        </ul>
                    </div>     
                </div> 
                <div class="stall-parameters" data-type="5">
                    <div class="stall-parameters-tit">
                        <span>开心套餐</span>
                        <span>基准价：￥<span  data-base-price="26"></span></span>
                        <div data-id="5" class="c-coefficient-pic" data-name="开心套餐" data-flag="false"></div>
                    </div>
                    <div class="stall-parameters-cont">
                        <ul>
                            <li>前档：<span>U9</span></li>
                            <li>后档：<span>E5</span></li>
                            <li>左前档：<span>E5</span></li>
                            <li>右前档：<span>E5</span></li>
                            <li>左后档：<span>E5</span></li>
                            <li>右后档：<span>E5</span></li>
                        </ul>
                    </div>     
                </div>
                <div class="stall-parameters-alone">
                    <div class="coefficient-list">
                        <div class="coefficient-list-tit">
                            <span>选择系数</span>
                            <a href="javascript:;" class="J_show_reference"><span class="s-txt">系数参考</span></a>
                        </div>
                        <ul class="coefficient-list-cont" id="ops-coefficient-list-cont">
                            <li data-type="1" class="active">1.00</li>
                            <li data-type="2">1.08</li>
                            <li data-type="3">1.12</li>
                            <li data-type="4">1.20</li>
                            <li data-type="5">1.50</li>
                            <li data-type="6">2.00</li>
                        </ul>
                    </div>
                    <div class="coefficient-memo">
                        <span>备注</span>
                        <input type="text" id="J_apex_remark" maxlength="100" placeholder="选填，请告诉我们特殊要求"/>
                    </div>
                    <a class="parameter-confirm" id="ops-set-meal-confirmation" href="javascript:void(0)">确认</a>
                </div>     
            </div>
        </div>

        <!-- 欧帕斯售价参考 -->
        <div class="price-reference-cont" id="ops-price-reference-cont">
            <div class="c-coefficient-box">
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">吉祥套餐 <span>U9+U7</span></div>
                        <div data-id="1" class="c-coefficient-pic c-coefficient-pic-active" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont" style="display: block;">
                        <div class="detailed-coefficient">
                            <span>系数</span>
                            <span>1.00</span></span>
                            <span>1.08</span></span>
                            <span>1.12</span></span>
                            <span>1.20</span></span>
                            <span>1.50</span></span>
                            <span>2.00</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-2">
                            <span>报价</span>
                            <span>￥<span>16800</span></span>
                            <span>￥<span>18144</span></span>
                            <span>￥<span>18816</span></span>
                            <span>￥<span>20160</span></span>
                            <span>￥<span>25200</span></span>
                            <span>￥<span>33600</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-3">
                            <span>门店采购价</span>
                            <span>￥<span>8184</span></span>
                            <span>￥<span>8839</span></span>
                            <span>￥<span>9167</span></span>
                            <span>￥<span>9821</span></span>
                            <span>￥<span>12276</span></span>
                            <span>￥<span>16368</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">如意套餐 <span>U9+I8</span></div>
                        <div data-id="2" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>系数</span>
                            <span>1.00</span></span>
                            <span>1.08</span></span>
                            <span>1.12</span></span>
                            <span>1.20</span></span>
                            <span>1.50</span></span>
                            <span>2.00</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-2">
                            <span>报价</span>
                            <span>￥<span>13800</span></span>
                            <span>￥<span>14904</span></span>
                            <span>￥<span>15456</span></span>
                            <span>￥<span>16560</span></span>
                            <span>￥<span>20700</span></span>
                            <span>￥<span>27600</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-3">
                            <span>门店采购价</span>
                            <span>￥<span>6974</span></span>
                            <span>￥<span>7532</span></span>
                            <span>￥<span>7811</span></span>
                            <span>￥<span>8369</span></span>
                            <span>￥<span>10461</span></span>
                            <span>￥<span>13948</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">幸福套餐 <span>U+R5</span></div>
                        <div data-id="3" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>系数</span>
                            <span>1.00</span></span>
                            <span>1.08</span></span>
                            <span>1.12</span></span>
                            <span>1.20</span></span>
                            <span>1.50</span></span>
                            <span>2.00</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-2">
                            <span>报价</span>
                            <span>￥<span>8800</span></span>
                            <span>￥<span>9504</span></span>
                            <span>￥<span>9856</span></span>
                            <span>￥<span>10560</span></span>
                            <span>￥<span>13200</span></span>
                            <span>￥<span>17600</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-3">
                            <span>门店采购价</span>
                            <span>￥<span>4609</span></span>
                            <span>￥<span>4978</span></span>
                            <span>￥<span>5163</span></span>
                            <span>￥<span>5531</span></span>
                            <span>￥<span>6914</span></span>
                            <span>￥<span>9218</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">平安套餐 <span>U9+V5</span></div>
                        <div data-id="4" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>系数</span>
                            <span>1.00</span></span>
                            <span>1.08</span></span>
                            <span>1.12</span></span>
                            <span>1.20</span></span>
                            <span>1.50</span></span>
                            <span>2.00</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-2">
                            <span>报价</span>
                            <span>￥<span>5980</span></span>
                            <span>￥<span>6458</span></span>
                            <span>￥<span>6698</span></span>
                            <span>￥<span>7176</span></span>
                            <span>￥<span>8970</span></span>
                            <span>￥<span>11960</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-3">
                            <span>门店采购价</span>
                            <span>￥<span>2959</span></span>
                            <span>￥<span>3196</span></span>
                            <span>￥<span>3315</span></span>
                            <span>￥<span>3551</span></span>
                            <span>￥<span>4439</span></span>
                            <span>￥<span>5918</span></span>
                        </div>
                    </div>
                </div>
                <div class="c-coefficient">
                    <div class="c-coefficient-tit">
                        <div class="c-coefficient-txt">开心套餐 <span>U9+E5</span></div>
                        <div data-id="5" class="c-coefficient-pic" data-flag="false"></div>
                    </div>
                    <div class="c-coefficient-cont">
                        <div class="detailed-coefficient">
                            <span>系数</span>
                            <span>1.00</span></span>
                            <span>1.08</span></span>
                            <span>1.12</span></span>
                            <span>1.20</span></span>
                            <span>1.50</span></span>
                            <span>2.00</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-2">
                            <span>报价</span>
                            <span>￥<span>4980</span></span>
                            <span>￥<span>5378</span></span>
                            <span>￥<span>5578</span></span>
                            <span>￥<span>5976</span></span>
                            <span>￥<span>7470</span></span>
                            <span>￥<span>9960</span></span>
                        </div>
                        <div class="detailed-coefficient detailed-coefficient-3">
                            <span>门店采购价</span>
                            <span>￥<span>2354</span></span>
                            <span>￥<span>2543</span></span>
                            <span>￥<span>2637</span></span>
                            <span>￥<span>2825</span></span>
                            <span>￥<span>3531</span></span>
                            <span>￥<span>4708</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="apex-price-close">关闭</div>
        </div>

    </div>
</main>
<div id="J_maskLayers" class="maskLayers hidden">
    <div class="box">
        <div class="items" data-id="1">
            <div class="title">产品编号：<span>1</span></div>
            <ul class="item J_label_box"></ul>
            <div class="remark">
                <div class="label">天御特效护眼护肤功能膜</div>
                <textarea placeholder="请告诉我们特殊要求..." class="J_remark"></textarea>
            </div>
        </div>
    </div>
    <p class="addon" id="J_add_pro">新添加一个</p>
    <a href="javascript: void(0);" class="btn submit" id="J_submit_option">确认</a>
</div>
<div class="mask-container mask-fixed" id="mask_select_options">
    <div class="mask-bg"></div>
    <div class="wechat-position-select-container">
        <div class="title">选择<span></span></div>
        <ul id="J_options_box"></ul>
        <button class="btn" id="J_close_options">关闭</button>
    </div>
</div>

<!--地址选择-->
<div id="J-address-selected"  class="maskLayersh hidden">
    <div class="maskLayersh-shop J_address_list">
        <script type="text/template" id="J_tpl_address">
            <a href="javascript:void(0);" class="btn-block-top J_add_address"><span><</span>选择收货地址</a>
            <div class="mt44"></div>
            <div class="address-box">
                {@each _ as it}
                <!--address item-->
                <div class="address-item J_address_item" data-id="${it.id}">
                    <div class="title">
                        <span>收货人：<label class="J_contact">${it.consignee}</label></span>
                        <span class="J_mobile">${it.mobile}</span>
                    </div>
                    <div class="detail">收货地址：<span class="J_address">${it.province.name} ${it.city.name} ${it.district.name}${it.detail}</span></div>
                </div>
                {@/each}
                <!--address item-->
            </div>
        </script>

    </div>
</div>

<!-- 售价参考弹窗 -->
<div class="mask-container mask-fixed" id="modal_price">
    <div class="mask-bg"></div>
    <div class="wechat-price-modal">
        <div class="title">售价参考</div>
        <table>
            <thead>
                <tr>
                    <td>系数</td>
                    <td>报价</td>
                    <td>最低售价</td>
                    <td>门店结算价格</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.00</td>
                    <td>￥2480</td>
                    <td>￥1980</td>
                    <td>￥1180</td>
                </tr>
                <tr>
                    <td>1.08</td>
                    <td>￥2678</td>
                    <td>￥2138</td>
                    <td>￥1274</td>
                </tr>
                <tr>
                    <td>1.12</td>
                    <td>￥2778</td>
                    <td>￥2218</td>
                    <td>￥1322</td>
                </tr>
                <tr>
                    <td>1.20</td>
                    <td>￥2976</td>
                    <td>￥2376</td>
                    <td>￥1416</td>
                </tr>
                <tr>
                    <td>2.00</td>
                    <td>￥4960</td>
                    <td>￥3960</td>
                    <td>￥2360</td>
                </tr>
            </tbody>
        </table>
        <button class="btn" id="J_price_close">关闭</button>
    </div>
</div>

<div class="car-type-modal" id="J_modal_type">
    <div class="title"><span id="J_type_close"></span>系数选定参考</div>
    <div class="detail">
        <h3>具体详情请自行查阅</h3>
        <h3>《SUV等特殊车型系数选定参考》</h3>
        <p>注：1、以上车型的划分是根据具体用料、施工难度、风险系数归类划分！</p>
        <p>2、其他未罗列车型请咨询督导！</p>
    </div>
    <div class="table">
        <p class="table-title">*以下图标只是举例说明参考</p>
        <table>
            <thead>
                <tr>
                    <td colspan="2">1.08系数-中大型SUV举例</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>品牌</td>
                    <td>系列</td>
                </tr>
                <tr>
                    <td>宝马</td>
                    <td>X5/X6/X5M/X6M</td>
                </tr>
                <tr>
                    <td>奔驰</td>
                    <td>GLK</td>
                </tr>
                <tr>
                    <td>保时捷</td>
                    <td>卡宴</td>
                </tr>
                <tr>
                    <td>北京汽车</td>
                    <td>BJ80</td>
                </tr>
                <tr>
                    <td>北京幻速</td>
                    <td>S3L</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <td colspan="2">1.12系数-七座MPV/大型SUV举例</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>品牌</td>
                    <td>系列</td>
                </tr>
                <tr>
                    <td>奥迪</td>
                    <td>Q7</td>
                </tr>
                <tr>
                    <td>奔驰</td>
                    <td>M/ML/G/GL/GLC/GLE/GLS/R级</td>
                </tr>
                <tr>
                    <td>巴博斯</td>
                    <td>M级/G级/GL</td>
                </tr>
                <tr>
                    <td>北汽幻速</td>
                    <td>H2/H2V/H3/H3F</td>
                </tr>
                <tr>
                    <td>北汽威旺</td>
                    <td>M50F/M20/M30/M35</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <td colspan="2">1.2系数-七座MPV/大型SUV举例</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>品牌</td>
                    <td>系列</td>
                </tr>
                <tr>
                    <td>北汽威旺</td>
                    <td>205</td>
                </tr>
                <tr>
                    <td>北汽制造</td>
                    <td>战旗/骑士</td>
                </tr>
                <tr>
                    <td>别克</td>
                    <td>GL8</td>
                </tr>
                <tr>
                    <td>本田</td>
                    <td>本田</td>
                </tr>
                <tr>
                    <td>比亚迪</td>
                    <td>商</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <td colspan="2">1.5系数-房车举例</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>品牌</td>
                    <td>系列</td>
                </tr>
                <tr>
                    <td>奔驰</td>
                    <td>V级/唯雅诺</td>
                </tr>
                <tr>
                    <td>北汽幻速</td>
                    <td>H6</td>
                </tr>
                <tr>
                    <td>北汽威旺</td>
                    <td>307</td>
                </tr>
                <tr>
                    <td>北汽制造</td>
                    <td>战旗加长版</td>
                </tr>
                <tr>
                    <td>长安</td>
                    <td>金牛星/V5/睿行M80/长安星光</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- 欧帕斯详情 -->
<div class="apex-product-detail-modal hidden">
    <div class="title">
        <span></span>
        欧帕斯介绍
    </div>
    <img src="/images/mobile_membrane/detail/1.jpg" alt="">
    <img src="/images/mobile_membrane/detail/2.jpg" alt="">
    <img src="/images/mobile_membrane/detail/3.jpg" alt="">
    <img src="/images/mobile_membrane/detail/4.jpg" alt="">
    <img src="/images/mobile_membrane/detail/5.jpg" alt="">
</div>
