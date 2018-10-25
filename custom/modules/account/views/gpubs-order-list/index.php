<?php
$this->params = ['js' => 'js/gpubs-order-list.js', 'css' => 'css/gpubs-order-list.css'];
$this->title = '';
?>
<div class="custom-order-group-main">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="J_tab_list">
        <li role="presentation" class="active" data-status="1"><a href="#home" role="tab" data-toggle="tab">拼购中</a></li>
        <li role="presentation" data-status="2"><a href="#home" role="tab" data-toggle="tab">拼购完成</a></li>
        <li role="presentation" data-status="3"><a href="#profile" role="tab" data-toggle="tab">部分提货</a></li>
        <li role="presentation" data-status="4"><a href="#messages" role="tab" data-toggle="tab" >全部提货</a></li>
        <li role="presentation" data-status="0"><a href="#settings" role="tab" data-toggle="tab">拼购失败</a></li>
        <li role="presentation" data-status="6"><a href="#settings" role="tab" data-toggle="tab">参团失败</a></li>
    </ul>
    <div class="order-title-box">
        <table class="table text-center">
            <thead>
                <tr>
                    <td class="hidden"><a href="javascript:;"></a></td>
                    <td width="414">商品</td>
                    <td width="83">状态</td>
                    <td width="95">单价</td>
                    <td width="66">数量</td>
                    <td width="156">总金额</td>
                    <td width="103">操作</td>
                </tr>
            </thead>
        </table>
        <div class="order-group-list" id="J_list_box">

        </div>
        <div class="footer text-right" id="J_page_box"></div>
    </div>
</div>

<script type="text/template" id="J_tpl_list">
    {@each _ as it}
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <td colspan="7" class="no-border row">
                        <strong class="col-xs-9 text-left" style="width:70%;">
                            {@if it.status != 1 && it.status != 0}
                                {@if it.group.gpubs_type == 1}
                                    <span>订单编号：${it.detail_number}</span>
                                {@/if}
                            {@/if}
                            <span>参团时间：${it.join_datetime}</span>
                            <span>拼团编号：${it.group.group_number}</span>
                        </strong>
                        <strong class="col-xs-3 text-right" style="width:30%;">
                            {@if it.group.gpubs_type == 1}
                                <span class="text-default">${it.group.min_quantity_per_group}件起拼</span>
                                <span class="text-danger" style="margin-right:0px;">自提拼购</span>
                            {@/if}
                            {@if it.group.gpubs_type == 2}
                                {@if it.group.gpubs_rule_type == 1}
                                    <span class="text-default">${it.group.min_member_per_group}人起拼</span>
                                {@/if}
                                {@if it.group.gpubs_rule_type == 2}
                                    <span class="text-default">${it.group.min_quantity_per_group}件起拼</span>
                                {@/if}
                                {@if it.group.gpubs_rule_type == 3}
                                    <span class="text-default">${it.group.min_member_per_group}人每人${it.group.min_quanlity_per_member_of_group}件起拼</span>
                                {@/if}
                                <span class="text-danger" style="margin-right:0px;">送货拼购</span>
                            {@/if}
                        </strong>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="row acc-media-box" width="426">
                        <div class="col-xs-12 ">
                            <div class="media text-left">
                                <a class="media-left media-middle" href="/product?id=${it.product.product_id}">
                                    <img src="${it.product.image}">
                                </a>
                                <div class="media-body media-middle">
                                    <a href="/product?id=${it.product.product_id}"><h5 class="media-heading">${it.product.title}</h5></a>
                                    <ul class="list-unstyled text-muted">
                                        {@each it.product.sku as attr}
                                        <li>${attr.name}:${attr.selectedOption.name}</li>
                                        {@/each}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="83">
                    {@if it.group.gpubs_type == 1}
                        ${it.status|status}
                    {@/if}
                    {@if it.group.gpubs_type == 2}
                        {@if it.status == 2}
                            拼购完成
                            {@else}
                            ${it.status|status}
                        {@/if}
                    {@/if}
                    </td>
                    <td width="95">¥${it.product.product_sku_price}</td>
                    <td width="66">×${it.product.quantity}</td>
                    <td width="156">
                        <div class="h4 text-danger">¥${it.product.total_fee}</div>
                    </td>
                    <td width="103">
                        {@if it.status !== 0 && it.status != 1}
                            {@if it.group.gpubs_type == 1}
                                <div><a href="/account/gpubs-order-detail?order_id=${it.id}">订单详情</a></div>
                            {@/if}
                        {@/if}
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div class="group-buy-info">
                            <div class="info-person">
                                <div class="head-img">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                {@if it.status === 1}
                                <div class="end-time">
                                    剩余时间：
                                    <span class="text-danger J_group_time" data-time="${it.group.left_unixtime}">00:00:00</span>
                                </div>
                                {@/if}
                            </div>
                            {@if it.group.gpubs_type == 1}
                            <div class="info-address">
                                <p>
                                    自提点：
                                    <span>${it.group.full_address}</span>
                                </p>
                                <p>
                                    联系人：
                                    <span>${it.group.consignee}</span>
                                    <span>${it.group.mobile}</span>
                                </p>
                            </div>
                            {@/if}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    {@/each}
</script>
<script type="text/template" id="J_tpl_list_fail">
    {@each _ as it}
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <td colspan="7" class="no-border row">
                        <strong class="col-xs-8 text-left">
                            <span>参团失败，您支付的金额将返还至账户余额！</span>
                        </strong>
                        <strong class="col-xs-4 text-right">
                            <span class="text-default">${it.group.target_quantity}件起拼</span>
                            <span class="text-danger">自提拼购</span>
                        </strong>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="row acc-media-box" width="426">
                        <div class="col-xs-12 ">
                            <div class="media text-left">
                                <a class="media-left media-middle" href="javascript:;">
                                    <img src="${it.product.image}">
                                </a>
                                <div class="media-body media-middle">
                                    <a href="javascript:;"><h5 class="media-heading">${it.product.title}</h5></a>
                                    <ul class="list-unstyled text-muted">
                                        {@each it.product.sku_attributes as attr}
                                        <li>${attr.name}:${attr.selectedOption.name}</li>
                                        {@/each}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="83">参团失败</td>
                    <td width="95">¥${it.price}</td>
                    <td width="66">×${it.quantity}</td>
                    <td width="156">
                        <div class="h4 text-danger">¥${it.total_fee}</div>
                    </td>
                    <td width="103">
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div class="group-buy-info">
                            <div class="info-person">
                                <div class="head-img">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <div class="end-time">
                                    剩余时间：
                                    <span class="text-danger">00:00:00</span>
                                </div>
                            </div>
                            <div class="info-address">
                                <p>
                                    自提点：
                                    <span>${it.group.full_address}</span>
                                </p>
                                <p>
                                    联系人：
                                    <span>${it.group.consignee}</span>
                                    <span>${it.group.mobile}</span>
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    {@/each}
</script>