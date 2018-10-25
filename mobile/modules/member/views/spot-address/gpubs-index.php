<?php
$this->params = ['js' => 'js/address-gpubs-index.js','css'=>'css/address-gpubs-index.css'];
$this->title = '九大爷平台 - 自提点管理';
?>

<style>
    body {
        background-color: #f2f2f2;
    }
</style>

<div class="self-lifting-manage-box" id="self-lifting-manage-box">

</div>

<script type="text/template" id="self-lefting-manage">
    {@if data != null}
        <div class="self-lifting-manage" id="self-lifting-manage">
            <ul class="addr-list" id="addr-list">
                {@each data as it}
                    <li class="addr-item">
                        <div class="addr-info">
                            <div>
                                <span class="addr-default {@if it.default == 0} hidden {@/if}"></span>
                                <span class="addr-txt">${it.spot_name}</span>
                            </div>
                            <span class="addr-user">${it.consignee}</span>
                            <span class="addr-user">${it.mobile}</span>
                            <span class="addr-detail">${it.detailed_address}</span>
                        </div>
                        <div class="addr-set">
                            {@if it.default == 1}
                                <img class="addr-sele" data-id="${it.id}" src="/images/self_lifting_manage/checkbox_sele_44_icon.png" alt="">
                                <span class="set-default">已设为默认</span>
                            {@else}
                                <img class="addr-sele" data-id="${it.id}" src="/images/self_lifting_manage/checkbox_no_44_icon.png" alt="">
                                <span class="set-default">设为默认</span>
                            {@/if}
                            <span class="addr-right">
                                {@if it.default == 1}
                                    <a class="addr-btn edit-btn sele" data-id="${it.id}" href="javascript:void(0);">编辑</a>
                                    <a class="addr-btn del-btn active" href="javascript:void(0)" data-id="${it.id}">删除</a>
                                {@else}
                                    <a class="addr-btn edit-btn" data-id="${it.id}" href="javascript:void(0);">编辑</a>
                                    <a class="addr-btn del-btn" href="javascript:void(0)" data-id="${it.id}">删除</a>
                                {@/if}
                            </span>
                        </div>
                    </li>
                {@/each}
            </ul>
        </div>
        <a class="push-btn p-btn" href="/member/spot-address/gpubs-add">新增自提点</a>
    {@else}
        <div class="self-lifting-manage-no hidden">
            <div class="addr-no-box">
                <span class="user-pic"></span>
                <span>暂无自提点</span>
                <a class="manage-btn" href="/member/spot-address/gpubs-add">自提点管理</a>
            </div>
        </div>
    {@/if}
</script>