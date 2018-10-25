<?php
$this->params = ['js' => ['js/quality_global.js', 'js/custom_detail.js'], 'css' => ['css/quality_global.css', 'css/custom_detail.css']];

$this->title = '九大爷平台 - 质保单查询 - 服务商查询详情';
?>
<div class="quality-stores-detail-container">
    <div class="main-container">
        
        <div class="title">
            <span>管芯号查询&nbsp;&gt;&nbsp;服务商质保单详情</span>
            <p>服务商质保单详情</p>
        </div>

        <div class="container-detail">

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

            <div class="stores-car-info">

                <div class="owner-info">
                    <p>车辆信息</p>
                    <p>
                        <span>车主姓名</span>
                        <span class="car-type" id="J_owner_name"></span>
                    </p>
                    <p>
                        <span>车品牌　</span>
                        <span class="car-type" id="J_car_brand"></span>
                    </p>
                </div>

                <div class="car-info">
                    <p>施工信息</p>
                    <p>
                        <span>施工门店</span>
                        <span class="work-stores-name" id="J_construct_custom"></span>
                    </p>
                    <P>
                        <span>施工日期</span>
                        <span class="work-time" id="J_construct_time"></span>
                    </P>
                    <p>
                        <span>完成日期</span>
                        <span class="work-end-time" id="J_finished_time"></span>
                    </p>
                   
                </div>
            </div>

            <div class="product-info">

                <p class="product-title">产品信息</p>

                <div class="package-detail">
                    <table>
                        <thead>
                            <th>序号</th>
                            <th>管芯号</th>
                            <th>品牌</th>
                            <th>产品</th>
                            <th>施工部位</th>
                            <th class="hidden J_work_option">部位选项</th>
                            <th>数量</th>
                            <th>导购</th>
                            <th>技师</th>
                        </thead>
                        <tbody  id="J_product_box">
                            <script type="text/template" id="J_tpl_list">
                                {@each _ as it, index}
                                <tr>
                                    <td>${index - 0 + 1}</td>
                                    <td>${it.code}</td>
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
    </div>
</div>