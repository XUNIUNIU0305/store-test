<?php
$this->params = ['js' => 'js/auth-detail.js', 'css' => 'css/auth-detail.css'];
?>
<div class="admin-main-wrap">
    <div class="admin-audit-qualify-container">
        <!--nav-->
        <ul class="nav nav-tabs datepicker-nav">
            <li><strong>审核资质</strong></li>
        </ul>
        <div class="row">
            <!--form start-->
            <form class="form-horizontal col-xs-6" role="form">
                <div class="info-title"><p>申请人信息</p></div>
                <div class="form-group ">
                    <label class="col-xs-2" for="shop_name">门店名称：</label>
                    <div class="col-xs-10">
                        <div class="form-control-static" id="J_store_name"></div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-xs-2" for="company_name">公司名称:</label>
                    <div class="col-xs-10">
                        <div class="form-control-static" id="J_corp_name"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2" for="">门店所属区域：</label>
                    <div class="col-xs-8">
                        <div class="form-control-static" id="J_district"></div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-xs-2" for="address">门店具体地址:</label>
                    <div class="col-xs-10">
                        <div class="form-control-static" id="J_address"></div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-xs-2" for="responsor">负责人:</label>
                    <div class="col-xs-10">
                        <div class="form-control-static" id="J_manager_name"></div>
                    </div>
                </div>
            
                <div class="form-group ">
                    <label class="col-xs-2" for="shop_name">负责人身份证:</label>
                    <div class="col-xs-10">
                        <label class="img-upload-box" for="upload_img_1" style="width: 224px; height: 142px;">
                            <div class="lint">
                                身份证正面
                            </div>
                            <a href="" data-lightbox="unique-mark2" target="_blank"><img id="J_card_front" src="" alt="暂无"></a>
                        </label>
                        <label class="img-upload-box" for="upload_img_2" style="width: 224px; height: 142px;">
                            <div class="lint">
                                身份证背面
                            </div>
                            <a href="" data-lightbox="unique-mark2" target="_blank"><img id="J_card_back" src="" alt="暂无"></a>
                        </label>
                    </div>
                </div>
                
                <div class="form-group ">
                    <label class="col-xs-2" for="contact">联系人:</label>
                    <div class="col-xs-10">
                        <div class="form-control-static" id="J_contact_name"></div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-xs-2" for="phone">联系人手机号:</label>
                    <div class="col-xs-10">
                        <div class="form-control-static" id="J_contact_mobile"></div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-xs-2" for="email">电子邮箱:</label>
                    <div class="col-xs-10">
                        <div class="form-control-static" id="J_email"></div>
                    </div>
                </div>
                <div class="form-group hidden">
                    <label class="col-xs-1 optional" for="tel1">门店固话:</label>
                    <div class="col-xs-10">
                        <div class="form-control-static"></div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-xs-2" for="shop_name">资质照片:</label>
                    <div class="col-xs-10">
                        <label class="img-upload-box" for="upload_img_3" style="width: 132px; height: 145px;">
                            <div class="lint">
                                营业执照照片
                            </div>
                            <a href="" data-lightbox="unique-mark2" target="_blank"><img id="J_business_licence" src="/images/new_icon.png" alt="暂无"></a>
                        </label>
                        <label class="img-upload-box" for="upload_img_3" style="width: 132px; height: 145px;">
                            <div class="lint">
                                门店门面照片
                            </div>
                            <a href="" data-lightbox="unique-mark2" target="_blank"><img id="J_store_front" src="/images/new_icon.png" alt="暂无"></a>
                        </label>
                        <label class="img-upload-box" for="upload_img_3" style="width: 132px; height: 145px;">
                            <div class="lint">
                                门店店内照片
                            </div>
                            <a href="" data-lightbox="unique-mark2" target="_blank"><img id="J_store_inside" src="/images/new_icon.png" alt="暂无"></a>
                        </label>
                    </div>
                </div>
            </form>
            <!--form end-->
            <div class="personal-info col-xs-6">
                <div class="info-title"><p>邀请人信息</p></div>
                <div class="default-address hidden">
                    <div class="module-title">默认收货信息</div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">收货人:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_default_name"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">联系电话:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_default_mobile"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">收货地址:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_default_address"></div>
                        </div>
                    </div>
                </div>
                <div class="sys-info hidden">
                    <div class="module-title">系统预留信息</div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">门店账号:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_sys_code"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">门店昵称:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_sys_name"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">三级行政区域:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_sys_address"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">五级业务区域:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_sys_area"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">新手机号:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_new_mobile"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">老手机号:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_old_mobile"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">预留邮箱:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_sys_email"></div>
                        </div>
                    </div>
                </div>
                <div class="business-info hidden">
                    <div class="module-title">系统预留信息</div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">运营商账号:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_business_code"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">运营商名称:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_business_name"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">运营商负责人:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_business_leader"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">所属城市:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_business_city"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">城市负责人:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_city_leader"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">所属省:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_business_province"></div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-xs-2" for="email">省负责人:</label>
                        <div class="col-xs-10">
                            <div class="form-control-static" id="J_province_leader"></div>
                        </div>
                    </div>
                </div>
                <p class="watch-more hidden"><a href="#" id="J_be_invited">查看他的被邀请信息&gt;&gt;</a></p>
                <div class="wechat-info hidden">
                    <div class="module-title">微信信息</div>
                    <div class="wechat-box">
                        <img src="" id="J_wechat_img" alt="">
                        <div class="text">
                            <p id="J_wechat_name"></p>
                            <br>
                            <p id="J_wechat_address"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-row hidden" id="J_handle_box">
            <button class="btn btn-danger" data-target="#apxModalAdminAuth" data-toggle="modal">通过</button>
            <button class="btn btn-warning" data-name="驳回" data-toggle="modal" data-target="#apxModalAdminAlertReject">驳回</button>
            <button class="btn btn-warning" data-name="注销" data-toggle="modal" data-target="#apxModalAdminCancel">注销</button>
        </div>
        <div class="btn-row hidden" id="J_cancel_box">
            <button class="btn btn-warning" data-name="注销" data-toggle="modal" data-target="#apxModalAdminCancel">注销</button>
        </div>
    </div>
</div>
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertReject" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">驳回备注</h4>
            </div>
            <div class="modal-body management-textarea">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <label for="remark">请输入备注信息</label>
                        <textarea type="text" class="form-control" id="remark" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_auth_reject">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminCommonAlert" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                操作成功！
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>
<!-- confirm -->
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminAuth" tabindex="-1">
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
                <span class="J_confrim_content">确定要通过吗？</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_auth_sure">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- confirm -->
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminCancel" tabindex="-1">
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
                <span class="J_confrim_content">确定要注销吗？</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_cancel_sure">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>