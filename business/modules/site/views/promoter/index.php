<?php
$this->params = ['css' => 'css/promoter.css', 'js' => 'js/promoter.js'];
?>
<div class="business-main-wrap">
    <div class="business-create-code">
        <div class="row header">
            <div class="col-xs-2 msg">
                <p>使用中的邀请码</p>
                <span id="J_in_use">0</span>
            </div>
            <div class="col-xs-2 msg">
                <a href="/site/promoter/review?status=0">
                    <p>成功注册门店</p>
                    <span id="J_success_use">0</span>
                </a>
            </div>
            <div class="col-xs-2 msg">
                <a href="/site/promoter/review?status=1">
                    <p>待审核门店</p>
                    <span id="J_check_pending">0</span>
                </a>
            </div>
            <div class="col-xs-2 msg">
                <a href="/site/promoter/review?status=2">
                    <p>待提交信息门店</p>
                    <span id="J_wait_submit">0</span>
                </a>
            </div>
            <div class="col-xs-2">
                <a href="/site/promoter/stream">
                	<p class="price" id="J_amount">￥0.00</p>
                	<span>奖励金额</span>
                </a>
            </div>
            <div class="col-xs-2">
                <a class="btn btn-business" data-target="#apxModalBusinessAdd" data-toggle="modal">添加邀请码</a>
            </div>
        </div>
        <div class="code-list clearfix" id="J_store_list">
            <script type="text/template" id="J_tpl_list">
                {@each _ as it}
                    <div class="item">
                        <p class="code">序列号：<span>${it.id}</span></p>
                        {@if it.is_available == 0}
                            <div class="img disabled"></div>
                        {@else if it.is_available == 1}
                            <div class="img usable"></div>
                        {@/if}
                        <div class="remark">
                            <p>备注：${it.title}</p>
                            <input type="text" class="hidden" placeholder="请输入备注" name="">
                        </div>
                        <div class="handle-group">
                            {@if it.is_available == 0}
                                <a href="#" class="btn btn-business disabled" data-id="${it.id}" data-target="#apxModalBusinessDel" data-toggle="modal">禁用</a>
                                <a href="javascript:;" class="btn btn-business edit-remark disabled" data-id="${it.id}">修改备注</a>
                                <a href="/site/promoter/invite?id=${it.id}" class="btn btn-business">邀请记录</a>
                                <a href="/site/promoter/download?id=${it.id}" class="btn btn-business disabled">下载</a>
                            {@else if it.is_available == 1}
                                <a href="#" class="btn btn-business" data-id="${it.id}" data-target="#apxModalBusinessDel" data-toggle="modal">禁用</a>
                                <a href="javascript:;" class="btn btn-business edit-remark" data-id="${it.id}">修改备注</a>
                                <a href="/site/promoter/invite?id=${it.id}" class="btn btn-business">邀请记录</a>
                                <a href="/site/promoter/download?id=${it.id}" class="btn btn-business">下载</a>
                            {@/if}
                            
                        </div>
                    </div>
                {@/each}
            </script>
        </div>
        <div class="footer text-right" id="J_store_page"></div>
    </div>
</div>
<!-- 添加弹窗 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessAdd" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;"><i class="glyphicon glyphicon-alert"></i>确定要添加邀请码？</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_add_qr">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 禁用弹窗 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessDel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;"><i class="glyphicon glyphicon-alert"></i>确定要禁用邀请码？</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_del_qr">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>