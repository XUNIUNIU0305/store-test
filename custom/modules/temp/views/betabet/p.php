<?php
$this->params = ['css' => 'css/p2.css', 'js' => 'js/p2.js'];
?>
<div class="summaryreductiondatas">
    <div class="summaryreductiondatas-top">
        <div class="firsttitle">订单明细汇总</div>
        <!-- <p class="activitytime">2018.10.18~2018.10.19</p > -->
    </div>
    <div class="summaryreductiondatas-main">
        <div class="content">
            <div class="main-top"></div>
            <div class="order-details-summary">
                <!-- 筛选区域 -->
                <div class="screening-area">
                    <div class="select-box">
                        <span class="region">所在区域：</span>
                        <select class="select0">
                            <option id='' value ='全部' >全部</option>
                        </select>
                        <select class="select1">
                            <option id='' value ='全部' >全部</option>
                        </select>
                        <select class="select2">
                            <option id='' value ='全部' >全部</option>
                        </select>
                        <select class="select3">
                            <option id='' value ='全部' >全部</option>
                        </select>
                    </div>
                    <div class="import-box">
                        <span class="store-account">门店账号</span>
                        <input type="number" />
                    </div>
                    <div class="inquire" flag="false">查询</div>
                </div>
                
                <div class="order-details-main">
                    <table border="0" width="1200px">
                    <!-- 订单明细标题 -->
                        <thead>
                            <tr class="order-details-title">
                                <th style="width:52px;">编号</th>
                                <th style="width:124px;">订单号</th>
                                <th style="width:124px;">买家账号</th>
                                <th style="width:106px;">买家电话</th>
                                <th style="width:150px;">商品名称</th>
                                <th style="width:52px;">数量</th>
                                <th style="width:213px;">所在区域</th>
                                <th style="width:80px;">团长姓名</th>
                                <th style="width:106px;">团长手机</th>
                                <th style="width:193px;">提货地址</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="hidden">
                            <tr class="order-details-total">
                                <td style="width:52px;">合计</td>
                                <td style="width:124px;"></td>
                                <td style="width:124px;"></td>
                                <td style="width:106px;"></td>
                                <td style="width:150px;"></td>
                                <td class="quantity" style="width:52px;">100</td>
                                <td style="width:213px;"></td>
                                <td style="width:80px;"></td>
                                <td style="width:106px;"></td>
                                <td style="width:193px;"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="load-more hidden" flag="true">加载更多</div>
                    <div class="no-data hidden">
                        <div></div>
                        <span>暂无数据</span>
                    </div>
                </div>
            </div>
            <div class="main-bottom"></div>
        </div>
    </div>
    <div class="summaryreductiondatas-bottom">
    </div>
</div>