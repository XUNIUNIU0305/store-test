<?php
$this->params = ['css' => 'css/statement-index.css', 'js' => 'js/statement-index.js'];
?>
<div class="business-bank-bill">
    <p class="title">交易记录<a href="/account/index" class="btn pull-right btn-back hidden">返回上一级</a></p>
    <div class="search-box clearfix">
        <div class="pull-left time-box">
            <div class="date-selecter clearfix form-inline">
                <span>时间：</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeStart" value="">
                    <span class="input-group-btn">
                        <button class="btn btn-default date-icon" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
                <span class="">到</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeEnd" value="">
                    <span class="input-group-btn">
                        <button class="btn btn-default date-icon" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
            </div>
        </div>
        <div class="pull-left">
            <span class="btn btn-business" id="search">查询</span>
        </div>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td width=300>交易类别</td>
                    <td width=500>交易内容</td>
                    <td>交易金额</td>
                    <td>交易后账户余额</td>
                    <td>交易时间</td>
                    <td width=200>状态</td>
                </tr>
            </thead>
            <tbody id="J_steam_list">
                <script type="text/template" id="J_tpl_list">
                    {@each _ as it}
                        <tr>
                            <td>
                                {@if it.alteration_type == 1}
                                    {@if it.content.type == 'order_receive'}
                                        车膜订单
                                    {@/if}
                                    {@if it.content.type == 'non_transaction_receive'}
                                        其他入账
                                    {@/if}
                                    {@if it.content.type == 'partner_award'}
                                        门店加盟奖励
                                    {@/if}
                                {@/if}
                                {@if it.alteration_type == 2}
                                    {@if it.content.type == 'pay'}
                                        提现成功
                                    {@/if}
                                    {@if it.content.type == 'non_transaction_pay'}
                                        其他出账
                                    {@/if}
                                {@/if}
                                {@if it.alteration_type == 3}
                                    {@if it.content.type == 'freeze'}
                                        提现操作冻结
                                    {@/if}
                                {@/if}
                                {@if it.alteration_type == 4}
                                    {@if it.content.type == 'thaw'}
                                        提现操作解冻
                                    {@/if}
                                {@/if}
                            </td>
                            <td>$${it.content.message}</td>
                            {@if it.alteration_type == 1}
                                <td class="cashIn">+${it.alteration_amount}</td>
                            {@/if}
                            {@if it.alteration_type == 2}
                                <td class="cashOut">-${it.alteration_amount}</td>
                            {@/if}
                            {@if it.alteration_type == 3}
                                <td class="cashOut">-${it.alteration_amount}</td>
                            {@/if}
                            {@if it.alteration_type == 4}
                               <td class="cashIn">+${it.alteration_amount}</td>
                            {@/if}
                            <td>${it.rmb_after}</td>
                            <td>${it.alteration_datetime}</td>
                            <td class="cashIn">成功</td>
                        </tr>
                    {@/each}
                </script>
            </tbody>
        </table>
    </div>
    <div class="footer text-right" id="J_page_list"></div>
</div>
