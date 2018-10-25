<?php
/**
 * Created by PhpStorm.
 * User: wang li
 * @var $this \yii\web\View
 */

use business\modules\data\assets\BasicAsset;

$asset = BasicAsset::register($this);
$asset->js[] = 'js/home.js';
$asset->css[] = 'css/home.css';
?>

<div class="business-main-wrap">
    <div class="business-data-total">
        <div class="top-title">
            <span class="tip">综合数据</span>
            <a href="/data" class="btn hidden">返回上一级</a>
        </div>
        <p class="h3">综合数据</p>
        <div class="total-items">
            <div class="total-item">
                <div class="item-title">
                    <span><a class="text" href="/data/conversion">消费转化率漏斗</a></span>
                    <input type="text" class="time-input date-time-input J_conversion_time">
                </div>
                <div id="conversion_loading_box">
                    <ul class="item-number">
                        <li><span id="J_total_conversion"></span><span class="sub">%</span><p>总转化率</p></li>
                    </ul>
                    <ul class="item-tooltip" id="J_conversion_list">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                    <div class="item-content" id="H_conversion" style="margin-right: 80px;">
                        
                    </div>
                </div>
            </div>
            <div class="total-item">
                <div class="item-title">
                    <span><a class="text" href="/data/sales">消费总额</a></span>
                    <input type="text" class="time-input date-time-input  J_total_time">
                    <div class="item-select" data-method="totalMoney">
                        <div class="btn-group">
                            <button type="button" class="btn btn-time dropdown-toggle" data-toggle="dropdown">
                            <span class="J_type_title" data-type="day">按天</span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li data-type="hour" data-title="按小时"><a href="#">按小时</a></li>
                                <li data-type="day" data-title="按天"><a href="#">按天</a></li>
                                <li data-type="week" data-title="按周"><a href="#">按周</a></li>
                                <li data-type="month" data-title="按月"><a href="#">按月</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="item-tabs" data-method="totalMoney">
                        <span class="line active" data-status="line"></span>
                        <span class="bar" data-status="bar"></span>
                        <span class="tb" data-status="tb"></span>
                    </div>
                </div>
                <div id="total_loading_box">
                    <ul class="item-number">
                        <li><span id="J_total_money"></span><span class="sub">元</span></li>
                    </ul>
                    <div class="item-content" id="H_price_total">
                        
                    </div>
                    <div class="tb-box hidden">
                        <div class="table iscroll_container">
                            <table>
                                <tbody id="J_total_table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="total-item">
                <div class="item-title">
                    <span><a class="text" href="/data/cart">购物车内金额</a></span>
                    <input type="text" class="time-input date-time-input J_car_time">
                    <div class="item-select" data-method="carMoney">
                        <div class="btn-group">
                            <button type="button" class="btn btn-time dropdown-toggle" data-toggle="dropdown">
                            <span class="J_type_title" data-type="day">按天</span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li data-type="hour" data-title="按小时"><a href="#">按小时</a></li>
                                <li data-type="day" data-title="按天"><a href="#">按天</a></li>
                                <li data-type="week" data-title="按周"><a href="#">按周</a></li>
                                <li data-type="month" data-title="按月"><a href="#">按月</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="item-tabs" data-method="carMoney">
                        <span class="line active" data-status="line"></span>
                        <span class="bar" data-status="bar"></span>
                        <span class="tb" data-status="tb"></span>
                    </div>
                </div>
                <div id="car_loading_box">
                    <ul class="item-number">
                        <li><span id="J_car_price"></span><span class="sub">元</span></li>
                    </ul>
                    <div class="item-content" id="H_car_money">
                        
                    </div>
                    <div class="tb-box hidden">
                        <div class="table iscroll_container">
                            <table>
                                <tbody id="J_car_table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="total-item">
                <div class="item-title">
                    <span><a class="text" href="/data/customization">定制与非定制商品</a></span>
                    <input type="text" class="time-input date-time-input J_custom_time">
                    <div class="item-select" data-method="custom">
                        <div class="btn-group">
                            <button type="button" class="btn btn-time dropdown-toggle" data-toggle="dropdown">
                            <span class="J_type_title" data-type="day">按天</span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li data-type="hour" data-title="按小时"><a href="#">按小时</a></li>
                                <li data-type="day" data-title="按天"><a href="#">按天</a></li>
                                <li data-type="week" data-title="按周"><a href="#">按周</a></li>
                                <li data-type="month" data-title="按月"><a href="#">按月</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="item-tabs" data-method="custom">
                        <span class="line active" data-status="line"></span>
                        <span class="bar" data-status="bar"></span>
                        <span class="tb" data-status="tb"></span>
                    </div>
                </div>
                <div id="custom_loading_box">
                    <ul class="item-number">
                        <li><span id="J_custom_price"></span><span class="sub">元</span><p>定制商品</p></li>
                        <li><span id="J_uncustom_price"></span><span class="sub">元</span><p>非定制商品</p></li>
                    </ul>
                    <div class="item-content" id="H_custom">
                        
                    </div>
                    <div class="tb-box hidden">
                        <div class="table iscroll_container">
                            <table>
                                <tbody id="J_custom_table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="total-item">
                <div class="item-title">
                    <span><a class="text" href="/data/buy">下单金额与时间分布</a></span>
                    <input type="text" class="time-input date-time-input J_scattergram_time">
                    <div class="item-select" data-method="scattergram">
                        <div class="btn-group">
                            <button type="button" class="btn btn-time dropdown-toggle" data-toggle="dropdown">
                            <span class="J_type_title" data-type="day">按天</span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li data-type="hour" data-title="按小时"><a href="#">按小时</a></li>
                                <li data-type="day" data-title="按天"><a href="#">按天</a></li>
                                <li data-type="week" data-title="按周"><a href="#">按周</a></li>
                                <li data-type="month" data-title="按月"><a href="#">按月</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="item-tabs" data-method="scattergram">
                        <span class="dot active" data-status="dot"></span>
                        <!-- <span class="tb" data-status="tb"></span> -->
                    </div>
                </div>
                <div id="buy_loading_box">
                    <div class="item-content" id="H_scattergram" style="height: 370px;margin-top: 20px;">
                        
                    </div>
                    <div class="tb-box hidden" style="height: 370px;margin-top: 20px;">
                        <div class="table iscroll_container">
                            <table>
                                <tbody id="J_scattergram_table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="total-item">
                <div class="item-title">
                    <span><a class="text" href="/data/invite">邀请门店数据</a></span>
                    <input type="text" class="time-input date-time-input J_store_time">
                    <div class="item-select" data-method="store">
                        <div class="btn-group">
                            <button type="button" class="btn btn-time dropdown-toggle" data-toggle="dropdown">
                            <span class="J_type_title" data-type="day">按天</span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li data-type="hour" data-title="按小时"><a href="#">按小时</a></li>
                                <li data-type="day" data-title="按天"><a href="#">按天</a></li>
                                <li data-type="week" data-title="按周"><a href="#">按周</a></li>
                                <li data-type="month" data-title="按月"><a href="#">按月</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="item-tabs" data-method="store">
                        <span class="line active" data-status="line"></span>
                        <span class="tb" data-status="tb"></span>
                    </div>
                </div>
                <div id="invite_loading_box">
                    <div class="item-content" id="H_store" style="height: 370px;margin-top: 20px;">
                        
                    </div>
                    <div class="tb-box hidden" style="height: 370px;margin-top: 20px;">
                        <div class="table iscroll_container">
                            <table>
                                <tbody id="J_store_table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="J_tpl_money">
    <tr>
        <td>日期</td>
        <td>金额(元)</td>
    </tr>
    {@each items as it}
        <tr>
            <td>${it.date.key}</td>
            <td>${it.total}</td>
        </tr>
    {@/each}
</script>
<script type="text/template" id="J_tpl_custom">
    <tr>
        <td>日期</td>
        <td>定制商品金额(元)</td>
        <td>非定制商品金额(元)</td>
    </tr>
    {@each items as it}
        <tr>
            <td>${it.date.key}</td>
            <td>${it.customization}</td>
            <td>${it.normal}</td>
        </tr>
    {@/each}
</script>
<script type="text/template" id="J_tpl_store">
    <tr>
        <td>日期</td>
        <td>销售金额(元)</td>
        <td>邀请人数(人)</td>
        <td>开通人数(人)</td>
    </tr>
    {@each items as it}
        <tr>
            <td>${it.date.key}</td>
            <td>${it.total}</td>
            <td>${it.inviteNum}</td>
            <td>${it.openedNum}</td>
        </tr>
    {@/each}
</script>