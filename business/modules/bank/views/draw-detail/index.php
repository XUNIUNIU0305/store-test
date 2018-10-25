<?php
$this->params = ['css' => 'css/draw-detail.css', 'js' => 'js/draw-detail.js'];
?>
<div class="business-bank-status">
    <p class="title">提现记录详情<a href="/bank/draw-list" class="btn btn-business pull-right">返回提现记录</a></p>
    <div class="info row">
        <div class="col-xs-3 status-box">
            <p class="header">流水号：<span id="J_draw_number"></span></p>
            <p class="status" id="J_status"></p>
        </div>
        <div class="col-xs-8 text-center status-ship">
            <div class="col-xs-3 active">
                <p>提现申请</p>
                <small id="J_apply_time"></small>
            </div>
            <div class="col-xs-3 active">
                <p>平台审核</p>
                <small></small><br>
                <small></small>
            </div>
            <div class="col-xs-3 J_last_status">
                <p>提现成功</p>
                <small id="J_last_time"></small>
            </div>
        </div>
    </div>
    <table class="table">
        <tbody>
            <tr>
                <td>银行</td>
                <td id="J_bank_name"></td>
            </tr>
            <tr>
                <td>银行卡号</td>
                <td id="J_bank_code"></td>
            </tr>
            <tr>
                <td>账户名称</td>
                <td id="J_user_name"></td>
            </tr>
            <tr>
                <td>提现金额</td>
                <td id="J_extract_num"></td>
            </tr>
            <tr>
                <td>审核留言</td>
                <td id="J_remark"></td>
            </tr>
            <tr>
                <td>审核通过时间</td>
                <td id="J_pass_time"></td>
            </tr>
            <tr>
                <td>驳回时间</td>
                <td id="J_reject_time"></td>
            </tr>
            <tr>
                <td>失败时间</td>
                <td id="J_failure_time"></td>
            </tr>
            <tr>
                <td>成功时间</td>
                <td id="J_success_time"></td>
            </tr>
        </tbody>            
    </table>
</div>