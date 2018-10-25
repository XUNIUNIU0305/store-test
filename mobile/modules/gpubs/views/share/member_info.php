<?php
/**
 * Created by PhpStorm.
 * User: kun
 * Date: 2018/8/23
 * Time: 15:40
 */
$this->params = ['js' => 'js/member_info.js', 'css' => 'css/member_info.css'];
$this->title = '团员拼购信息';
?>
<div class="group-activity-personnel-information">
    <div class="white"></div>
    <div class="head-personnel-information">
        <div class="head-personnel-pic"><div class="head-personnel"></div></div>
        <p class="head-personnel-name"></p>
        <p class="head-personnel-starttime"><span></span>发起拼购</p>
    </div>
    <div class="white1"></div>

    <div class="personnel-information">
        <div class="personnel-group">
            <div class="personnel-group-info">
                <span class="personnel-group-premise"></span>
                <span class="personnel-group-way"></span>
                <span class="personnel-group-nows">已拼:<em  class="personnel-group-now"></em></span>
            </div>
            <div class="personnel-num">团编号：<span></span></div>
            <p class="personnel-introduce"></p>
        </div>
        <div class="personnel-information-num">
            <!-- 无人参加拼购 -->
            <div class="none-personnel-group hidden">
                <div class="none-personnel-pic"></div>
                <p class="none-personnel-info">暂无团员参加此拼购</p>
            </div>
             <!-- 有人参加拼购 -->
            <div class="have-personnel-group hidden" id="have-personnel-group"></div>
            <!-- 参加人员信息 -->
            <script id="activityGroupMemberInfo" type="text/template">
                {@each member as data,index}
                    <div class="have-personnel-group-list">
                        <div class="have-personnel-group-pic"><img class="have-personnel-group-head-portrait" src="${data.header_img}" alt=""></div>
                        <div class="have-personnel-group-info">
                            <div class="have-personnel-name">${data.custom_user_account}</div>
                            <div class="have-personnel-spe">
                                <span class="have-personnel-Model">
                                    {@each data.sku_attribute as datas,index}
                                        ${datas.selectedOption.name};
                                    {@/each}
                                </span>
                                <span class="have-personnel-spe-num">${data.quantity}件</span>
                            </div>
                            <div class="have-personnel-time"><span>${data.join_datetime}</span>加入拼购</div>
                        </div>
                    </div>
                {@/each}
            </script>
        </div>
    </div>
</div>



