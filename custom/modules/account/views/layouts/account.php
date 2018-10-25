<?php
use yii\helpers\Html;
use custom\modules\account\assets\AccountAsset;

AccountAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@custom/views/layouts/header_footer_search.php'); ?>

<div class="custom-account-view">
    <div class="container">
        <div class="custom-account-container">
            <div class="custom-acc-aside pull-left">
                <a href='/account' class="acc-title">账户首页</a>
                <div id="J_menu_list">
                    <script type="text/template" id="J_tpl_menu">
                        {@each _ as it}
                            <div class="acc-aside-list">
                                <a data-toggle="collapse" href="#aside_panel_${it.id}" class="collapsed">
                                    ${it.title}
                                </a>
                                <div class="collapse" id="aside_panel_${it.id}">
                                    <ul class="list-unstyled clearfix">
                                        {@each it.children as child}
                                            <li class="${child.url|url_build}">
                                                <a href="${child.url}">${child.title}<span class="text-danger" data-name="${child.url|name_build}"></span></a>
                                            </li>
                                        {@/each}
                                    </ul>
                                </div>
                            </div>
                        {@/each}
                    </script>
                </div>
            </div>
            <div class="account-header clear-fix hidden">
                <div class="col-xs-4 user-info">
                    <div class="img">
                        <img class="J_acc_head" src="" alt="">
                    </div>
                    <div class="info">
                        <p class="h4 text-ellipsis">您好！
                            <span class="name J_acc_nickname"></span>
                        </p>
                        <p class="h4 text-ellipsis">店铺：
                            <span class="J_acc_name"></span>
                        </p>
                        <p class="h4">手机：
                            <span class="J_acc_mobile"></span>
                        </p>
                        <p class="h3">账户金额：
                            <span class="price J_acc_balance"></span>
                        </p>
                    </div>
                </div>
                <div class="col-xs-4 handle">
                    <div class="col-xs-4">
                        <a href="/account/password">
                            <img src="/images/new-account/xiugaimima.png" alt="">
                            <p class="title">修改密码</p>
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <a href="/account/address">
                            <img src="/images/new-account/dizhiguanli.png" alt="">
                            <p class="title">地址管理</p>
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <a href="/account/recharge">
                            <img src="/images/new-account/zhanghuchongzhi.png" alt="">
                            <p class="title">账户充值</p>
                        </a>
                    </div>
                </div>
                <div class="col-xs-4 address">
                    <p>所属区域：
                        <span class="J_acc_district"></span>
                    </p>
                    <p>默认地址：
                        <span class="J_acc_address"></span>
                    </p>
                    <p class="hidden">五级区域：
                        <span></span>
                    </p>
                </div>
            </div>
            <div class="custom-acc-main" style="min-height: 900px;">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="J_tpl_lately_order">
    {@each orders as it}
        <table class="item">
            <tr>
                <td width="350">
                    {@each it.items as item, index}
                        {@if index < 3}
                            <img src="${item.image}" alt="">
                        {@/if}
                    {@/each}
                </td>
                <td width="180">
                    <p>订单编号：
                        <span>${it.order_no}</span>
                    </p>
                    <p>${it.create_time}</p>
                </td>
                <td width="180">
                    <p class="dis">商品金额：￥
                        <span>${it.total_fee|price_build}</span>
                    </p>
                    <p>实付金额：￥
                        <span>${it.total_fee|price_build}</span>
                    </p>
                </td>
                <td width="100">
                    ${it.status|statu_build}
                </td>
                <td>
                    <a target="_blank" href="/account/order/detail?no=${it.order_no}">订单详情</a>
                    <a href="javascript:;" title="400-0318-119">联系我们</a>
                </td>
            </tr>
        </table>
    {@/each}
</script>
<script type="text/template" id="J_tpl_order">
    {@each orders as it, index}
    <table class="table table-bordered text-center J_table_box" data-no="${it.order_no}">
        <thead>
            <tr>
                <td colspan="7" class="no-border row">
                    <div class="col-xs-12 text-left">
                        <span>订单编号：<span class="text-blue">${it.order_no}</span></span>
                        <span>订单时间：${it.create_time}</span>
                    </div>
                </td>
            </tr>
        </thead>
        <tbody>
            {@each it.items as item, index2}
            <tr>
                <td class="row acc-media-box">
                    <div class="col-xs-12 ">
                        <div class="media text-left">
                            <a class="media-left media-middle" target="_blank" href="/product?id=${item.product_id}">
                                <img src="${item.image}">
                            </a>
                            <div class="media-body media-middle">
                                <a target="_blank" href="/product?id=${item.product_id}">
                                    <h5 class="media-heading">${item.title}</h5>
                                </a>
                                <ul class="list-unstyled text-muted">
                                    {@each item.attributes as attr}
                                    <li>${attr.attribute}：${attr.option}</li>
                                    {@/each}
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
                <td width="80">
                    ${it.status | statu_build}
                    {@if it.status == 3}
                    <div><a class="J_create_refund" style="color: #199ed8;" href="javascript:;" data-no="${it.order_no}" data-id="${item.id}">(申请售后)</a></div>
                    {@/if}
                </td>
                <td width="92">¥${item.price|price_build}</td>
                <td width="64">×${item.count}</td>
                {@if index2 == 0}
                <td width="150" rowspan="${it.items.length}">
                    <div class="text-default">商品金额：¥${it.items_fee | price_build}</div>
                    <div class="text-danger">实付金额：¥${it.total_fee | price_build}</div>
                </td>
                <td width="98" rowspan="${it.items.length}">
                    <div>
                        <a target="_blank" href="/account/order/detail?no=${it.order_no}">订单详情</a>
                    </div>
                    {#
                    <div>
                        <a href="#">查看物流</a>
                    </div>} {#
                    <div>
                        <a href="#">提醒发货</a>
                    </div>}
                    {@if it.status == 0}
                    <div class="J_off_order">
                        <a href="javascript:;">取消订单</a>
                    </div>
                    <div class="acc-pay-again">
                        <a class="collapsed" data-toggle="collapse" href="#collapsePayAgain${index}">去付款
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </a>
                        <div class="collapse-box collapse J_collapse_box" id="collapsePayAgain${index}">
                            <div class="J_payment_box">
                            </div>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-xs btn-danger J_to_pay">付款</button>
                                <button type="button" class="btn btn-xs btn-default" data-toggle="collapse" href="#collapsePayAgain${index}">取消</button>
                            </div>
                        </div>
                    </div>
                    {@/if} 
                    {@if it.status == 1}
                    <div class="J_off_order">
                        <a href="javascript:;">取消订单</a>
                    </div>
                    {@/if}
                </td>
                {@/if}
            </tr>
            {@/each}
        </tbody>
    </table>
    {@/each}
</script>
<?php $this->endContent(); ?>

