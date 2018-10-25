<?php
$this->params = ['css' => 'css/quality.css', 'js' => 'js/detail.js'];
?>
<div class="business-main-wrap">
    <div class="row">
        <div class="business-warranty-container">
            <!--车主信息-->
            <div class="warranty-title">车主信息</div>
            <div class="form-horizontal clearfix">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">车主姓名：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_owner_name">九大爷</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">电话号码：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_owner_telephone">68888888</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">手机号码：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_owner_mobile">13818868888888</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">电子邮箱：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_owner_email">jiudaye@9daye.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="col-xs-1 control-label" for="">车主地址：</label>
                        <div class="col-xs-11">
                            <p class="form-control-static J_owner_address">上海市宝山区三门路123号</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--产品信息-->
            <div class="warranty-title">产品信息</div>
            <div class="quality_list">
                <table class="table table-bordered table-fix text-center table-striped">
                    <thead>
                    <tr>
                        <th width="80">序号</th>
                        <th width="300">管芯号</th>
                        <th width="150">质保卡号</th>
                        <th>产品</th>
                        <th width="150">施工部位</th>
                        <th width="120">导购</th>
                        <th width="120">技师</th>
                    </tr>
                    </thead>
                    <tbody id="J_pro_list">
                    <script type="text/template" id="J_tpl_list">
                        {@each goods as it, index}
                        <tr>
                            <td>${index - 0 + 1}</td>
                            <td>${it.code}</td>
                            <td  class="J_quality_code"></td>
                            <td >${it.package_name}</td>
                            <td>${it.place_name}</td>
                            <td>${it.sales}</td>
                            <td>${it.technician}</td>
                        </tr>
                        {@/each}
                    </script>
                    </tbody>
                </table>
            </div>
            <!--车信息-->
            <div class="warranty-title">车信息</div>
            <div class="form-horizontal clearfix">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">车牌号码：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_car_number"></p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">车架号码：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_car_frame"></p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">车&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;型：</label>
                        <div class="col-xs-10">
                            <p class="form-control-static J_type_name"></p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">车价格：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_car_price_range"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!--施工信息-->
            <div class="warranty-title">施工信息</div>
            <div class="form-horizontal clearfix">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">施工单位：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_shop_name">上海某某门店</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">施工日期：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_construct_date">1979-12-28</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">产品总价：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_price">¥15800.00</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">完成日期：</label>
                        <div class="col-xs-6">
                            <p class="form-control-static J_finished_date">1979-12-28</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 hidden">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">技&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;师：</label>
                        <div class="col-xs-5">
                            <p class="form-control-static J_technician">张三</p>
                        </div>
                    </div>
                </div>

            </div>
            <a href="/quality/qualityorder/index" class="btn btn-lg btn-business btn-block">返回</a>
        </div>
    </div>
</div>
