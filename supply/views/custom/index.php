<?php
/**
 * @var $this \yii\web\View
 */
$this->params = ['js' => 'js/custom.js', 'css' => 'css/custom.css'];
?>
<div class="customization-list">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="J_tab_box">
        <li class="active">
            <a href="#untreated" data-toggle="tab" data-status="1">未上传</a>
        </li>
        <li>
            <a href="#uploaded" data-toggle="tab" data-status="2">已上传</a>
        </li>
        <li>
            <a href="#masking" data-toggle="tab" data-status="3">生产中</a>
        </li>
        <li>
            <a href="#transition" data-toggle="tab" data-status="4">已发货</a>
        </li>
        <li>
            <a href="#reject" data-toggle="tab" data-status="5">已拒绝</a>
        </li>
        <li>
            <a href="#close" data-toggle="tab" data-status="4" data-close="1">已关闭</a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="untreated">
            <div class="item-select item-active">
                <h2>订单筛选</h2>
                <ul class="item-msg">
                    <li>
                        <label>订单号：
                            <input type="text" name="order_number" class="first-input">
                        </label>
                        <label>购买账号：
                            <input type="text" name="buy_account">
                        </label>
                        <label>付款时间：
                            <input type="text" class="date-picker time-input" name="pay_date_start">
                            <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="pay_date_end">
                        </label>
                    </li>
                    <li>
                        <label>收货人：
                            <input type="text" name="buy_person" class="first-input">
                        </label>
                        <label>收货地址：
                            <input type="text" name="address">
                        </label>
                        <label class="last-label">
                            <button class="btn J_search_btn">搜索</button>
                            <button class="btn print-btn">导出订单</button>
                        </label>
                    </li>
                    </ul>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="uploaded">
            <div class="item-select">
                    <h2>订单筛选</h2>
                    <ul class="item-msg">
                        <li>
                            <label>订单号：
                                 <input type="text" name="order_number" class="first-input">
                            </label>
                            <label>购买账号：
                                 <input type="text" name="buy_account">
                            </label>
                            <label>付款时间：
                                 <input type="text" class="date-picker time-input" name="pay_date_start">
                                 <i class="time-line"></i>
                                 <input type="text" class="date-picker time-input" name="pay_date_end">
                            </label>
                        </li>
                        <li>
                            <label>收货人：
                                 <input type="text" name="buy_person" class="first-input">
                             </label>
                            <label>收货地址：
                                 <input type="text" name="address">
                           </label>
                            <label>上传时间：
                                 <input type="text" class="date-picker time-input" name="pay_date_start">
                                 <i class="time-line"></i>
                                 <input type="text" class="date-picker time-input" name="pay_date_end">
                            </label>
                        </li>
                        <li>
                            <label>修改时间：
                                <input type="text" class="date-picker time-input" name="update_date_start">
                                <i class="time-line"></i>
                                <input type="text" class="date-picker time-input" name="update_date_end">
                            </label>
                            <label class="last-label">
                                <button class="btn J_search_btn">搜索</button>
                                <button class="btn print-btn">导出订单</button>
                            </label>
                        </li>
                    </ul>
               </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="masking">
            <div class="item-select">
                <h2>订单筛选</h2>
                <ul class="item-msg">
                    <li>
                        <label>订单号：
                              <input type="text" name="order_number" class="first-input">
                        </label>
                        <label>购买账号：
                              <input type="text" name="buy_account">
                        </label>
                        <label>付款时间：
                             <input type="text" class="date-picker time-input" name="pay_date_start">
                             <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="pay_date_end">
                        </label>
                    </li>
                    <li>
                        <label>收货人：
                              <input type="text" name="buy_person" class="first-input">
                        </label>
                        <label>收货地址：
                              <input type="text" name="address">
                        </label>
                        <label class="last-label">
                            <button class="btn J_search_btn">搜索</button>
                            <button class="btn print-btn">导出订单</button>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="transition">
            <div class="item-select">
                <h2>订单筛选</h2>
                <ul class="item-msg">
                    <li>
                        <label>订单号：
                             <input type="text" name="order_number" class="first-input">
                        </label>
                        <label>购买账号：
                             <input type="text" name="buy_account">
                        </label>
                        <label>付款时间：
                             <input type="text" class="date-picker time-input" name="pay_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="pay_date_end">
                        </label>
                    </li>
                    <li>
                        <label>收货人：
                             <input type="text" name="buy_person" class="first-input">
                         </label>
                        <label>收货地址：
                             <input type="text" name="address">
                       </label>
                        <label>上传时间：
                             <input type="text" class="date-picker time-input" name="upload_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="upload_date_end">
                        </label>
                    </li>
                    <li>
                        <label>修改时间：
                             <input type="text" class="date-picker time-input" name="update_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="update_date_end">
                        </label>
                        <label>发货时间：
                             <input type="text" class="date-picker time-input" name="send_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="send_date_end">
                        </label>
                        <label class="choose-express" style="position: relative;">物流公司：<select></select>
                          <input style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" type="text" />
                          <div style="position: absolute; bottom: 0; right: 0; width: 200px;"></div>
                        </label>
                    </li>
                    <li>
                        <label>物流单号：
                            <input type="text" name="express_number">
                        </label>
                        <label class="last-label">
                            <button class="btn J_search_btn">搜索</button>
                            <button class="btn print-btn">导出订单</button>
                        </label>
                    </li>
                </ul>
           </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="reject">
            <div class="item-select">
                <h2>订单筛选</h2>
                <ul class="item-msg">
                    <li>
                        <label>订单号：
                             <input type="text" name="order_number" class="first-input">
                        </label>
                        <label>购买账号：
                             <input type="text" name="buy_account">
                        </label>
                        <label>付款时间：
                             <input type="text" class="date-picker time-input" name="pay_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="pay_date_end">
                        </label>
                    </li>
                    <li>
                        <label>收货人：
                             <input type="text" name="buy_person" class="first-input">
                         </label>
                        <label>收货地址：
                             <input type="text" name="address">
                       </label>
                        <label>上传时间：
                             <input type="text" class="date-picker time-input" name="upload_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="upload_date_end">
                        </label>
                    </li>
                    <li>
                        <label>修改时间：
                             <input type="text" class="date-picker time-input" name="update_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="update_date_end">
                        </label>
                        <label>发货时间：
                             <input type="text" class="date-picker time-input" name="send_date_start">
                             <i class="time-line"></i>
                             <input type="text" class="date-picker time-input" name="send_date_end">
                        </label>
                        <label class="choose-express" style="position: relative;">物流公司：<select></select>
                          <input style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" type="text" />
                          <div style="position: absolute; bottom: 0; right: 0; width: 200px;"></div>
                        </label>
                    </li>
                    <li>
                        <label>物流单号：
                            <input type="text" name="express_number">
                        </label>
                        <label>拒绝时间：
                            <input type="text" class="date-picker time-input" name="reject_date_start">
                            <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="reject_date_end">
                        </label>
                        <label class="last-label">
                            <button class="btn J_search_btn">搜索</button>
                            <button class="btn print-btn">导出订单</button>
                        </label>
                    </li>
                </ul>
           </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="close">
            <div class="item-select">
                <h2>订单筛选</h2>
                <ul class="item-msg">
                    <li>
                        <label>订单号：
                            <input type="text" name="order_number" class="first-input">
                        </label>
                        <label>购买账号：
                            <input type="text" name="buy_account">
                        </label>
                        <label>付款时间：
                            <input type="text" class="date-picker time-input" name="pay_date_start">
                            <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="pay_date_end">
                        </label>
                    </li>
                    <li>
                        <label>收货人：
                            <input type="text" name="buy_person" class="first-input">
                        </label>
                        <label>收货地址：
                            <input type="text" name="address">
                        </label>
                        <label>上传时间：
                            <input type="text" class="date-picker time-input" name="upload_date_start">
                            <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="upload_date_end">
                        </label>
                    </li>
                    <li>
                        <label>修改时间：
                            <input type="text" class="date-picker time-input" name="update_date_start">
                            <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="update_date_end">
                        </label>
                        <label>发货时间：
                            <input type="text" class="date-picker time-input" name="send_date_start">
                            <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="send_date_end">
                        </label>
                        <label class="choose-express" style="position: relative;">物流公司：<select></select>
                          <input style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" type="text" />
                          <div style="position: absolute; bottom: 0; right: 0; width: 200px;"></div>
                        </label>
                    </li>
                    <li>
                        <label>物流单号：
                            <input type="text" name="express_number">
                        </label>
                        <label>关闭时间：
                            <input type="text" class="date-picker time-input" name="close_date_start">
                            <i class="time-line"></i>
                            <input type="text" class="date-picker time-input" name="close_date_end">
                        </label>
                        <label class="last-label">
                            <button class="btn J_search_btn">搜索</button>
                            <button class="btn print-btn">导出订单</button>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="order-list">
        <ul class="order-items" id="J_order_list"></ul>
        <!--panigation-->
        <div class="pull-right" id="J_list_page"></div>
    </div>
</div>
<script id="J_tpl_list" type="text/template">
    {@each items as item}
        <li class="order-item">
            <div class="order-top clearfix">
               <div class="order-topCont">
                    <span>订单号：${item.order_no}</span>
                    <span>下单时间：${item.create_time}</span>
                    <span>付款时间：${item.pay_time}</span>
                    <span>付款方式：${item.pay_method}</span>
               </div>
                <a href="/custom/view?order_number=${item.order_no}" target='_blank'>查看更多<i>&gt;</i></a>
                <a style="font-size: 12px;margin-right: 15px;color: #000" target="_blank" href="/print?order_id=${item.order_no}">打印</a>
            </div>
            <ul class="order-main">
                <li>
                    <div class="order-first order_wrap">
                       <img src="${item.product.image}" alt="">
                       <div class="img-right">
                            <h3>${item.product.title}</h3>
                            <p class="order-color">
                                {@each item.product.attributes as attribute}
                                    <span>${attribute.option}</span>
                                {@/each}
                            </p>
                            <p class="order-money">￥${item.product.price}</p>
                       </div>
                    </div>
                    <div class="order_address order_wrap">
                       <p title="${item.address}"><label>收货地址：</label>${item.address}</p>
                       <p><span class="buy_ps">收货人： </span>${item.consignee}</p>
                       <p><span>收货电话：</span>${item.mobile}</p>
                   </div>
                </li>
                <li>
                   <div class="order_wrap order_mask">
                        <h2>客户特别备注：</h2>
                        <p>${item.customization.notes|isCustom}</p>
                   </div>
                   <div class="order_wrap order_mask">
                        <h2>厂家特别备注：</h2>
                        <p>${item.customization.notes|isSupply}</p>
                   </div>
                </li>
            </ul>
        </li>
    {@/each}
</script>
