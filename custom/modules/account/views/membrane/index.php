<?php
$this->params = ['js' => 'js/membrane.js', 'css' => 'css/membrane.css'];

$this->title = '九大爷平台 - 交易中心';
?>
<div class="cus-container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="J_tab_list">
        <li role="presentation" class="active" data-id="1"><a href="#home" role="tab" data-toggle="tab">未付款</a></li>
        <li role="presentation" data-id="2"><a href="#home" role="tab" data-toggle="tab">已付款</a></li>
        <li role="presentation" data-id="3"><a href="#profile" role="tab" data-toggle="tab">已接单</a></li>
        <li role="presentation" data-id="4"><a href="#messages" role="tab" data-toggle="tab" >已完成</a></li>
        <li role="presentation" data-id="5"><a href="#settings" role="tab" data-toggle="tab">已取消</a></li>
    </ul>
     <div class="order-select">
           <h2 id="ceshi">订单筛选</h2>
           <div class="order-msg">
               <ul class="clearfix">
                   <li>
                       <label>订单号：</label>
                       <input type="text" name="no">
                   </li>
                   <li> 
                       <label>购买账号：</label>
                       <input type="text" name="account" class="long-length">
                   </li>  
                   <li class="msg-time">
                       <label>下单时间：</label>
                       <input type="text" class="date-picker" name="created_start">
                       <i class="time-line"></i>
                       <input type="text" class="date-picker" name="created_end">        
                   </li> 
                   <li>
                       <label>收货人：</label>
                       <input type="text" name="receive_name">
                   </li>
                   <li>
                       <label>收货地址：</label>
                       <input type="text" name="receive_address" class="long-length">
                   </li> 
               </ul>  
               <span class="btn" id="J_search_btn">查询</span>
           </div>
     </div>  
     <div class="order-content">
        <div role="tabpanel" class="tab-pane fade in active" id="tab_all">
            <table class="table table-fix top-table">
                <tr>
                    <td class="pro-detail" width="335">商品详情</td>
                    <td width="100" class="text-center">单价</td>
                    <td width="186">用户备注</td>
                    <td width="220">收货人信息</td>
                    <td width="110">状态</td>
                </tr>
            </table>
            <div  id="tableBox">
	            	 <script type="text/template" id="tpl_order">
	            	      {@each items as it,index}
	            			<table class="table table-bordered text-center" id="table_order">
	                      		<thead>
				                    <tr>
				                        <td colspan="7" class="no-border row text-left top-msg">
				                            <strong class="text-left order-msg" >
				                                <span>订单编号：<span class="order_num">${it.no}</span></span>
				                                <span class="orser-time">下单时间：${it.createdDate}</span>
				                            </strong>
				                        </td>
				                    </tr>
				                </thead>
				                <tbody>
				                {@each it.items as item}
				                    <tr class="tr-content">
				                        <td class="row acc-media-box " id="des-content">
				                            <div class="col-xs-8 ">
				                                <div class="media text-left">
				                                    <a class="media-left media-middle" href="#">
				                                        <img src="${item.membrane_product_id === 1 ? '/images/membrane/product/ty.png' : '/images/membrane/product/apex.jpg'}">
				                                    </a>
				                                    <div class="media-body media-middle">
				                                        <div class="descipe-txt">${item.name}</div>
				                                        <ul class="list-unstyled text-muted list-attr">
				                                        	{@each item.attributes as attribute}
													            <li>${attribute.block} ${attribute.type}</li>
				                                        	{@/each}
				                                        </ul>
				                                    </div>
				                                </div>
				                            </div>
				                        </td>
				                        <td width="99" class="pro-price">${item.price}</td>
				                        <td width="188" class="text-left">
				                            ${it.remark}
				                        </td>
				                        <td width="220" class="text-left txt-msg">
				                            <div class="person"><i></i>${it.receiveName}</div>
				                            <div class="address"><i></i>${it.receiveAddress}</div>
				                        </td>
				                        <td width="110">
				                        {@if it.status===1}
                                            <div class="acc-pay-again J_table_box">
                                                <a class="collapsed" data-toggle="collapse" href="#collapsePayAgain${index}" aria-controls="#collapsePayAgain${index}">去付款
                                                    <i class="glyphicon glyphicon-chevron-up"></i>
                                                </a>                                           
                                                <div class="collapse-box collapse J_collapse_box" id="collapsePayAgain${index}">    
                                                    <div class="J_payment_box">
                                                        <div class="radio">            
                                                            <label><input type="radio" name="payment${index}" value="1"/>余额</label>
                                                        </div>
                                                        <div class="radio">            
                                                            <label><input type="radio" name="payment${index}" checked value="2"/>支付宝</label>
                                                        </div>
                                                        <div class="radio">            
                                                            <label><input type="radio" name="payment${index}" value="6"/>农行-网银</label>
                                                        </div>
                                                    </div>                                         
                                                    <div class="input-group-btn">         
                                                        <button type="button" class="btn btn-xs btn-danger J_to_pay" data-no="${it.no}">付款</button>
                                                        <button type="button" class="btn btn-xs btn-default" data-toggle="collapse" href="#collapsePayAgain${index}" aria-expanded="true">取消</button>
                                                    </div>                                      
                                                </div>                                      
                                            </div>
                                        {@else if it.status===2}
                                            <p>已付款</p>
                                            <p style="cursor: pointer;" class="J_cancel_order" data-no="${it.no}">取消订单</p>
										{@else if it.status===3}
										  <p>已接单</p>
										{@else if it.status === 4}
										    <p>已完成</p>
                                        {@else if it.status == 5}
                                            <p>已取消</p>
										{@/if}
            				            </td>
				                    </tr>
				                {@/each}
				                </tbody>
	            			</table>
				          {@/each}
	            	 </script>
            </div>
        </div>
    </div>
    <div class="text-right" id="cus_page_list">
</div>
</div>
<script>
  

</script>
