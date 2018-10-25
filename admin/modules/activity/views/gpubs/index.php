<?php
$this->params = ['js' => 'js/index-gpubs.js', 'css' => 'css/index-gpubs.css'];
?>
<div class="admin-frame-main">
    <div class="admin-main-wrap">
        <div class="group-pro-manager-container">
            <div class="container-title">
                <span>拼购商品</span>
                <label for="">商品ID/商品标题：</label>
                <input type="text" name="" placeholder="请输入商品ID查询" id="search-ipt">
                <select id="searchType">
                    <option value="3">全部</option>
                    <option value="1">自提拼购</option>
                    <option value="2">送货拼购</option>
                </select>
                <span class="btn" id="search-btn">
                    <i class="glyphicon glyphicon-search"></i>
                </span>
            </div>
            <div class="jump-box">
                <a href="/activity/gpubs/create-gpubs" class="btn btn-danger">新增拼购商品</a>
            </div>
            <div class="group-pro-list-box">
                <table class="group-pro-list">
                    <thead>
                        <tr>
                            <th>活动编号</th>
                            <th>商品ID</th>
                            <th>拼购类型</th>
                            <th>商品缩略图</th>
                            <th width="250">商品标题</th>
                            <th width="180">成团规则</th>
                            <th width="150">标签</th>
                            <th>剩余库存量</th>
                            <th>拼团价格</th>
                            <th>状态</th>
                            <th width="250">操作</th>
                        </tr>
                    </thead>
                    <tbody style="min-height:800px" id="J_prolist_box">
                        <script type="text/template" id="J_tpl_list">
                            {@each _ as it, index}
                                <tr>
                                    <td>${it.gpubsProductId}</td>
                                    <td>${it.product_id}</td>
                                    <td>${it.gpubs_type == 1 ? '自提拼购' : '送货拼购'}</td>
                                    <td>
                                        <div class="img-box">
                                            <img src="${it.mainImage}" alt="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pro-title text-left">
                                            ${it.title}
                                        </div>
                                    </td>
                                    <td>
                                        {@if it.gpubs_type == 1}
                                            ${it.min_quantity_per_group}件
                                            {@else}
                                                {@if it.gpubs_rule_type == 1}
                                                    ${it.min_member_per_group}人
                                                {@/if}
                                                {@if it.gpubs_rule_type == 2}
                                                    ${it.min_quantity_per_group}件
                                                {@/if}
                                                {@if it.gpubs_rule_type == 3}
                                                    ${it.min_member_per_group}人每人${it.min_quanlity_per_member_of_group}件
                                                {@/if}
                                        {@/if}
                                    </td>
                                    <td><strong class="hotInfo">热门推荐</strong><span data-hot="${it.hot_recomment == 1 ? 2 : 1}" data-id="${it.product_id}" class="${it.hot_recomment == 1 ? 'hotSpan' : 'hotSpan ishot'}"><span></span></span></td>
                                    <td>${it.stockCount == null ? '0': it.stockCount}</td>
                                    <td>${it.price == null ? '0' : it.price}</td>
                                    <td data-status="${it.status}" class="openStatus">${it.status == 1 ? '启用':'禁用'}</td>
                                    <td>
                                        <div class="handle-box">
                                            <span data-isopen="${it.status == 1 ? 0 : 1}" data-id="${it.product_id}" class="btn btn-yellow btn-isOpen">${it.status == 1 ? '禁用' : '启用'}</span>
                                            <span data-index="${index}" data-id="${it.gpubsProductId}" class="btn btn-blue btn-toDetail">查看</span>
                                            <span data-index="${index}" data-id="${it.gpubsProductId}" class="${it.status == 1 ? 'btn btn-grey' : 'btn btn-green btn-toModify'}">修改</span>
                                        </div>
                                    </td>
                                </tr>
                            {@/each}
                        </script>

                        <script type="text/template" id="J_search_list">
                            {@each _ as it, index}
                                <tr>
                                    <td>${it.gpubsProductId}</td>
                                    <td>${it.product_id}</td>
                                    <td>${it.gpubs_type == 1 ? '自提拼购' : '送货拼购'}</td>
                                    <td>
                                        <div class="img-box">
                                            <img src="${it.mainImage}" alt="">
                                            <!-- <img src="/images/920.jpg" alt=""> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pro-title text-left">
                                            ${it.title}
                                        </div>
                                    </td>
                                    <td>
                                        {@if it.gpubs_type == 1}
                                            ${it.min_quantity_per_group}件
                                            {@else}
                                                {@if it.gpubs_rule_type == 1}
                                                    ${it.min_member_per_group}人
                                                {@/if}
                                                {@if it.gpubs_rule_type == 2}
                                                    ${it.min_quantity_per_group}件
                                                {@/if}
                                                {@if it.gpubs_rule_type == 3}
                                                    ${it.min_member_per_group}人+${it.min_quanlity_per_member_of_group}件
                                                {@/if}
                                        {@/if}
                                    </td>
                                    <td><strong class="hotInfo">热门推荐</strong><span data-hot="${it.hot_recomment == 1 ? 2 : 1}" data-id="${it.product_id}" class="${it.hot_recomment == 1 ? 'hotSpan' : 'hotSpan ishot'}"><span></span></span></td>
                                    <td>${it.stockCount == null ? '0': it.stockCount}</td>
                                    <td>${it.price == null ? '0' : it.price}</td>
                                    <td data-status="${it.status}" class="openStatus">${it.status == 1 ? '启用':'禁用'}</td>
                                    <td>
                                        <div class="handle-box">
                                            <span data-isopen="${it.status == 1 ? 0 : 1}" data-id="${it.product_id}" class="btn btn-yellow btn-isOpen">${it.status == 1 ? '禁用' : '启用'}</span>
                                            <span data-index="${index}" data-id="${it.gpubsProductId}" class="btn btn-blue btn-toDetail">查看</span>
                                            <span data-index="${index}" data-id="${it.gpubsProductId}" class="${it.status == 1 ? 'btn btn-grey' : 'btn btn-green btn-toModify'}">修改</span>
                                        </div>
                                    </td>
                                </tr>
                            {@/each}
                        </script>

                        <!-- <tr>
                            <td>1</td>
                            <td>123</td>
                            <td>拼购类型</td>
                            <td>
                                <div class="img-box">
                                    <img src="/images/920.jpg" alt="">
                                </div>
                            </td>
                            <td>
                                <div class="pro-title text-left">
                                    商品标题商品标题商品标题商品标题商品标题
                                </div>
                            </td>
                            <td>123</td>
                            <td>123123</td>
                            <td>78.00</td>
                            <td>启用</td>
                            <td>
                                <div class="handle-box">
                                    <span class="btn btn-green">启用</span>
                                    <span class="btn btn-yellow">查看</span>
                                    <span class="btn btn-danger">禁用</span>
                                </div>
                            </td>
                        </tr> -->

                    </tbody>
                </table>
            </div>
            <div class="text-right" id="J_coupon_page"></div>
        </div>
    </div>
</div>
