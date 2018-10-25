<?php
$this->params = ['js' => 'js/gpubs-picking-up.js', 'css' => 'css/gpubs-picking-up.css'];
$this->title = '';
?>
<div class="custom-group-cancel-main">
    <div class="cancel-header">
        <div class="top-title">拼购提货</div>
        <div class="handle-box">
            <div class="input-group">
                <label for="">提货：</label>
                <input type="text" name="" id="J_pick_up_input" placeholder="请输入提货码" >
            </div>
            <span class="btn btn-danger" id="J_pick_up" >提货</span>
        </div>
    </div>
    <div class="group-cancel-container">
        <div class="form-box row">
            <div class="col-xs-6">
                <div class="input-group">
                    <label>订单状态：</label>
                    <select name="" class="order-status" id="J_order_status">
                        <option value="">全部</option>
                        <option value="2">未提货</option>
                        <option value="3">部分提货</option>
                        <option value="4">全部提货</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="input-group">
                    <label>拼购编号：</label>
                    <input type="text" name="" id="J_order_no" placeholder="请输入拼购编号" class="input-middle" >
                </div>
            </div>
            <div class="col-xs-6 time-form">
                <span>提货时间：</span>
                <div class="input-group time-box">
                    <input type="text" class="form-control date-picker J_search_timeStart" value="">
                </div>
                <span> 至 </span>
                <div class="input-group time-box">
                    <input type="text" class="form-control date-picker J_search_timeEnd" value="">
                </div>
            </div>
            <div class="col-xs-6">
                <div class="input-group">
                    <label>门店账号：</label>
                    <input type="text" name="" placeholder="请输入门店账号" id="J_account_no" class="input-middle" >
                </div>
            </div>
            <div class="col-xs-12 text-center">
                <span class="btn btn-danger" id="J_search_btn">搜索</span>
            </div>
        </div>
        <div class="group-order-list">
            <table>
                <thead>
                    <tr>
                        <th>拼购编号</th>
                        <th>商品名称</th>
                        <th>门店账号</th>
                        <th>数量</th>
                        <th>提货日期</th>
                        <th>状态</th>
                    </tr>
                </thead>
                <tbody id="J_list_container">
                    
                </tbody>
            </table>
        </div>
        <div id="J_page_box" class="text-right"></div>
    </div>
</div>

<div class="apx-modal-admin-alert custom-group-order-cancel-modal modal fade" id="apxModalAdminAlertReject" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提货明细</h4>
            </div>
            <div class="modal-body">
                <div class="pro-info">
                    <table>
                        <tbody>
                            <tr>
                                <td width="260">商品</td>
                                <td width="200">属性</td>
                                <td>订单总量</td>
                                <td>可提取数量</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="img-title">
                                        <div class="img-box">
                                            <img id="J_pick_img" alt="">
                                        </div>
                                        <div class="title-box" id="J_pick_title">
                                            
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="attr-box" id="J_pick_attr">
                                        
                                    </div>
                                </td>
                                <td id="J_order_total"></td>
                                <td id="J_order_balance"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="num-box">
                    <span>提取数量：</span>
                    <div class="input-group">
                        <div class="input-group-addon" id="J_minus_num">-</div>
                        <input class="form-control" id="J_pick_num_input" value="1" title="请输入数量" type="text">
                        <div class="input-group-addon" id="J_add_num">+</div>
                    </div>
                </div>
                <div class="text-center">
                    <span class="btn btn-danger" id="J_pick_up_btn">提取</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="J_tpl_list">
    {@each _ as it}
        <tr>
            <td>${it.group_number}</td>
            <td>
                <div class="pro-name">${it.product_title}</div>
            </td>
            <td>${it.custom_user_account}</td>
            <td>${it.picked_up_quantity}/${it.total_quantity}</td>
            <td>${it.last_pick_up_datetime}</td>
            <td>${it.status|status}</td>
        </tr>
    {@/each}
</script>