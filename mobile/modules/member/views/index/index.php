<?php
$this->params = ['js' => 'js/account-index.js','css'=>'css/account.css'];
$this->title = '九大爷平台 - 会员首页';
?>
<!--main container-->
<main class="container">
    <!--account page-->
    <div class="wechat-account">
        <!--user info-->
        <div class="user-info">
            <div class="user-avatar J_header_img">
                <img src="" alt="">
            </div>
            <p class="name J_shop_name"></p>
            <a class="mobile-link" href="/temp/invite"><p class="J_telephone"></p></a>
        </div>
        <!--orders-->
        <div class="panel">
            <div class="panel-heading">
                <h4>我的订单</h4>
                <a href="/member/order/index?status=">全部订单</a>
            </div>
            <div class="panel-body">
                <div class="order-list">
                    <a href="/member/order/index?status=0"><i></i>待付款</a>
                    <a href="/member/order/index?status=1"><i></i>待发货</a>
                    <a href="/member/order/index?status=2"><i></i>待收货</a>
                    <a href="/member/order/index?status=3"><i></i>已收货</a>
                    <!--
                    <a href="#"><i></i>售后</a>
                    -->
                </div>
            </div>
        </div>
        <!--latest order-->
        <div class="panel">
            <div class="panel-heading">
                <h4>最新订单</h4>
                <a href="/member/order/index?status=">查看</a>
            </div>
            <div class="panel-body J_neworder">
                <!--最新订单展示-->
                <script type="text/template" id="J_tpl_neworder">
                    {@each _ as it,index}
                <div class="acc-latest-order">
                    <div class="title">
                        <div class="h5">订单编号：${it.order_no}</div>
                        <span>
                            {@if it.status==0}未付款
                            {@else if it.status==1}待发货
                            {@else if it.status==2}已发货
                            {@else if it.status==3}已收货
                            {@else if it.status==4}已取消
                            {@else if it.status==5}已关闭
                            {@/if}
                        </span>
                        <span>${it.create_time}</span>
                    </div>
                    <div class="border-box">

                        {@each it.items as item ,index1}
                        <a href="/member/order/detail?no=${it.order_no}">
                            <!-- single item-->
                            <figure class="order-item">
                                <aside>
                                    <img src="${item.image}">
                                </aside>
                                <main>
                                    <div class="description">${item.title}</div>
                                    <div class="param">
                                    {@each item.attributes as attr}
                                        <span>${attr.attribute} : ${attr.option}</span>
                                    {@/each}
                                    </div>
                                    <div class="price">单价：<span>￥${item.price}</span></div>
                                    <span class="ammount">${item.count}</span>
                                </main>
                            </figure>
                        </a>
                        {@/each}

                    </div>
                    <small>共${it.items.length}件商品 合计￥${it.total_fee}</small>
                </div>
                    {@/each}
                </script>

            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h4>我的车膜</h4>
                <a href="/membrane/order">查看</a>
            </div>
            <div class="panel-body J_myfilm">
                <!--最新订单展示-->
                <script type="text/template" id="J_tpl_film">
                    {@each items as it,index}
                <div class="acc-latest-order">
                    <div class="title">
                        <div class="h5">订单编号：${it.no}</div>
                        <span>
                            {@if it.status==1}未支付
                            {@else if it.status==2}已支付
                            {@else if it.status==3}已接单
                            {@else if it.status==4}已完成
                            {@/if}
                        </span>
                        <span>${it.createDate}</span>
                    </div>
                    <div class="border-box">
                        {@each it.items as item ,index1}
                        <a href="/membrane/order/detail?no=${it.no}">
                            <!-- single item-->
                            <figure class="order-item">
                                <aside>
                                    <img src="${item.membrane_product_id === 1 ? '/images/film/address/pro-pic.jpg' : '/images/film/address/apex.jpg'}">
                                </aside>
                                <main>
                                    <div class="description">${item.name}</div>
                                    <div class="param">
                                    {@each item.attributes as attr}
                                        <span>${attr.block} : ${attr.type}</span>
                                    {@/each}
                                    </div>
                                    <div class="price">单价：<span>￥${item.price}</span></div>
                                    <span class="ammount">1</span>
                                </main>
                            </figure>
                        </a>
                        {@/each}

                    </div>
                    <small>共${it.items.length}件商品 合计￥${it.items[0].price}</small>
                </div>
                    {@/each}
                </script>

            </div>
        </div>
        <!--balance-->
        <div class="panel">
            <div class="panel-heading">
                <h4>账户余额</h4>
                <a href="" class="hidden">明细</a>
            </div>
            <div class="panel-body">
                <div class="acc-balance-info">
                    <div class="right account-balance"></div>
                    <a href="" class="btn hidden">查看交易明细</a>
                </div>
            </div>
        </div>
        <!--shop info-->
        <div class="panel hidden">
            <div class="panel-heading">
                <h4>门店信息</h4>
            </div>
            <div class="panel-body">
                <ul class="acc-shop-info">
                    <li>店铺名称：<span class="J_shop_name"></span></li>
                    <li>所属区域：<span class="J_area"></span></li>
                    <li>默认地址：<span class="J_address"></span></li>
                </ul>
            </div>
        </div>
        <!--address-->
        <div class="panel">
            <div class="panel-heading">
                <h4>地址管理</h4>
                <a href="/member/address/index">修改</a>
            </div>
            <div class="panel-body">
                <div class="acc-address-info">
                    <div class="title">
                        <span class="tip">默认</span>
                        <span class=" J_rec_address"></span>
                    </div>
                    <div class="person">
                        <span class="J_receiver"></span>
                        <span class="J_rec_mobile"></span>
                    </div>
                    <a href="/member/address/add" class="btn">新增地址</a>
                </div>
            </div>
        </div>
        <!--coupon-->
        <div class="panel">
            <div class="panel-heading">
                <h4>我的优惠券</h4>
                <a href="coupon/index">查看更多</a>
            </div>
            <div class="panel-body">
                <ul class="acc-coupon-info J_coupon_list">
                    <script type="text/template" id="J_coupon_list">
                        {@each _ as it}
                    <li class="coupon-item">
                        <div class="pull-left">
                            <div class="price">
                                <div class="big">
                                    <small>￥</small><label>${it.Coupon.price}</label>
                                </div>
                                <p>满${it.Coupon.consume_limit}可用</p>
                            </div>
                            <div class="info">
                                <p class="h2">[{@if it.Coupon.supplier}${it.Coupon.supplier.brand_name}{@else}全场可用{@/if}]</p>
                                <p>有效时间：${it.Coupon.start_time}至${it.Coupon.end_time}</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="chain">
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                                <s></s>
                            </div>
                            <a href="/"><span>立即使用</span></a>
                        </div>
                    </li>
                        {@/each}
                    </script>
                </ul>
            </div>
        </div>
        <!-- 拼购列表 -->
        <div class="pg-box">
            <ul class="pg-list" id="pg-list">
                <li class="pg-item">
                    <span>我的拼购</span>
                    <a class="pg-link" href="/member/gpubs-order/index">查看更多</a>
                </li>
                <li class="pg-item">
                    <span>自提拼购提货</span>
                    <a class="pg-link" href="/member/gpubs-pick/index">查看更多</a>
                </li>
                <li class="pg-item pg-alone">
                    <span>自提点管理</span>
                    <a class="pg-link" href="/member/spot-address/gpubs-index"><i></i></a>
                </li>
                <li class="pg-item pg-alone">
                    <span>自提拼购提货核销</span>
                    <a class="pg-link" href="/member/franchiser/index"><i></i></a>
                </li>
            </ul>
        </div>
    </div>
</main>
<!--free lunch-->
 <div class="mask-container wechat-activity-0904-mask hidden">
    <div class="mask-bg"></div>
    <div class="wechat-activity-0904">
        <div class="sub-content entrance in">
            <img src="/images/account/tanchuang.png" class="img-responsive" data-dismiss="mask">
            <div class="btn-row hidden">
                <a href="/temp/groupbuy" class="btn"></a>
            </div>
        </div>
        <a href="javascript:void(0)" class="close-btn" data-dismiss="mask"></a>
    </div>
</div>