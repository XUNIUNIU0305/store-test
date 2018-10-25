<?php
$this->params = ['js' => 'js/detail-gpubs.js', 'css' => 'css/detail-gpubs.css'];
?>
<div class="admin-main-wrap">
    <div class="add-group-pro-container">
        <div class="container-title">
            <span><strong>拼购商品详情</strong></span>
            <span class="btn btn-danger" id="to_list">返回</span>
        </div>

        <div class="pro-info-container">
            <div class="img-title">
                <div class="img-box">
                    <img class="pro-img" src="" alt="">
                </div>
                <div class="item-text">
                    <p>
                        商品类目：<span class="pro-sort"></span>
                    </p>
                    <p>
                        商品标题：<span class="pro-title"></span>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="">拼购类型：</label>
                <span id="groupType"></span>
            </div>
            <div class="input-group">
                <label for="">账户开团上限：</label>
                <span class="max_launch_per_user"></span>
                次
            </div>
            <div class="input-group">
                <label for="">成团规则：</label>
                <span class="min_quantity_per_group"></span>
            </div>
            <div class="input-group">
                <label for="">拼购时间：</label>
                <span class="lifecycle_per_group"></span>
                小时
            </div>
            <div class="input-group input-group-date-picker">
                <label for="data-picker-box">活动时间：</label>
                <div class="data-picker-box">
                    <!-- <div class="input-group query_time in">
                        <input type="text" class="form-control date-picker" value="" id="J_start_time">
                        <span class="input-group-btn date-show">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div> -->
                    <span class="J_start_time"></span>
                    <span>至</span>
                    <!-- <div class="input-group query_time in">
                        <input type="text" class="form-control date-picker" value="" id="J_end_time">
                        <span class="input-group-btn date-show">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div> -->
                    <span class="J_end_time"></span>
                </div>
            </div>
            <div class="input-group set-box">
                <label for="">统一设置：</label>
                <div class="set-container">
                    <!-- <div id="J_tpl_box">
                        <script type = 'text/template' id='J_tpl_table'>
                            <table class="attr-box">
                                <thead>
                                    <tr>
                                    {@each attrName as it}
                                        <td><div class="text-ellipsis" title="${it}">${it}</div></td>
                                    {@/each}
                                        <th>原单价</th>
                                        <th>拼购价格</th>
                                        <th>拼购总量</th>
                                    </tr>
                                </thead>
                                {@each i in range(0,row)}
                                <tr>
                                    {@each allAttr as x, index}
                                        {@if x[i] != undefined}
                                            <td rowspan="${[x, row]|rowspan_build}"><div class="text-ellipsis" title="${x[i]}">${x[i]}</div></td>
                                        {@/if}
                                    {@/each}
                                    <td>搜索</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="" id="">
                                            元
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="" id="">
                                            件
                                        </div>
                                    </td>
                                </tr>
                                {@/each}
                            </table>
                        </script>
                        <table class="attr-box">
                            <thead>
                                <tr>
                                    <th>属性</th>
                                    <th>颜色</th>
                                    <th>原单价</th>
                                    <th>拼购价格</th>
                                    <th>拼购总量</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>搜索</td>
                                    <td>搜索</td>
                                    <td>搜索</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="" id="">
                                            元
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="" id="">
                                            件
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->
                    <!-- </div> -->
                    <div id="J_table_box" class="apx-seller-attr-wrap attr-box pull-left">
                        <script type="text/template" id="J_tpl_table">
                            <table class="table table-bordered text-center">
                                <tr class="text-center active">
                                {@each attrName as it}
                                    <td><div class="text-ellipsis" title="${it}">${it}</div></td>
                                {@/each}
                                    <td>原单价</td>
                                    <td>拼购价格</td>
                                    <td>拼购总量</td>
                                </tr>
                                {@each i in range(0,row)}
                                    <tr>
                                        {@each allAttr as x, index}
                                            {@if x[i] != undefined}
                                                <td rowspan="${[x, row]|rowspan_build}"><div class="text-ellipsis" title="${x[i]}">${x[i]}</div></td>
                                            {@/if}
                                        {@/each}
                                        <td>
                                            <div class="old-price"></div>
                                        </td>
                                        <td>
                                            <div class="price">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="stock">
                                            </div>
                                        </td>
                                    </tr>
                                {@/each}
                            </table>
                        </script>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <label for="">商品服务说明：</label>
                <span id="description"></span>
            </div>
            <div class="input-group">
                <label for="">微信转发内容：</label>
                <span class="wechat_info">主标题：</span>
                <span id="share_title"></span>
            </div>
            <div class="input-group">
                <label for=""></label>
                <span class="wechat_info">副标题：</span>
                <span id="share_subtitle"></span>
            </div>
            <div class="input-group">
                <label for=""></label>
                <span class="wechat_info">头图：</span>
                <span class="fileCon">
                    <img src="" id="filename" />
                </span>
            </div>
        </div>
    </div>
</div>
