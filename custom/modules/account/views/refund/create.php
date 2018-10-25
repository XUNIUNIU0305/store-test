<?php
$this->params = ['js' => 'js/refund_create.js', 'css' => 'css/refund_create.css'];

$this->title = '九大爷平台 - 账户中心 - 提交申请退换货';
?>

<!--after sales container-->
<div class="apx-after-sales-container">
    <div class="h3">售后服务</div>
    <!--choose amount-->
    <div class="panel panel-default">
        <div class="panel-heading">
            选择商品数量
            <small class="pull-right high-lighted">*提示：定制类商品不参加退货流程</small>
        </div>
        <div class="panel-body">
            <table class="table text-center">
                <thead>
                    <tr>
                        <td width="410"><span class="col-xs-12 text-left">商品信息</span></td>
                        <td></td>
                        <td width="120">单价</td>
                        <td width="120">商品数量</td>
                        <td width="120">金额</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="row acc-media-box">
                            <div class="col-xs-12 ">
                                <div class="media text-left">
                                    <a class="media-left media-middle J_product_url" href="#">
                                        <img src="" class="J_product_img">
                                    </a>
                                    <div class="media-body media-middle J_product_title"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <ul class="list-unstyled text-left J_product_attr"></ul>
                        </td>
                        <td>
                            ¥ <span class="J_product_price"></span>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <div class="btn input-group-addon J_input_minus">-</div>
                                <input class="form-control J_only_int" value="1" maxlength="3" type="text" onfocus="this.select()">
                                <div class="btn input-group-addon J_input_add">+</div>
                            </div>
                        </td>
                        <td>
                            ¥ <strong class="text-danger J_total_price"></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--reasons and photos-->
    <div class="panel panel-default">
        <div class="panel-heading">提交退换货原因</div>
        <div class="panel-body">
            <form role="form">
                <div class="form-group">
                    <label for="description">描述：</label>
                    <textarea rows="4" class="form-control" id="description" maxlength="200" placeholder="请详细输入您遇到的问题，以便工作人员进行审核"></textarea>
                </div>
                <div class="form-group">
                    <label>请上传问题照片：<span class="pull-right high-lighted">*只支持2M以下的jpg、jpeg、png、gif格式图片</span></label>
                    <div class="gallary">
                        <div id="J_img_box">
                        </div>
                        <label class="upload-btn btn btn-danger">
                        添加照片
                        <input type="file" id="upload_pic">
                    </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--submit btn-->
    <a class="btn btn-danger btn-block btn-lg" id="J_submit_order">提交退换货申请</a>
</div>