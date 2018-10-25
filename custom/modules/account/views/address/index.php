<?php
$this->params = ['js' => 'js/address.js', 'css' => 'css/address.css'];

$this->title = '九大爷平台 - 账户中心 - 收货地址';
?>
<div class="top-title">地址管理</div>
<!-- adress edit start -->
<div class="apx-edit-address-wrap">
    <form class="apx-edit-address-form form-horizontal">
        <div class="collapse" id="collapseAddress">
            <div class="form-group">
                <label class="col-xs-3 control-label">收货人：</label>
                <div class="col-xs-2">
                    <input type="text" class="form-control" id="J_username" maxlength="8" placeholder="收货人姓名">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label">所在地区：</label>
                <div class="col-xs-2 dropdown J_address_province">
                    <button class="btn btn-block btn-default dropdown-toggle" type="button" id="address_area" data-toggle="dropdown">
                        <span>省／直辖市</span>
                        <i class="glyphicon glyphicon-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="address_area">
                       
                    </ul>
                </div>
                <div class="col-xs-2 dropdown J_address_city">
                    <button class="btn btn-block btn-default dropdown-toggle" type="button" id="address_city" data-toggle="dropdown">
                        <span>市</span>
                        <i class="glyphicon glyphicon-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="address_city">
                    </ul>
                </div>
                <div class="col-xs-2 dropdown J_address_district">
                    <button class="btn btn-block btn-default dropdown-toggle" type="button" id="address_district" data-toggle="dropdown">
                        <span>区</span>
                        <i class="glyphicon glyphicon-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="address_district">
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label">详细地址：</label>
                <div class="col-xs-7">
                    <input type="text" class="form-control" id="J_detail" maxlength="40" placeholder="街道门牌楼层房间号">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label">手机号码：</label>
                <div class="col-xs-3">
                    <input type="text" class="form-control J_only_number" id="J_mobile" placeholder="手机号" maxlength="11">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label">邮政编码：</label>
                <div class="col-xs-3">
                    <input type="text" class="form-control J_only_number" id="J_code" placeholder="请填写6位邮政编码" maxlength="6">
                </div>
            </div>
               <!--      <div class="form-group">
                <label class="col-xs-3 control-label">备注：</label>
                <div class="col-xs-7">
                    <textarea type="text" class="form-control" id="name" placeholder="年"></textarea>
                </div>
            </div> -->
            <div class="form-group text-center">
                <div class="btn btn-lg btn-danger J_add_address">保存收货地址</div>
            </div>
        </div>

        <div class="form-group">
            <a href="#collapseAddress" data-toggle="collapse" class="btn btn-lg btn-danger J_address_add">新增收货地址</a>
        </div>
    </form>
    <div class="apx-edit-address-box clearfix" id="J_address_box">
        <script type="text/template" id="J_tpl_address">
            {@each _ as it, index}
                {@if it.is_default}
                <div class="col-xs-4 J_box" data-id="${index}">
                    <div class="apx-cart-address default active J_address" data-id="${it.id}">
                        <h5>收货人：
                            <span>${it.consignee}</span>
                            <a href="javascript:void(0)" class="btn btn-danger btn-xs pull-right">默认地址</a>
                        </h5>
                        <p class="address" title="${it.province.name+it.city.name+it.district.name+it.detail}">地址：${it.province.name+it.city.name+it.district.name+it.detail}</p>
                        <p>电话：${it.mobile}</p>
                        <div class="position text-right">
                            <a href="javascript:void(0)" class="btn btn-link btn-xs J_alter">修改</a>
                            <a href="javascript:void(0)" class="btn btn-link btn-xs J_address_delete_btn">删除</a>
                        </div>
                        <div class="del-confirm hidden">
                            <p class="del-title text-center">确定删除此地址？</p>
                            <div class="btn-box text-center">
                                <span class="btn btn-danger J_adress_delete-confirm">确定</span>
                                <span class="btn btn-default J_address_cancel_btn">取消</span>
                            </div>
                        </div>
                    </div>
                </div>
                {@/if}
            {@/each}
            {@each _ as it, index}
                {@if it.is_default == false}
                <div class="col-xs-4 J_box" data-id="${index}">
                    <div class="apx-cart-address J_address" data-id="${it.id}">
                        <h5>收货人：
                            <span>${it.consignee}</span>
                            <a href="javascript:void(0)" class="btn btn-default btn-xs pull-right J_set_default">设置为默认</a>
                        </h5>
                        <p class="address" title="${it.province.name+it.city.name+it.district.name+it.detail}">地址：${it.province.name+it.city.name+it.district.name+it.detail}</p>
                        <p>电话：${it.mobile}</p>
                        <div class="position text-right">
                            <a href="javascript:void(0)" class="btn btn-link btn-xs J_alter">修改</a>
                            <a href="javascript:void(0)" class="btn btn-link btn-xs J_address_delete_btn">删除</a>
                        </div>
                        <div class="del-confirm hidden">
                            <p class="del-title text-center">确定删除此地址？</p>
                            <div class="btn-box text-center">
                                <span class="btn btn-danger J_adress_delete-confirm">确定</span>
                                <span class="btn btn-default J_address_cancel_btn">取消</span>
                            </div>
                        </div>
                    </div>
                </div>
                {@/if}
            {@/each}
        </script>
        <div class="col-xs-12">
            <a class="pull-right" href="#">收起地址 <i class="glyphicon glyphicon-chevron-up"></i></a>
        </div>
    </div>
</div>
<!-- adress edit end -->
