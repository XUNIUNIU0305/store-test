<?php
$this->params = ['js' => ['js/create-gpubs.js','js/datepicker.js'], 'css' => 'css/create-gpubs.css'];
?>
<div class="admin-main-wrap">
    <div class="add-group-pro-container">
        <div class="container-title">
            <span>拼购商品</span>
            <span class="btn btn-danger" id="to_list">返回</span>
        </div>
        <div class="add-search-container">
            <div class="input-group">
                <label for="">商品ID：</label>
                <input type="text" placeholder="请输入商品ID查询" name="" id="search_ipt">
            </div>
            <span class="btn btn-danger" id="search-pro">查询</span>
            <div class="error-info hidden">
                请输入商品ID！
            </div>
        </div>
        <div class="pro-info-container hidden">
            <div class="img-title">
                <div class="img-box">
                    <img class="pro-img" src="/images/930.jpg" alt="">
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
                <select name="" id="gp-type">
                    <option value="1">自提拼购</option>
                    <option value="2">送货拼购</option>
                </select>
            </div>
            <div class="input-group">
                <label for="">账户开团上限：</label>
                <input type="text" name="" id="max_launch_per_user">
                次
            </div>
            <div class="input-group" id="self_lifting">
                <label for="">成团规则：</label>
                <input type="text" name="" id="min_quantity_per_group">
                件成团
            </div>
            <div class="input-group" id="delivery">
                <label for="">成团规则：</label>
                <ul>
                    <li>
                        <input type="radio" name="groupType" title="1" checked />按人数<br/>
                        <input type="text" value="" id="min_member_per_group" /> 人成团
                    </li>
                    <li>
                        <input type="radio" name="groupType" title="2" />按数量<br/>
                        <input type="text" value="" id="min_quantity_per_group2" disabled /> 件成团
                    </li>
                    <li>
                        <input type="radio" name="groupType" title="3" />按人数+数量<br/>
                        <input type="text" value="" id="min_member_per_group2" disabled /> 人 +
                        <input type="text" value="" id="min_quanlity_per_member_of_group" disabled /> 件成团
                    </li>
                </ul>
            </div>
            <div class="input-group">
                <label for="">拼购时间：</label>
                <input type="text" name="" id="lifecycle_per_group">
                小时
            </div>
            <div class="input-group input-group-date-picker">
                <label for="data-picker-box">活动时间：</label>
                <div class="data-picker-box">
                    <div class="input-group query_time in">
                        <input type="text" class="form-control date-picker" value="" id="J_start_time">
                        <span class="input-group-btn date-show">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>
                    <span>至</span>
                    <div class="input-group query_time in">
                        <input type="text" class="form-control date-picker" value="" id="J_end_time">
                        <span class="input-group-btn date-show">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="input-group set-box">
                <label for="">统一设置：</label>
                <div class="set-container">
                    <div class="common-set">
                        <div class="input-group">
                            <input type="number" placeholder="拼购价格" name="" id="total-price">
                            元
                        </div>
                        <div class="input-group">
                            <input type="number" placeholder="拼购总量" name="" id="total-counts">
                            件
                        </div>
                        <span class="btn" id="set_total_btn">确定</span>
                    </div>
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
                                            <div class="">
                                                <input class="form-control guidance-fill J_price" maxlength="9" type="text">
                                            </div>
                                            <span>元</span>
                                        </td>
                                        <td>
                                            <div class="">
                                                <input class="form-control guidance-fill J_count" maxlength="9" type="text">
                                            </div>
                                            <span>件</span>
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
                <input type="text" name="" id="description" maxlength=50>
            </div>
            <div class="input-group">
                <label for="">微信转发内容：</label>
                <span class="wechat_info">主标题：</span>
                <input type="text" name="" id="share_title" maxlength=25>
            </div>
            <div class="input-group">
                <label for=""></label>
                <span class="wechat_info">副标题：</span>
                <input type="text" name="" id="share_subtitle" maxlength=50>
            </div>
            <div class="input-group">
                <label for=""></label>
                <span class="wechat_info">头图：</span>
                <span class="fileCon">
                    <strong id="infoShow">上传图片</strong>
                    <img src="" id="imgShow" />
                    <input type="file" name="" id="filename">
                </span>
            </div>
            <div class="handle-box">
                <span class="btn btn-danger" id="add_gpubs">新增</span>
            </div>
        </div>
    </div>
</div>

