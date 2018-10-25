<?php
$this->params = ['js' => 'js/cart.js', 'css' => 'css/cart.css'];

$this->title = '九大爷平台 - 购物车';
?>
<!--隐藏购物车-->
<style>[data-toggle='popover_cart']{display: none;}</style>

<!-- cart table start -->
<div class="container-fluid apx-cart-container">
    <div class="container">
        <div class="cart-none hidden">
            <p class="none-title">
                购物车空空的哦~，去看看心仪的商品吧~
            </p>
            <a href="/" class="none-go">去购物</a>
        </div>
        <div class="row have-items hidden">
            <h3 class="apx-cart-title text-danger"><strong>全部商品 <span></span></strong></h3>
            <table class="table J_list_box">
                <script type="text/template" id="J_tpl_table">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="apx_cart_checkAll">
                            </th>
                            <th width="108">
                                <label for="apx_cart_checkAll">全选</label>
                            </th>
                            <th>商品信息</th>
                            <th width="100">单价</th>
                            <th width="100">数量</th>
                            <th width="100">金额</th>
                            <th width="140">操作</th>
                        </tr>
                    </thead>
                    {@each items as it, index}  
                        <tbody>
                        <tr class="apx-cart-shop">
                            <td>
                                <input type="checkbox" class="J_checked_box" name="box${index}">
                            </td>
                            <td colspan="6">
                                <strong><a href="#">${it.supplier}</a></strong>
                            </td>
                        </tr>
                        {@each it.items as x}  
                            {@if x.sale_status != 1 || x.stock < 1}
                                <tr class="apx-cart-product expire J_id_box" data-id="${x.id}" data-stock="${x.stock}" data-price="${x.price*x.count}">
                                    <td>
                                    <input type="checkbox" disabled>
                            {@else if x.stock < x.count}
                                <tr class="apx-cart-product lack J_id_box" data-id="${x.id}" data-stock="${x.stock}" data-price="${x.price*x.count}">
                                    <td>
                                    <input type="checkbox" class="flag" name="box${index}">
                            {@else}
                                <tr class="apx-cart-product J_id_box" data-id="${x.id}" data-stock="${x.stock}" data-price="${x.price*x.count}">
                                    <td>
                                    <input type="checkbox" class="flag" name="box${index}">
                            {@/if}
                            </td>
                            <td>
                                <a href="/product?id=${x.product_id}"><img src="${x.image}" class="img-responsive"></a>
                            </td>
                            <td class="clearfix">
                                <div class="col-xs-6">
                                    <a href="/product?id=${x.product_id}">${x.title}</a>
                                </div>
                                <ul class="col-xs-6 list-unstyled">
                                    {@each x.attributes as y}
                                        <li>${y.name}：${y.selected_option.name}</li>
                                    {@/each}
                                </ul>
                            </td>
                            <td>¥ ${x.price|price_build}</td>
                            <td>
                                <div class="input-group input-group-sm J_input_box">
                                    <div class="input-group-addon J_number_minus">-</div>
                                    <input class="form-control J_only_int" readonly="readonly" value="${x.count}" maxlength="2" type="text">
                                    <div class="input-group-addon J_number_add">+</div>
                                </div>
                                <strong class="high-lighted">库存量 ${x.stock}</strong>
                            </td>
                            <td>
                                <strong class="J_end_price" data-price="${x.price}">¥ ${(x.price*x.count)|price_build}</strong>
                            </td>
                            <td>
                                <a href="javaScript:;" class="btn btn-block btn-xs btn-link J_product_del">删除</a>
                            </td>
                        </tr>
                        {@if x.sale_status != 1}
                            <tr class="apx-cart-product J_error_msg"  data-id="${x.id}">
                                <td colspan="7"><strong class="high-lighted">此商品已失效，请选购其他商品</strong></td>
                            </tr>
                        {@else if x.sale_status < 1}
                            <tr class="apx-cart-product J_error_msg"  data-id="${x.id}">
                                <td colspan="7"><strong class="high-lighted">此商品库存不足，请选购其他商品</strong></td>
                            </tr>
                        {@else if x.stock < x.count}
                            <tr class="apx-cart-product J_error_msg"  data-id="x.id">
                                <td colspan="7"><strong class="high-lighted">库存总量为${x.stock}件，请调整购买数量！！！</strong></td>
                            </tr>
                        {@/if}
                        {@/each}  
                        </tbody>
                    {@/each}
                </script>
            </table>
            <div class="apx-cart-bar J_cart_affix_bar">
                <div class="container">
                    <input type="checkbox" id="apx_cart_checkAll2">
                    <label for="apx_cart_checkAll2">全选</label>
                    <a href="javaScript:;" class="J_del_all">删除选中的商品</a>
                    <!-- <a href="#">移入收藏夹</a> -->
                    <div class="pull-right">
                        <span>已选中 <span class="high-lighted J_pro_number">1</span> 件商品</span>
                        <span>总价：<span class="high-lighted J_total_price"></span></span>
                        <a class="apx-cart-bar-submit J_go_buy" href="javaScript:;"><strong>去结算</strong></a>
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#tab_like" role="tab" data-toggle="tab">猜你喜欢
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="tab_like">
                <div id="J_like_box" class="apx-cart-product-slider carousel slide" data-ride="carousel">
                    <script type="text/template" id="J_tpl_like">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            {@each _ as it, index}
                                {@if index == 0}
                                    <div class="item clearfix active">
                                {@else}
                                    <div class="item clearfix">
                                {@/if}
                                {@each it.item as item}
                                    <div class="apx-item pull-left">
                                        <div class="item-pic">
                                            <a href="/product?id=${item.id}" target="_blank"><img src="${item.main_image}?x-oss-process=image/resize,w_200,h_200,limit_1,m_lfit" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="item-cnt">
                                            <div class="item-cnt-title ellipsis"><a href="/product?id=${item.id}" target="_blank">${item.title}</a></div>
                                            <div class="text-center">
                                                ¥${item.price.min}
                                            </div>
                                            <a href="/product?id=${item.id}" target="_blank" class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>立即购买</a>
                                        </div>
                                    </div>
                                {@/each}
                                    </div>
                            {@/each}
                        </div>
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            {@each _ as it, i}
                                {@if i == 0}
                                    <li data-target="#J_like_box" data-slide-to="${i}" class="active"></li>
                                {@else}
                                    <li data-target="#J_like_box" data-slide-to="${i}"></li>
                                {@/if}
                            {@/each}
                        </ol>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- cart table end -->
