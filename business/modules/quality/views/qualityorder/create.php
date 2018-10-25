<?php
$this->params = ['css' => 'css/quality.css', 'js' => ['js/date.js','js/create.js']];
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
                            <input type="text" class="form-control" id="J_user_name"  maxlength="12">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">电话号码：</label>
                        <div class="col-xs-2">
                            <input type="text" class="form-control" id="J_area_code" maxlength="4">
                        </div>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="J_user_telephone" maxlength="8">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">手机号码：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="J_user_mobile" maxlength="11">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">电子邮箱：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control"  id="J_user_email">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="col-xs-1 control-label" for="">车主地址：</label>
                        <div class="col-xs-11">
                            <input type="text" class="form-control" id="J_user_address" maxlength="50">
                        </div>
                    </div>
                </div>
            </div>
            <!--产品信息-->
            <div class="warranty-title">产品信息</div>
            <div class="quality_list">
                <table class="table table-bordered table-fix text-center">
                    <thead>
                    <tr>
                        <th width="80">序号</th>
                        <th width="300">管芯号</th>
                        <th>产品</th>
                        <th width="200">施工部位</th>
                        <th width="140">技师</th>
                        <th width="140">导购</th>
                    </tr>
                    </thead>
                    <tbody id="J_pro_box">
                    <tr class="J_pro_info">
                        <td>1</td>
                        <td>
                            <input type="text" class="form-control J_round_num">
                        </td>
                        <td>
                            <select class="selectpicker J_package_list" data-width="100%">
                                <option value="">请选择</option>

                            </select>
                        </td>
                        <td>
                            <select class="selectpicker J_place_list" data-width="100%">
                                <option value="">请选择</option>

                            </select>
                        </td>
                        <td>
                            <select class="selectpicker J_technician_list" data-width="100%">
                                <option value="">请选择</option>

                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control J_sales_list" maxlength="12"> 
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="text-right">
                <a href="javascript:void(0)" id="J_minus_pro" class="btn btn-round">-</a>
                <a href="javascript:void(0)" id="J_add_pro" class="btn btn-round">+</a>
            </div>
            <!--车信息-->
            <div class="warranty-title">车辆信息</div>
            <div class="form-horizontal clearfix">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">车牌号码：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control"  id="J_user_code">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">车架号码：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control"  id="J_user_frame" maxlength="25">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="">车&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;型：</label>
                        <div class="col-xs-5">
                            <select class="selectpicker" id="J_brand_list"  data-width="100%">
                                <option value="">请选择</option>

                            </select>
                        </div>
                        <div class="col-xs-5">
                            <select class="selectpicker" id="J_type_list"  data-width="100%">
                                <option value="">请选择</option>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">车价格区间：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="J_car_price">
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
                            <input type="text" class="form-control" id="J_shop_name" name="">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">施工日期：</label>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <input type="text" class="form-control date-picker J_search_timeStart" value="2017-03-01">
                                <span class="input-group-btn J_date_btn">
                                <a class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></a>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 control-label" for="J_pro_price">产品总价：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="J_pro_price">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-xs-2 col-xs-offset-4 control-label" for="">完成日期：</label>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <input type="text" class="form-control date-picker J_search_timeEnd" value="2017-03-01">
                                <span class="input-group-btn J_date_btn">
                                <a class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></a>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--J_submit_quality-->
            <button class="btn btn-lg btn-business btn-block J_reset_button " data-toggle="modal" data-target="#modalWarrantyAdd">保存</button>
        </div>
    </div>

    <!--提示信息-->
    <div class="apx-modal-warranty modal fade" id="modalWarrantyAdd" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content J_warring">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <div class="modal-title" id="myModalLabel">
                        <strong><span class="h3">提示信息</strong>
                    </div>
                </div>
                <div class="modal-body">
                    <p class="text-center h4">请确认录入信息是否准确，确定后不可修改</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger " id="J_submit_quality">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>


            <div class="modal-content result J_result_content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <div class="modal-title" id="myModalLabel">
                        <strong><span class="h3">提示信息</strong>
                    </div>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        <span class="h4">信息录入成功，质保卡已生成，卡号如下：<br></span>
                        <span class="h2 text-danger">895789451232</span>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="J_end">确定</button>
                </div>
            </div>
        </div>
    </div>

</div>

