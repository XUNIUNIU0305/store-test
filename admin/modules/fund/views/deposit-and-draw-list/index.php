<?php
$this->params = ['css' => 'css/deposit-and-draw-list.css', 'js' => 'js/deposit-and-draw-list.js'];
?>
<div class="admin-main-wrap">
    <div class="text-right create-btn" style="transform: translateY(26px);">
        <a href="/fund/deposit-and-draw-application" class="btn btn-danger">制单</a>
    </div>
<div class="admin-deposit">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#order_status_1" data-toggle="tab" data-status="1">未审核</a>
        </li>
        <li>
            <a href="#order_status_2" data-toggle="tab" data-status="2">已审核</a>
        </li>
        <li>
            <a href="#order_status_3" data-toggle="tab" data-status="3">执行成功</a>
        </li>
        <li>
            <a href="#order_status_4" data-toggle="tab" data-status="4">执行失败</a>
        </li>
        <li>
            <a href="#order_status_5" data-toggle="tab" data-status="5">已取消</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="order_status_1">
            <!--table-->
            <table class="table table-hover table-panel table-fix text-center dashed">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">操作类型</th>
                        <th width="150">用户账号</th>
                        <th width="120">用户类型</th>
                        <th width="50">金额</th>
                        <th width="140">订单状态</th>
                        <th width="136">创建时间</th>
                        <th width="136">审核时间</th>
                        <th width="136">执行时间</th>
                        <th width="136">取消时间</th>
                    </tr>
                </thead>
                <tbody class="J_list_box">
                    
                </tbody>
            </table>
            <!-- pagination -->
            <div class="text-right J_page_box"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="order_status_2">
            <!--table-->
            <table class="table table-hover table-panel table-fix text-center dashed">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">操作类型</th>
                        <th width="150">用户账号</th>
                        <th width="120">用户类型</th>
                        <th width="50">金额</th>
                        <th width="140">订单状态</th>
                        <th width="136">创建时间</th>
                        <th width="136">审核时间</th>
                        <th width="136">执行时间</th>
                        <th width="136">取消时间</th>
                    </tr>
                </thead>
                <tbody class="J_list_box">
                    
                </tbody>
            </table>
            <!-- pagination -->
            <div class="text-right J_page_box"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="order_status_3">
            <!--table-->
            <table class="table table-hover table-panel table-fix text-center dashed">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">操作类型</th>
                        <th width="150">用户账号</th>
                        <th width="120">用户类型</th>
                        <th width="50">金额</th>
                        <th width="140">订单状态</th>
                        <th width="136">创建时间</th>
                        <th width="136">审核时间</th>
                        <th width="136">执行时间</th>
                        <th width="136">取消时间</th>
                    </tr>
                </thead>
                <tbody class="J_list_box">
                    
                </tbody>
            </table>
            <!-- pagination -->
            <div class="text-right J_page_box"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="order_status_4">
            <!--table-->
            <table class="table table-hover table-panel table-fix text-center dashed">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">操作类型</th>
                        <th width="150">用户账号</th>
                        <th width="120">用户类型</th>
                        <th width="50">金额</th>
                        <th width="140">订单状态</th>
                        <th width="136">创建时间</th>
                        <th width="136">审核时间</th>
                        <th width="136">执行时间</th>
                        <th width="136">取消时间</th>
                    </tr>
                </thead>
                <tbody class="J_list_box">
                    
                </tbody>
            </table>
            <!-- pagination -->
            <div class="text-right J_page_box"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="order_status_5">
            <!--table-->
            <table class="table table-hover table-panel table-fix text-center dashed">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">操作类型</th>
                        <th width="150">用户账号</th>
                        <th width="120">用户类型</th>
                        <th width="50">金额</th>
                        <th width="140">订单状态</th>
                        <th width="136">创建时间</th>
                        <th width="136">审核时间</th>
                        <th width="136">执行时间</th>
                        <th width="136">取消时间</th>
                    </tr>
                </thead>
                <tbody class="J_list_box">
                    
                </tbody>
            </table>
            <!-- pagination -->
            <div class="text-right J_page_box"></div>
        </div>
    </div>
</div>
</div>
<script type="text/template" id="J_tpl_list">
    {@each _ as it}
        <tr data-id="${it.id}" class="J_list_item">
            <td>${it.id}</td>
            <td>${it.operate_type | operateType}</td>
            <td>${it.user_account}</td>
            <td>${it.user_type | userType}</td>
            <td>${it.amount}</td>
            <td>${it.status | status}</td>
            <td>${it.create_time}</td>
            <td>${it.pass_time}</td>
            <td>${it.operate_time}</td>
            <td>${it.cancel_time}</td>
        </tr>
    {@/each}
</script>