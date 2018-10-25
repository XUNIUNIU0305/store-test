<?php
$this->params = ['js' => 'js/gpubs-management.js', 'css' => 'css/gpubs-management.css'];
$this->title = '';
?>
<div class="admin-main-wrap">
    <div class="apx-admin-group-order">
        <!--查询导航-->
        <div class="nav-bar">
            <div class="form-inline">
                <div class="form-group">
                    <label for="J_group_type">活动编号：</label>
                    <input type="number" id="activity_gpubs_id" oninput="if(value.length>10)value=value.slice(0,10)" />
                </div>
                <div class="form-group">
                    <label for="J_group_type">拼团类型：</label>
                    <select id="J_group_type" class="form-control">
                        <option value="-1">全部</option>
                        <option value="1">自提</option>
                        <option value="2">送货</option>
                    </select>
                </div>
                <div class="form-group area">
                    <label for="J_group_province">区域：</label>
                    <select id="J_group_province" class="form-control">
                        <option value="-1">全部</option>
                    </select>
                    <select id="J_group_city" class="form-control">
                        <option value="-1">全部</option>
                    </select>
                    <select id="J_group_three" class="form-control">
                        <option value="-1">全部</option>
                    </select>
                    <select id="J_group_four" class="form-control">
                        <option value="-1">全部</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="select_status">拼购状态：</label>
                    <select id="J_group_status" class="form-control">
                        <option value="-1">全部</option>
                        <option value="1">进行中</option>
                        <option value="2">拼购成功</option>
                        <option value="3">拼购失败</option>
                        <option value="-2">强制成团成功</option>
                    </select>
                </div>

                <button class="btn btn-danger" id="J_search_btn">查询</button>
            </div>
        </div>
        <div class="order-status-nav">
            <div class="nav-item">
                <div class="item-title">拼购团总数</div>
                <div class="item-num">
                    <span class="J_total_quantity"></span>团
                </div>
            </div>
            <div class="nav-item">
                <div class="item-title">拼购进行中</div>
                <div class="item-num">
                    <span class="J_wait_quantity"></span>团&nbsp;&nbsp;&nbsp;&nbsp;<span class="J_wait_total_fee"></span>元
                </div>
            </div>
            <div class="nav-item">
                <div class="item-title">拼购成功</div>
                <div class="item-num">
                    <span class="J_establish_quantity"></span>团
                </div>
            </div>
            <div class="nav-item">
                <div class="item-title">拼成总量</div>
                <div class="item-num">
                    <span class="J_establish_product_quantity"></span>件
                </div>
            </div>
            <div class="nav-item">
                <div class="item-title">拼成总金额</div>
                <div class="item-num">
                    <span class="J_establish_total_fee"></span>元
                </div>
            </div>
        </div>
        <div class="order-list-container" style="overflow-x:auto">
            <table style="min-width:1200px">
                <thead>
                    <tr>
                        <th width="120">活动编号</th>
                        <th width="120">团编号</th>
                        <th width="120">拼购类型</th>
                        <th width="180">拼购商品</th>
                        <th>团长账号</th>
                        <th>收货人</th>
                        <th width="180">所在区域</th>
                        <th>拼购发起时间</th>
                        <th>拼购截止时间</th>
                        <th>活动截止时间</th>
                        <th>已拼数量</th>
                        <th>成团数量</th>
                        <th width="120">已拼金额（元）</th>
                        <th>拼购状态</th>
                        <th width="120">操作</th>
                    </tr>
                </thead>
                <tbody id="J_list_box">
                    <script type="text/template" id="J_tpl_list">
                        {@each _ as it}
                            <tr>
                                <td>${it.activity_gpubs_id}</td>
                                <td>${it.group_number}</td>
                                <td>${it.gpubs_type === 1? '自提拼购' : '送货拼购'}</td>
                                <td>${it.product_title}</td>
                                <td>${it.owner_account}</td>
                                <td>${it.owner_name}</td>
                                <td>
                                    ${it.area.business_top_area_id.name}/
                                    ${it.area.business_secondary_area_id.name}/
                                    ${it.area.business_tertiary_area_id.name}/
                                    ${it.area.business_quaternary_area_id.name}
                                </td>
                                <td>
                                    <div class="time">
                                        ${it.group_start_datetime}
                                    </div>
                                </td>
                                <td>
                                    <div class="time">
                                        ${it.group_end_datetime}
                                    </div>
                                </td>
                                <td>
                                    <div class="time">
                                        ${it.activity_end_datetime}
                                    </div>
                                </td>
                                {@if it.gpubs_type == 1}
                                    <td>${it.present_quantity}件</td>
                                    <td>${it.target_quantity}件</td>
                                {@/if}
                                {@if it.gpubs_type == 2}
                                    {@if it.gpubs_rule_type == 2}
                                        <td>${it.present_quantity}件</td>
                                        <td>${it.target_quantity}件</td>
                                    {@/if}
                                    {@if it.gpubs_rule_type == 1}
                                        <td>${it.present_member}人</td>
                                        <td>${it.target_member}人</td>
                                    {@/if}
                                    {@if it.gpubs_rule_type == 3}
                                        <td>${it.present_member}人+${it.present_quantity}件</td>
                                        <td>${it.target_member}人+${it.min_quanlity_per_member_of_group}件/人</td>
                                    {@/if}
                                {@/if}
                                <td>${it.total_fee}</td>
                                <td>${it|status}</td>
                                <td>
                                    {@if it.status === 1}
                                    <span class="handle text-warning" data-id="${it.id}">强制成团</span>
                                    {@/if}
                                </td>
                            </tr>
                        {@/each}
                    </script>
                </tbody>
            </table>
        </div>
        <!--pagination-->
        <div class="pull-right" id="J_page_box">
            
        </div>
    </div>
</div>
