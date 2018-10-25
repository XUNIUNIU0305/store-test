<?php
/**
 * @var $this \yii\web\View
 */

use business\modules\data\assets\BasicAsset;

$this->title = '定制商品分析';
$asset = BasicAsset::register($this);
$asset->js[] = 'js/customization.js';
$asset->css[] = 'css/customization.css';
?>

<div class="business-main-wrap">
    <div class="business-data-customization">
        <div class="data-top-title">
            <span class="tip ds">综合数据</span>
            <span class="second">定制与非定制商品数据分析</span>
            <a href="/data" class="btn-back">返回上一级</a>
        </div>
        <div class="sell-search">
            <div class="row">
                <div class="col-xs-8">
                    <div class="col-xs-3">
                        <label>对象:</label>
                        <select class="selectpicker btn-group-xs" id="J_user_level" data-width="80%" data-max-options="3" multiple>
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <label>时间选择:</label>
                        <input type="text" name="" class="time-input date-time-input" id="J_data_time">
                    </div>
                    <div class="col-xs-2">
                        <label>显示设置:</label>
                        <select class="selectpicker btn-group-xs" data-width="100%" id="J_type_box">
                            <option value="day">按天</option>
                            <option value="week">按周</option>
                            <option value="month">按月</option>
                        </select>
                    </div>
                </div>
                <div class="pull-right col-xs-1">
                    <label>&nbsp;</label>
                    <a href="javascript:;" class="btn btn-search" id="J_data_btn">立即搜索</a>
                </div>
            </div>
        </div>
        <div class="customization-detail">
            <div class="chart-container">
                <div class="tab-list" id="J_data_type">
                    <span class="line active" data-type="line"></span>
                    <span class="bar" data-type="bar"></span>
                </div>
                <div id="H_chart_box" style="max-width: 99%;height: 550px;margin: 0 auto; overflow: hidden;" >
                    
                </div>
            </div>
            <div class="pro-box">
                <p class="pro-title" id="J_pro_type">
                    <span class="active" data-type="is">定制</span>
                    <span data-type="no">非定制</span>
                </p>
                <div class="pro-scroll iscroll_container">
                    <ul id="J_pro_box"></ul>
                </div>
            </div>
        </div>
        <div class="table">
            <p class="table-title"></p>
            <div class="table-box">
                <table>
                    <tbody id="J_data_table">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- 弹窗 -->
<div class="modal fade bs-example-modal-lg business-data-modal-pro" tabindex="-1" role="dialog" id="product_chart_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title clearfix">
                    <span class="pull-left top">销售分布：<span>按</span></span>
                    <div class="col-xs-2 top">
                        <select class="selectpicker btn-group-xs" id="J_area_level" data-width="100%">
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <a href="javascript:;" class="btn btn-search" id="J_pro_btn">立即搜索</a>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="tab-list" id="J_chart_type">
                    <span class="line active" data-type="line"></span>
                    <span class="bar" data-type="bar"></span>
                </div>
                <div class="chart-container" id="H_pro_box">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="J_tpl_data">
    <tr>
        <td>时间</td>
        <td>定制销售金额(元)</td>
        <td>非定制销售金额(元)</td>
    </tr>
    {@each _ as it}
        <tr>
            <td>${it.date.key}</td>
            <td>${it.customizationTotal}</td>
            <td>${it.total}</td>
        </tr>
    {@/each}
</script>
<script type="text/template" id="J_tpl_pro">
    {@each _ as it}
        <li data-toggle="modal" data-target="#product_chart_modal" data-id="${it.id}">
            <p class="item-title">商品ID：<span>${it.id}</span></p>
            <p class="item-detail">${it.title}</p>
            <p class="item-price">单价：￥<span>${it.price|price}</span></p>
            <div class="br"></div>
            <p class="total">总销售额(元)：<span>${it.total}</span></p>
        </li>
    {@/each}
</script>