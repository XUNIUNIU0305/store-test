<?php
$this->params = ['js' => 'js/brand.js', 'css' => 'css/brand.css'];
?>
<div class="apx-brands-container container-fluid">
    <div class="container">
        <div class="row">
            <!-- carousel -->
            <div id="apx-brands-carousel" class="apx-brands-carousel carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">

                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">

                </div>
            </div>
            <!--热销品牌-->
            <div class="apx-brands-box">
                <div class="brands-title">热销品牌</div>
                <div class="media">
                    <div class="media-left" id='media_left_img'>
                        <a href="#" target="_blank"><img /></a>
                    </div>
                    <div class="media-body" id="J_media-body">
                        <!--item-->
                        <script type="text/templates" id="J_Hotbrand_list">
                             {@each _ as it}
                              <div class="brand-item-wrap">
                                   <a href="${it.url}" target="_blank">
                                        <div class="brand-item">
                                               <img src="${it.path}" class="img-responsive img-hotbrand-img">
                                        </div>
                                   </a>
                              </div>

                             {@/each}
                                  <div class="brand-item-wrap">
                                      <div class="refresh-btn J_brands_refresh" id="J_for_a_change">
                                          <i class="glyphicon glyphicon-refresh"></i>
                                             <span>换一批</span>
                                       </div>
                                   </div>
                         </script>
                        <!--item-->
                        <!--<div class="brand-item-wrap">
                            <a href="#">
                                <div class="brand-item">
                                    <div class="logo">
                                        <img src="/images/brands/brand1.png" class="img-responsive">
                                    </div>
                                    <p>御甲</p>
                                </div>
                            </a>
                        </div>-->
                        <!--refresh btn-->
                        <!--<div class="brand-item-wrap">
                            <div class="refresh-btn J_brands_refresh">
                                <i class="glyphicon glyphicon-refresh"></i>
                                <span>换一批</span>
                            </div>
                        </div>-->
                    </div>
                </div>
                <div class="banner" id='J_banner_img'>
                    <a href="#" target="_blank"><img src="" class="img-responsive img-responsive-img"></a>
                </div>
            </div>
            <!--品牌特辑-->
            <div class="apx-brands-box clearfix">
                <div class="brands-title">商品推荐</div>
                <div id="J_Edit">
                <script type="text/templates" id="J_Edit_list">
                      {@each _ as it}
                       <div class="col-xs-6">
                             <a href="${it.url}" target="_blank"><img src="${it.background_path}" class="img-responsive img-ebit-adjustment"></a>
                             <div class="text-box">
                                  <div class="logo">
                                       <img src="${it.logo_path}" class="img-responsive ebitmin-img">
                                  </div>
                                  <span class="h3">${it.title}</span>
                                  <p>${it.introduction}</p>
                             </div>
                       </div>
                      {@/each}
                 </script>
                </div>
            </div>
        </div>
    </div>
</div>

