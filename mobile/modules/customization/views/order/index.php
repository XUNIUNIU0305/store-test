<?php 
/** 
 * @var $this \yii\web\View 
 */ 
use mobile\modules\customization\assets\Asset; 
$this->title = '定制上传'; 
Asset::register($this)->addJs('js/order.js')->addCss('css/order.css'); 
?> 
<main class="container"> 
    <div class="custom_order_mobile"> 
        <div id="status-span" class="customization-nav"> 
            <nav> 
                <span data-status="1" class="C_order_info">未上传</span> 
                <span data-status="2" class="C_order_info">未处理</span> 
                <span data-status="3,4,5,6" class="C_order_info">已处理</span> 
            </nav> 
        </div> 
        <div class="nav_content"> 
            <span data-status="3,4,5,6" class="content_info active_content_nav">全部 
                  <i class="selected_icon"></i> 
            </span> 
            <span data-status="3" class="content_info">生产中 
                   <i class="selected_icon"></i> 
            </span> 
            <span data-status="4" class="content_info">已发货 
                   <i class="selected_icon"></i> 
            </span> 
            <span data-status="5" class="content_info">已拒绝 
                  <i class="selected_icon"></i> 
            </span> 
            <span data-status="6" class="content_info">已取消 
                  <i class="selected_icon"></i> 
            </span> 
        </div> 
        <p class="old-tip top">旧定制订单请咨询客服 4000318119</p>
           <ul class="order_list" id="list_box"> 
                <script id="order_tpl" type="text/template"> 
                    {@each items as it} 
                      <li data-order_number="${it.order_no}"> 
                        <div class="list_top"> 
                          <span class="top_left"> 
                            <i class="icon_skew"></i>${it.brand_name}
                          </span> 
                          <span class="pay_time">订单号：${it.order_no}</span> 
                        </div> 
                        <div class="list_content"> 
                            <div class="pro_img"> 
                               <img src="${it.product.image}" alt="" width="100" height="100"> 
                               <div class="img_mask"> 
                                    {@if it.customization == 1}
                                        未上传
                                    {@else if it.customization == 2}
                                        未处理
                                    {@else if it.customization == 3}
                                        生产中
                                    {@else if it.customization == 4}
                                        已发货 
                                    {@else if it.customization == 5}
                                        已拒绝 
                                    {@else if it.customization == 6}
                                        已取消 
                                    {@/if}
                              </div> 
                            </div> 
                          <div class="text"> 
                            <p>${it.product.title}</p> 
                                    <p class="pro_attr"> 
                                       {@each it.product.attributes as attribute} 
                                              <span>${attribute.option}</span> 
                                        {@/each} 
                                    </p> 
                            <p class="pro_price">￥${it.product.total_fee}</p> 
                          </div> 
                        </div> 
                        <div class="list_btn"> 
                                {@if it.customization===1} 
                                    <a href="/customization/order/view?order_number=${it.order_no}" class="sub_order">上传</a> 
                                    <a href="#" class="cancel_order">取消订单</a> 
                                {@else if it.customization===2} 
                                    <a href="/customization/order/view?order_number=${it.order_no}">修改</a> 
                                    <a href="#" class="cancel_order">取消订单</a> 
                                {@else}
                                    <a href="/customization/order/view?order_number=${it.order_no}">查看订单</a> 
                                {@/if}
                        </div> 
                      </li> 
                    {@/each} 
                </script> 
          </ul> 
     </div> 
</main>