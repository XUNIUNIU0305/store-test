<?php
$this->params =['js' => ['js/share.js'], 'css' =>  ['css/share.css']];// ['js' => ['js/inviting_friends.js'],['css/inviting_friends.css']
$this->title = '';
?>

<style>
    main.container {
        background-color: #f4f5f7;
    }
</style>
<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>
<!--top nav-->
<nav class="top-nav">
    <div class="title">
        拼购详情
    </div>
</nav>

<div class="wechat-share-modal hidden">
    <img src="/images/share.png" alt="">
</div>
<!--main container-->
<main class="container">
    <!--confirm order-->
    <div id="topAnchor"></div>
    <div class="group-activity-detail-container">
        <div class="pro-info">
            <div class="info-header">
                <div class="info-header-centenr">
                    <div class="info-header-pic"></div>
                    <div class="user-name"><span id="G_group-share-user-name"></span>的团</div>
                    <div class="pro-num">
                        <p class="num">团编号：<span  id="G_group-share-pro-num"></span></p>
                        <p class="start-num"><span id="G_group-share-pro-way"></span><span class="pro-start-num"></span>起拼</p>
                    </div>
                </div>
            </div>
            <div class="pro-content">
                <div class="group-order-item">
                    <div class="aside">
                        <img src="">
                    </div>
                    <div class="main">
                        <div class="group-pro-price"><div class="description" id="G_group-share-main-description"></div></div>
                        <div class="group-price">
                            <em id="G_group-share-group-price"></em>拼团价:
                            <i class="lengthlongbr">
                                <span>￥</span>
                                <em class="group-price-meny group-price-meny1"></em>
                                <span class="group-price-menyZero group-price-menyZero1"></span>
                                <em class="group-price-meny group-price-meny3"></em>
                                <em class="group-price-meny group-price-meny2"></em>
                                <span class="group-price-menyZero group-price-menyZero2"></span>
                            </i>
                        </div>
                        <div class="param">
                            单独购买价：￥
                            <span class="G_group-share-main-param1"></span>
                            <span class="G_group-share-main-param2"></span>
                            <span class="G_group-share-main-param3"></span>
                        </div>
                    </div>
                </div>
                <div class="pro-offer">
                    <em class="pro-offer1"></em>
                    <i id="G_group-share-pro-offer"></i>
                </div>
            </div>
        </div>
        <div class="activity-info">
            <div class="balance-pro-group">
                <!-- 拼购时 -->
                <div class="balance-pro hidden">
                    仅剩
                    <span class="balance-pro-remain" id="G_group-share-balance-pro"></span>，剩余时间
                    <span class="balance-pro-d"></span>
                    <span class="balance-pro-h">00</span>：
                    <span class="balance-pro-m">00</span>：
                    <span class="balance-pro-s">00</span>
                    拼购成功
                </div>
                <!-- 拼购失败 -->
                <div class="balance-pro-failure hidden">
                    <div><span></span>拼购失败</div>
                    <p>未能按时完成拼购，款项将原路返还</p>
                </div>
                <!-- 拼购失效 -->
                <div class="balance-pro-Invalid hidden">
                    <div><span></span>拼购已失效</div>
                    <p>您来迟了，该拼购已失效！</p>
                </div>
                <!-- 参团失败 -->
                <div class="balance-pro-abnormal hidden">
                    <div><span></span>参团失败</div>
                    <p>款项支付异常，拼购失败，前往<a href="/member/gpubs-order/index">我的拼购</a></p>
                </div>
                <!-- 拼购成功 -->
                <div class="balance-pro-success hidden">
                    <div><span></span>拼购成功</div>
                    <p>您能在【我的拼购】中查看详情，前往<a href="/member/gpubs-order/index">我的拼购</a></p>
                </div>
            </div>

            <!-- <div class="participates-group"> -->
                <div class="participates">
                    <ul class="user-list">
                        <!--  参加拼购时头像边框user-list-pics -->
                        <li class="user-head G_group-share-user-head-img0 G_group-share-user-portrait ">
                            <div class="user-head-tuanzhang"><img src="/images/8-13/tuanzhang.svg" alt=""></div>
                            <!-- <div class="user-head-tuanzhang"></div> -->
                        </li>
                        <li class="G_group-share-user-head-img1 G_group-share-user-portrait "></li>
                        <li class="G_group-share-user-head-img2 G_group-share-user-portrait "></li>
                        <li class="G_group-share-user-head-img3 G_group-share-user-portrait "></li>
                        <!-- 多于5人时last-user-pic -->
                        <li class="G_group-share-user-head-img4 G_group-share-user-portrait "></li>
                    </ul>
                </div>
            <!-- </div> -->
            <div class="participates-group">
                <div class="handle-box  hidden" id="G_group-share-handle-box">
                    邀请好友参团
                </div>
                <div class="handle-box1 hidden" id="G_group-share-handle-box1">
                    我要参团
                </div>
                <div class="handle-box2 hidden" id="G_group-share-handle-box2">
                    更多火热拼购正在进行中...
                </div>
                <div class="handle-box3 hidden" id="G_group-share-handle-box3">
                    再开一团
                </div>
                <div class="handle-box4 hidden" id="G_group-share-handle-box4">
                    我要开团
                </div>
            </div>
        </div>

        <!-- 拼购规则 -->
        <div class="activity-rule">
            <div class="activity-rule-info">
                拼购规则
                <span>查看详情<img src="/images/8-13/erer.png" alt=""></span>
            </div>
            <div class="activity-process">
                <div>
                    <span>1</span>参团/开团</div>
                <div>
                    <img src="/images/8-13/arrows_24_icon.png" alt="">
                </div>
                <div>
                    <span>2</span>邀请好友</div>
                <div>
                    <img src="/images/8-13/arrows_24_icon.png" alt="">
                </div>
                <div>
                    <span>3</span>拼购成功</div>
            </div>
        </div>
        <!-- 自提点地址 -->
        <div class="activity-address G_group-share-activity-address hidden" id="G_group-share-activity-address">
            <div>自提点信息</div>
            <address class="activity-address-point"></address>
            <p>
                <span class="activity-address-name"></span>
                <span class="activity-address-tel"></span>
            </p>
            <address class="activity-detial-address"></address>
        </div>
        <div class="border-address hidden"><img src="/images/8-13/border.png" alt=""></div>

        <!--热门推荐 -->
        <div class="hot-pro">
            <div class="hot-pro-tit">—热门推荐—</div>
            <!--热门推荐列表 -->
            <ul id="hot-pro-allinfo-wrap">
            </ul>
            <script id="groupHotProList" type="text/template">
                {@each data as it,index}
                <li class="hot-pro-allinfo">
                    <div class="hot-pro-img">
                        <img class="hot-pro-pic" src="${it.filename}" alt="">
                    </div>
                    <div class="hot-pro-info">
                        <div class="hot-pro-description">${it.title}</div>
                        <div class="hot-pro-price">
                            <span>￥</span>
                            {@if it.min_price.toString().indexOf('.') == -1}
                                <em class="hot-pro-newpri">${it.min_price}.00</em>
                                {@else}
                                <em class="hot-pro-newpri">${it.min_price}</em>
                            {@/if}
                            <s class="hot-pro-oldpri">
                            {@if it.max_price.toString().indexOf('.') == -1}
                                ￥${it.max_price}.00
                                {@else}
                                ￥${it.max_price}
                            {@/if}
                            </s>
                        </div>
                        <div class="hot-pro-ways">
                            {@if it.gpubs_type == 1}
                                <span class="hot-pro-way">自提</span>
                            {@else if it.gpubs_type == 2}
                               <span class="hot-pro-way">送货</span>
                            {@/if}
                            <span class="hot-pro-way-pic">
                                {@if it.gpubs_rule_type == 1 || it.gpubs_rule_type == 3}
                                    <span class="hot-pro-buypic"></span>
                                {@else if it.gpubs_rule_type == 2}
                                    <span class="hot-pro-buypic1"></span>
                                {@/if}

                                {@if it.gpubs_rule_type == 1}
                                    <span class="hot-pro-bugway">${it.min_member_per_group}人拼</span>
                                {@else if it.gpubs_rule_type == 2}
                                    <span class="hot-pro-bugway">${it.min_quantity_per_group}件拼</span>
                                {@else if it.gpubs_rule_type == 3}
                                    <span class="hot-pro-bugway">${it.min_member_per_group}人${it.min_quanlity_per_member_group}件起拼</span>
                                {@/if}
                            </span>
                            <a href="/goods/detail?id=${it.product_id}"><span class="hot-pro-gogroup">去开团</a>
                        </div>
                    </div>
                </li>
                {@/each}
            </script>
            <div id="group-hot-pro-container"></div>

        </div>
        <div class="group-activity-detail-footer">———小九，是有底线的呐~———</div>

<!-- 规格详情 -->
<div class="specifications-detail-wrap hidden" id="specifications-detail-wrap">
			<div class="specifications-detail">
				<div class="specifications-detail-mess">
					<div class="detail-img">
						<img src="/images/group_goods_detail/goods_detail_img.png" alt="">
					</div>
					<div class="detail-mess">
						<div class="close" id="specifications-detail-wrap-close"></div>
						<!-- 起拼-送货 -->
						<div class="spell-delivery">
							<!-- 起拼按钮 -->
							<div class="spell same">
							</div>
							<!-- 送货按钮 -->
							<div class="delivery same">
								<!-- 送货 -->
							</div>
						</div>
						<p class="detail-newprice"><span class="new-price"><span class="min-price"></span><span class="max-price"></span>
							</span>
						</p>
						<p class="detail-oldprice">
							<span class="old-price"><span class="min-price"></span><span class="max-price"></span>
							</span>
							<!-- 库存 -->
							<span class="inventory">库存 <span class="inventory-num" id="inventory-num"></span> 件</span>
						</p>
					</div>
					<!-- 请选择规格-颜色 -->
					<div class="choose-specifications-color">
						<!-- 请选择 -->
						<span class="please-choose">请选择：</span>
						<!-- 已选择 -->
						<span class="yet-choose"></span>
					</div>
				</div>
				<!-- 具体规格及颜色数量 -->
				<div class="detail-main">
					<!-- 规格及数量 -->
					<div class="standard same" id="standard">
						<script type="text/template" id="J_tpl_buy_attribute">
							{@each _ as it,index}
							<div class="standard-title title" id="standard-title">
								<span data-id="${it.id}">${it.name}</span>
								<ul class="item" data-id="${it.id}">
									{@each it.options as option,index2}
									<li attr_id="${option.id}" product_sku_id="${option.id}">
										${option.name}</li>
									{@/each}
								</ul>
							</div>
							{@/each}
						</script>
					</div>
					<!-- 颜色 -->
					<!-- 数量 -->
					<div class="number same">
						<div class="number-title title">
							<span>数量</span>
						</div>
						<div class="number-button">
							<div class="button">
								<span class="reduce" id="deliver-goods-reduce"></span>
								<input class="text" id="deliver-goods-text" type="text" value="1" maxlength="3">
								<span class="add" id="deliver-goods-add"></span>
							</div>
						</div>
					</div>
				</div>

				<div class="footer">
					<div class="confirm" id="confirm" status="1">确定</div>
				</div>
			</div>
		</div> 
    </div>
</main>
<!-- 邀请好友 -->
<div class="G_group-share-activity-invite hidden">
    <img src="/images/8-13/share.png" alt="">
</div>
<!-- 快速导航列表 -->
<div class="fast-guid-list hidden" id="fast-guid-list"> </div>
<div class="guid-list" id="guid-list">
    <div class="pack-up" id="pack-up"></div>
    <ul class="list">
        <li class="pingou-index"></li>
        <li class="my-pingou"></li>
        <li class="ziti-pingou-tihuo"></li>
    </ul>
</div>
<!-- 返回顶部 -->
<a href="#topAnchor"><div href="#topAnchor" class="G_group-share-back-top"></div></a>