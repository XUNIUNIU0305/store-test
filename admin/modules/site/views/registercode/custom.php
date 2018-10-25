<?php
$this->params = ['js' => 'js/registercode.js', 'css' => 'css/registercode.css'];
?>

<div class="apx-admin-account">
    <div class="account-head">
        <h5>账户管理</h2>
        <div class="account-line"></div>
        <a class="btn btn-default" href="" data-toggle="modal" data-target="#modalAddType">+添加账户</a>
    </div>
    <div class="account-head account-main">
        <h5>新账户添加如下</h2>
        <div class="account-line"></div>
        <ul class="account-list-box">
            <li class="account-list">
                <span class="col-xs-10">账户ID</span>
                <span class="">状态</span>
            </li>
            <div class="J_account_box"></div>
        </ul>
    </div>
	<div class="apx-admin-add-account modal fade" id="modalAddType" tabindex="-1">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <a type="button" class="close" data-dismiss="modal">
	                    <span aria-hidden="true">&times;</span>
	                    <span class="sr-only">Close</span></a>
	                <h4 class="modal-title">+添加账户</h4>
	            </div>
	            <div class="modal-body">
	                <form>
	                    <div class="form-group">
	                        <div class="box">
	                            <h4>请输入添加账户个数：</h4>
	                        </div>
	                        <div class="button-group">
	                            <div class="left">
	                                <div class="addon add">-</div><input type="text" value="1" maxlength="3" id="J_account_nub"><div class="addon sub">+</div>
	                            </div>
	                            <div class="right J_add_account"><a href="javascript:;">生成</a></div>
	                        </div>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
</div>
