<?php
$this->params = ['js' => 'js/shop.js', 'css' => 'css/shop.css'];
?>
<div class="apx-shop-head container-fluid">
    <div class="container">
        <div class="row">
            <!-- detail -->
            <div class="media">
                <div class="media-left media-middle" id="J_media_img">
                    <div class="media-left-inner"><img src="/images/shell_logo.jpg" class="img-responsive" /></div>
                </div>
                <div class="media-body media-middle" id="J_media_p">
                    <div class="media-heading" id="J_media_strong">

                    </div>
                    <!--<p>主营品牌：APEX/欧帕斯</p>-->
                    <p>所在地：上海/上海</p>
                </div>
                <div class="apx-shop-score">
                    <strong>店铺动态评分</strong>
                    <ul class="list-unstyled">
                        <li>描述相符：<strong class="high-lighted">4.8</strong><i class="glyphicon glyphicon glyphicon-arrow-up text-danger small"></i></li>
                        <li>描述相符：<strong class="high-lighted">4.8</strong><i class="glyphicon glyphicon glyphicon-arrow-up text-danger small"></i></li>
                        <li>描述相符：<strong class="high-lighted">4.8</strong><i class="glyphicon glyphicon glyphicon-arrow-up text-danger small"></i></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div id="J_shop_banner">
    		<script type="text/templates" id="J_shop_list">
    		<div class="apx-shop-banner row">
    		<div class="main-banner">
                <div class="main-item" id="J_shop_item" onmouseover="mouseover()" onmouseout="mouseout()">

                </div>
            </div>
            <div class="sub-banner" id="J_sub_banner">
                {@each small as it,index}
                    {@if it.id==""}
                        <div class="sub-item">
                                <div class="label" style="background: ${index|first_build}">${index|shop_build}</div>
                                <a href="#">
                                     <img src="" class="img-responsive img_left" />
                                </a>
                        </div>
                    {@else}
                        <div class="sub-item">
                               <div class="label" style="background: ${index|first_build}">${index|shop_build}</div>
                               <a href="${it.image_url}" target="_blank">
                                   <img src="${it.image_path}" class="img-responsive img_left" />
                               </a>
                        </div>
                    {@/if}
                {@/each}
              </div>
    		</div>
    		 <div class="row apx-shop-sub-banner" id="J_apx_shop_sub_shop">
                  {@each sub as it}
                   {@if it.id==""}
                       <a href="#" class="col-xs-4"><img src=""/></a>
                   {@else}
                       <a href="${it.image_url}" class="col-xs-4"  target="_blank"><img src="${it.image_path}"/></a>
                   {@/if}
                  {@/each}
            </div>
    		</script>
    	</div>
    <div class="row">
        <!--标题-->
        <div class="apx-shop-carousel-title">
            <div class="list-title">
                <div class="pull-left">ai</div>
                <p>热销推荐</p>
                <span>all item</span>
            </div>
        </div>
        <!--商品-->
        <div class="apx-shop-product-slider">
            <!-- Wrapper for slides -->
            				<!-- Wrapper for slides -->
            				<div id="carousel-example-generic" class="carousel slide">
            						<div class="carousel-inner" role="listbox">
            							<div class="item clearfix active J_carousel_shop">
            							<script type="text/templates" id="J_Product_list">
            								{@each _ as it}
                                                <div class="apx-item pull-left">
                                                    <div class="item-pic">
                                                        <img src="${it.mainImage}" alt="" class="img-responsive" />
                                                    </div>
                                                    <div class="item-cnt">
                                                        <div class="item-cnt-title ellipsis"><a href="product?id=${it.id}" target="_blank">${it.title}</a></div>
                                                        <div class="text-center">
                                                            ¥${it.price.min}
                                                        </div>
                                                        <a href="product?id=${it.id}" target="_blank"><button class="add-cart">查看详情</button></a>
                                                    </div>
                                                </div>
            								{@/each}
            							</script>
            							</div>
            							<div class="footer clearfix">
                                             <div class="text-right col-xs-12" id="J_shop_page">

                                             </div>
                                        </div>
            				 </div>

            				</div>
            <!-- Wrapper for slides -->
        </div>
    </div>
</div>