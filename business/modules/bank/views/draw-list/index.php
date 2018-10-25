<?php
$this->params = ['css' => 'css/draw-list.css', 'js' => 'js/draw-list.js'];
?>
<div class="business-bank-bill">
    <p class="title">我的账单<a href="/account/index" class="btn pull-right btn-back hidden">返回上一级</a></p>
    <div class="search-box clearfix hidden">
        <div class="pull-left time-box">
            <div class="date-selecter clearfix form-inline">
                <span>时间：</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker" value="2017-03-01">
                    <span class="input-group-btn">
                        <button class="btn btn-default date-icon" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
                <span class="">到</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker" value="2017-03-01">
                    <span class="input-group-btn">
                        <button class="btn btn-default date-icon" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
            </div>
        </div>
        <div class="pull-left stream">
            <div class="input-group">
                <label class="form-label">流水号：</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <div class="pull-right">
            <span class="btn btn-business">查询</span>
        </div>
    </div>
    <div class="tab-box" id="J_tabs_list">
        <span class="tab-label">状态：</span>
        <span class="btn btn-tab" data-status="-1">全部</span>
        <span class="btn btn-tab active" data-status="0">未审核</span>
        <span class="btn btn-tab" data-status="1">通过</span>
        <span class="btn btn-tab" data-status="2">驳回</span>
        <span class="btn btn-tab" data-status="3">失败</span>
        <span class="btn btn-tab" data-status="4">成功</span>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>申请时间</td>
                    <td>提现流水号</td>
                    <td>银行名称</td>
                    <td>银行账号</td>
                    <td>银行开户名称</td>
                    <td>提现金额</td>
                    <td>提现单状态</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody id="J_steam_list">
                <script type="text/template" id="J_tpl_list">
                    {@each _ as it}
                        <tr>
                            <td>${it.apply_time}</td>
                            <td>${it.draw_number}</td>
                            <td>${it.bank.bank_name}</td>
                            <td>${it.bank.acct_no}</td>
                            <td>${it.bank.acct_name}</td>
                            <td>${it.rmb}</td>
                            <td class="text-success">
                                {@if it.status === 0}
                                    未审核
                                {@else if it.status === 1}
                                    通过
                                {@else if it.status === 2}
                                    驳回
                                {@else if it.status === 3}
                                    失败
                                {@else if it.status === 4}
                                    成功
                                {@/if}
                            </td>
                            <td><a href="/bank/draw-detail?id=${it.id}">详情</a></td>
                        </tr>
                    {@/each}
                </script>
            </tbody>
        </table>
    </div>
    <div class="footer text-right" id="J_page_list"></div>
</div>
