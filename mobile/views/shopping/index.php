<?php
$this->params = ['js' => 'js/shopping-cart.js', 'css' => 'css/shopping-cart.css'];
$this->title = '九大爷平台 - 购物车';
?>
<nav class="bottom-nav bottom-nav-shopping">
    <span>购物车</span>
    <!-- <span class="shop-edit" data-edit="edit">编辑</span> -->
</nav>
<nav class="bottom-nav bottom-nav-shop-bottom">

    <span id="checkboxalls">
        <div class="shop-box">
            <input type="checkbox" name="" class="shop-checkbox" />
            <label class="check-alls myCheck"></label>
        </div>
        全选
    </span>
    <!--删除是注释下面span文字-->
    <span class="shop-she" style="display: none;"></span>
    <span class="shop-price">合计：<em>￥</em><em class="price-em">0</em></span>
    <span class="shop-settlement" data-sett="sett">去结算</span>
</nav>
<main class="container">
    <div class="wechat-shopping">
        <script type="text/template" id="J_shopping_list">
            {@each _ as it,index}
            <div class="shopping-list" id="checkbox${index}">
                <div class="shop-title clearfix">
                    <div class="shop-box shop-checknox" >
                        <input type="checkbox" name="check-all" class="shop-checkbox" />
                        <label class="check-all myCheck"></label>
                    </div>
                    ${it.supplier}
                    <span class="edit-btn" data-status="edit">编辑</span>
                </div>
                <ul>
                {@each it.items as x}
                    {@if x.stock < x.count}
                        <li class="no-more">
                    {@else}
                        <li>
                    {@/if}
                        <figure class="shopping-item">
                            <div class="shop-box shop-box-mycheck">
                                <input type="checkbox" name="unit-box" class="shop-checkbox" />
                                {@if x.sale_status === 2 || x.stock < x.count}
                                    <label data-attributes="${x.attributes|first_build}" data-id="${x.id}" data-price="${x.price}" data-count="${x.count}" data-stock="${x.stock}" data-productid="${x.product_id}" data-skuid="${x.product_sku_id}"></label>
                                {@else}
                                    <label class="unit-box myCheck" data-attributes="${x.attributes|first_build}" data-id="${x.id}" data-price="${x.price}" data-count="${x.count}" data-stock="${x.stock}" data-productid="${x.product_id}" data-skuid="${x.product_sku_id}"></label>
                                {@/if}
                            </div>
                            <aside>
                                <img src="${x.image}">
                                {@if x.sale_status === 2}
                                    <div class="lose">
                                          已下架
                                    </div>
                                {@/if}
                            </aside>
                            {@if x.sale_status !== 2}
                            <main class="shop-main mian-view" data-stock="${x.stock}">
                            {@else}
                            <main class="shop-main mian-view no" data-stock="${x.stock}">
                            {@/if}
                                 <div class="description">${x.title}</div>
                                 <div class="param"><span>${x.attributes|first_build}</span></div>
                                 <div class="price"><span>￥${x.price}</span></div>
                                 <span class="ammount">${x.count}</span>
                            </main>
                            {@if x.sale_status !== 2}
                                <main class="shop-main hidden main-edit" data-count="${x.count}" data-stock="${x.stock}" data-attributes="${x.attributes|first_build}">
                                    <div class="input-group">
                                        <span class="addon minu-btn" data-id="${x.id}"></span>
                                        <span class="num">${x.count}</span>
                                        <span class="addon add-btn" data-id="${x.id}"></span>
                                    </div>
                                    <div class="param-edit" data-pid="${x.product_id}" data-attr="${x.attributes|attributesId}" data-id="${x.id}">
                                        <span>${x.attributes|first_build}</span>
                                    </div>
                                </main>
                            {@/if}
                        </figure>
                        {@if x.sale_status === 2}
                            <div class="del-btn no" data-id="${x.id}">
                                <p>删除</p>
                            </div>
                        {@else}
                            <div class="del-btn hidden" data-id="${x.id}">
                                <p>删除</p>
                            </div>
                        {@/if}
                        <div class="more-tip">
                            <p>库存不足，仅剩<span>${x.stock}</span>件，请修改数量</p>
                        </div>
                    </li>
                {@/each}
                </ul>
            </div>
            {@/each}
        </script>
    </div>
</main>
<div id="maskLayersh"  class="maskLayersh maskProDetail J_buy_option" style="display:none;">
    <div class="maskLayersh-shop">
        <div class="maskLayersh-shop-top">
            <figure class="commodity-item">
                <aside>
                    <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4xLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyNTAgMjUwIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNTAgMjUwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDojREQwMDMxO30NCgkuc3Qxe2ZpbGw6I0MzMDAyRjt9DQoJLnN0MntmaWxsOiNGRkZGRkY7fQ0KPC9zdHlsZT4NCjxnPg0KCTxwb2x5Z29uIGNsYXNzPSJzdDAiIHBvaW50cz0iMTI1LDMwIDEyNSwzMCAxMjUsMzAgMzEuOSw2My4yIDQ2LjEsMTg2LjMgMTI1LDIzMCAxMjUsMjMwIDEyNSwyMzAgMjAzLjksMTg2LjMgMjE4LjEsNjMuMiAJIi8+DQoJPHBvbHlnb24gY2xhc3M9InN0MSIgcG9pbnRzPSIxMjUsMzAgMTI1LDUyLjIgMTI1LDUyLjEgMTI1LDE1My40IDEyNSwxNTMuNCAxMjUsMjMwIDEyNSwyMzAgMjAzLjksMTg2LjMgMjE4LjEsNjMuMiAxMjUsMzAgCSIvPg0KCTxwYXRoIGNsYXNzPSJzdDIiIGQ9Ik0xMjUsNTIuMUw2Ni44LDE4Mi42aDBoMjEuN2gwbDExLjctMjkuMmg0OS40bDExLjcsMjkuMmgwaDIxLjdoMEwxMjUsNTIuMUwxMjUsNTIuMUwxMjUsNTIuMUwxMjUsNTIuMQ0KCQlMMTI1LDUyLjF6IE0xNDIsMTM1LjRIMTA4bDE3LTQwLjlMMTQyLDEzNS40eiIvPg0KPC9nPg0KPC9zdmc+DQo=" class="J_buy_img">
                </aside>
                <main>
                    <div class="description"><span class="J_product_price"></span><em></em></div>
                    <div class="param">库存 <span class="J_product_inventory"></span>件</div>
                    <div class="param">已选：<span class="J_buy_attribute"></span></div>
                </main>
            </figure>
        </div>
        <div class="goods_attr">
            <div class="goods_attr_items">
                
            </div>
            <div class="maskLayersh-num">
                <p class="mask-num">数量</p>
                <div class="num-add-sub">
                    <div id="subtract"></div>
                    <div class="input-number" id="number">1</div>
                    <div id="add"></div>
                </div>
            </div>
        </div>
        <div class="maskLayersh-but">
            <button class="but-cancel-datermine J_buy_cancel">取消</button>
            <button class="but-cancel-datermine J_buy_submit">确定</button>
        </div>
    </div>
</div>
<script type="text/template" id="J_tpl_buy_attribute">
  {@each _ as it,index}
   <div class="maskLayersh-classification J_attr_box">
      <span data-id="${it.id}">${it.name}</span>
      <ul>
          {@each it.options as option,index2}
          <li attr_id="${option.id}">
              <input type="radio" name="attr${index}" calss="J_attr_input${index}" value="${index2}" autocomplete="off">
              ${option.name}</li>
          {@/each}
      </ul>
  </div>
  {@/each}
</script>