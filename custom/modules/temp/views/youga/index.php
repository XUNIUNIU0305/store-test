<?php
$this->params = ['css' => 'css/youga.css', 'js' => 'js/youga.js'];

$this->title = '九大爷平台 - 御甲星座';
?>
<div class="container apx-zodiac-container">
    <!--十二宫icon导航-->
    <div class="row">
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='1'><span class="zodiac-icon aries active"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='2'><span class="zodiac-icon taurus"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='3'><span class="zodiac-icon gemini"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='4'><span class="zodiac-icon cancer"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='5'><span class="zodiac-icon leo"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='6'><span class="zodiac-icon virgo"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='7'><span class="zodiac-icon libra"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='8'><span class="zodiac-icon scorpio"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='9'><span class="zodiac-icon sagittarius"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='10'><span class="zodiac-icon capricorn"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='11'><span class="zodiac-icon aquarius"></span></a></div>
        <div class="col-xs-1"><a href="javascript:void(0)" data-zodiac='12'><span class="zodiac-icon pisces"></span></a></div>
    </div>
    <!--选号列表-->
    <ul class="list-unstyled clearfix" data-toggle="buttons" data-view="J_zodiac_pick"></ul>
    <!--已选列表-->
    <ul class="list-unstyled clearfix apx-zodiac-list-picked" data-view="J_zodiac_chosen"></ul>
    <!--loading icon layer-->
    <div class="apx-zodiac-loading J_zodiac_loading">
        <i class="glyphicon glyphicon-refresh"></i>
    </div>
    <!--按钮-->
    <div class="apx-zodiac-submit">
        <div class="btn-row">
            <a href="javascript:void(0)" class="btn btn-warning btn-lg" data-control="J_zodiac_chosen">查看已选号码</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-lg disabled" data-control="J_zodiac_pick">提交选号</a>
        </div>
    </div>
</div>