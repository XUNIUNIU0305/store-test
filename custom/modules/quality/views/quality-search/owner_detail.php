<?php
$this->params = ['js' => ['js/quality_global.js', 'js/owner_detail.js'], 'css' => ['css/quality_global.css', 'css/owner_detail.css']];

$this->title = '九大爷平台 - 质保单查询 - 车主查询详情';
?>

<div class="quality-owner-detail-container">
    <div class="main-container">
        
        <div class="title">
            <span>车主查询&nbsp;&gt;&nbsp;车主质保单详情</span>
            <p>车主质保单详情</p>
        </div>

        <div class="empty-result-tip hidden">
            <div>
                <span>很遗憾！未能查询到您想要的结果</span>
                <p>请认真核对质保单号后，重新查询</p>
            </div>
            <a href="/quality/quality-search/index" class="btn">重新查询</a>
        </div>

        <div class="container-detail hidden">

            <div class="top">
                <p>
                    <span>质保单号</span>
                    <span class='quality-id' id="J_order_code"></span>
                </p>
                <p>
                    <span>质保单生成日期</span>
                    <span class='create-time' id="J_order_create_time"></span>
                </p>
            </div>

            <div class="owner-car-info">

                <div class="owner-info">
                    <p>车主信息</p>
                    <p>
                        <span>车主姓名</span>
                        <span class="owner-name" id="J_owner_name"></span>
                    </p>
                    <p>
                        <span>手机号码</span>
                        <span class="phone" id="J_owner_mobile"></span>
                    </p>
                    <p>
                        <span>车主地址</span>
                        <span class="address" id="J_owner_address"></span>
                    </p>
                    <p>
                        <span>电话号码</span>
                        <span class="cellphone" id="J_owner_phone"></span>
                    </p>
                    <p>
                        <span>电子邮箱</span>
                        <span class="e-mail" id="J_owner_email"></span>
                    </p>
                </div>

                <div class="car-info">
                    <p>车辆信息</p>
                    <p>
                        <span>车牌号码</span>
                        <span id="J_car_number"></span>
                    </p>
                    <P>
                        <span>车架号</span>
                        <span id="J_car_frame"></span>
                    </P>
                    <p>
                        <span>车品牌　</span>
                        <span id="J_car_brand_type"></span>
                    </p>
                    <p>
                        <span>车价</span>
                        <span id="J_car_price_range"></span>
                    </p>
                </div>
            </div>

            <div class="product-info">

                <p class="product-title">产品信息</p>

                <div class="package-tyep">
                    <p>
                        <span>套餐类型：</span>
                        <span class="package-name" id="J_package_name"></span>
                    </p>

                    <div class="package-detail">
                        <table>
                            <thead>
                                <th>序号</th>
                                <th>品牌</th>
                                <th>产品</th>
                                <th>施工部位</th>
                                <th class="hidden J_work_option">部位选项</th>
                                <th>数量</th>
                                <th>导购</th>
                                <th>技师</th>
                            </thead>
                            <tbody  id="J_package_box">
                                <script type="text/template" id="J_tpl_list">
                                    {@each _ as it, index}
                                        <tr>
                                            <td>${index - 0 + 1}</td>
                                            <td>${it.brand}</td>
                                            <td>${it.type}</td>
                                            <td>${it.place}</td>
                                            {@if it.work_option}
                                            <td class="text-center">
                                                <div style="max-width: 12em;margin: 0 auto;" class="text-center">${it.work_option ? it.work_option : ''}</div>
                                            </td>
                                            {@/if}
                                            <td>${it.amount}</td>
                                            <td>${it.sales}</td>
                                            <td>${it.technician}</td>
                                        </tr>
                                    {@/each}
                                </script>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="work-info">
                <p>施工信息</p>
                <p>
                    <span>施工门店</span>
                    <span class="work-store" id="J_construct_custom"></span>
                </p>
                <p>
                    <span>产品总价</span>
                    <span class="product-price-sum" id="J_construct_price"></span>
                </p>
                <p>
                    <span>施工日期</span>
                    <span class="work-time" id="J_construct_time"></span>
                </p>
                <p>
                    <span>完成日期</span>
                    <span class="work-end-time" id="J_finished_time"></span>
                </p>
            </div>

        </div>
    </div>
</div>