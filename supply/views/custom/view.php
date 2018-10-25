<?php
/**
 * @var $this \yii\web\View
 */
$this->params = ['js' => 'js/customDetail.js', 'css' => 'css/customDetail.css'];
?>
<!-- 定制订单详情 -->
<div class="supplyListDetail-container" id="contain_box">
    <div class="detail_msg">
		    <!-- 订单状态 -->
		    <div class="order-status">
                <div class="order-top clearfix">
                     <h2>定制状态：<span class="J_order_status"></span></h2>
                     <h3>订单状态：<span class="J_order_status"></span></h3>
                     <h3>付款方式：<span id="J_pay_ment"></span></h3>
                </div>
                <ul class="order-inform clearfix">
                    <li class="clearfix">
                        <img src="" id="J_info_img" alt="">
                        <div class="info-txt">
                            <h3 id="J_info_title"></h3>
                            <p>属性：<span id="J_info_attr"></span></p>
                            <p>单价：￥<span id="J_info_price"></span></p>
                        </div>
                    </li>
                    <li class="order-txt">
                        <p><span>订单号：</span><span id="J_info_no"></span></p>
                        <p><span>生成时间：</span><span id="J_create_time"></span></p>
                        <p><span>付款时间：</span><span id="J_pay_time"></span></p>
                        <p><span>优惠券：<span id="J_coupon_price">无</span></span></p>
                        <p><span>已退款：<span id="J_refund_price"></span></span></p>
                    </li>
                    <li class="order-txt">
                        <p><span>收货人：</span><span id="J_info_name"></span></p>
                        <p><span>收货电话：</span><span id="J_info_mobile"></span></p>
                        <p><span>收货地址：</span><span id="J_info_address"></span></p>
                        <p><span class="express hidden">快递公司：</span><span id="J_express_name"></span></p>
                        <p><span class="express hidden">快递单号：</span><span id="J_express_code"></span></p>
                    </li>
                </ul>
                <p class="info-text">
                   <span>订单备注：</span><span id="J_info_remark"></span>
                </p>
            </div>
		    <!-- 车信息 -->
            <div class="car-inform clearfix">
                 <div class="car-txt car-wrap">
                      <div class="car-txt-fir">
                           <p>
                              <strong class="car-str">用户车型品牌：</strong>
                              <span id="J_car_brand"></span>
                           </p>
                           <p>
                              <strong class="car-str">用户车型车系：</strong>
                              <span id="J_car_type"></span>
                           </p>
                           <p>
                              <strong>定制信息上传时间：</strong>
                              <span id="J_upload_time"></span>
                           </p>
                           <p class="car-txt-time">
                              <strong>定制信息修改时间：</strong>
                              <span id="J_edit_time"></span>
                           </p>
                      </div>
                      <div class="car-txt-sec">
                            <strong>用户特殊备注</strong>
                            <p id="J_customer_remark"></p>

                      </div>
                      <div class="car-txt-thr">
                           <strong>厂家特殊备注（60个汉字）</strong>
                           <div class="input-text">
                                <select name="" id="" class="hidden">
                                      <option value="">快速回复</option>
                                </select>
                                <textarea name="" id="J_supply_remark" maxlength="60" placeholder="填写备注"></textarea>
                                <p>还可以输入<strong class="input-last">60</strong>个字</p>
                           </div>
                      </div>
                 </div>
		         <div class="car-pic car-wrap clearfix">
                    <div class="img-box" id="J_img_box">
                    </div>
                    <button class="car-btn hidden">下载全部图片</button>
                 </div>
	        </div>
    </div>
    <footer class="footer-box">
	    <p class="high-lighted hidden">*此处提示文字</p>
	    <div class="remark hidden">
	        <span class="sub_mark btn J_submit_remark" data-title="提交备注" data-toggle="modal" data-target="#customizationConfirm">提交备注</span>
	    </div>
	    <div class="handle hidden" id="J_reject">
	          <span class="reject btn" data-toggle="modal" data-target="#customizationConfirm" data-title="拒绝">拒绝</span>
	          <span class="agree btn" data-toggle="modal" data-target="#customizationConfirm" data-title="接单">接单</span>
	    </div>
      <div class="handle hidden" id="J_over">
            <span class="reject btn J_submit_remark" data-title="提交备注" data-toggle="modal" data-target="#customizationConfirm">提交备注</span>
            <span class="agree btn" data-toggle="modal" data-target="#customizationOrder" data-title="发货">发货</span>
      </div>
	</footer>

</div>
<!-- 操作 -->
<div class="apx-modal-deal-order modal fade" id="customizationOrder" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close J_close_btn" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-title" id="myModalLabel">
                    <strong><span class="h3">订单号 : </span><span class="J_order_no"></span></strong>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="logisticName" class="col-xs-3 text-right control-label">物流公司：</label>
                        <div class="col-xs-3 choose-express" style="width: 267.16px">
                          <input type="text" />
                          <select class="form-control"><option value="-1">选择物流</option></select>
                          <div style="width: 237.16px;"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="express_code" class="col-xs-3 text-right control-label">物流单号：</label>
                        <div class="col-xs-8 cash-after">
                            <input type="text" id="express_code" placeholder="请输入物流单号后确认发货" class="form-control" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="J_shipments">确认发货</button>
            </div>
        </div>
    </div>
</div>
<!-- 确定弹窗 -->
<div class="apx-modal-admin-alert modal fade" id="customizationConfirm" tabindex="-1">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header">
              <a type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span></a>
              <h4 class="modal-title">提示信息</h4>
          </div>
          <div class="modal-body">
              <i class="glyphicon glyphicon-warning-sign"></i>
              <span class="J_confrim_content">此操作无法撤回，确定要执行<span></span>吗？</span>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-lg btn-danger" id="J_sure_btn">确认</button>
              <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
          </div>
      </div>
  </div>
</div>
<div class="apx-modal-admin-alert modal fade" id="customizationAlert" tabindex="-1">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header">
              <a type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span></a>
              <h4 class="modal-title">提示信息</h4>
          </div>
          <div class="modal-body">
              <i class="glyphicon glyphicon-warning-sign"></i>
              <span class="J_confrim_content"></span>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">确定</button>
          </div>
      </div>
  </div>
</div>
