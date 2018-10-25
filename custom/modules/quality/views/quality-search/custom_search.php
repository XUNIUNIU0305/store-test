<?php
$this->params = ['js' => ['js/quality_global.js', 'js/custom_search.js'], 'css' => ['css/quality_global.css', 'css/custom_search.css']];

$this->title = '九大爷平台 - 质保单查询 - 服务商查询页面';
?>

<div class="custom-quality-service">
    <div class="quality-nav-text">
        服务商登录&nbsp;&gt;质保查询
    </div>
    <div class="quality-title">质保查询</div>
    <div class="quality-service-container">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab">按质保单查询</a></li>
            <li role="presentation"><a href="#profile"  role="tab" data-toggle="tab">按管芯号查询</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <div class="searvice-search-container">
                    <label for="">质保单号：</label>
                    <input type="text" name="" id="J_code_input"/>
                    <span class="btn" id="J_code_btn">立即查询</span>
                </div>
                <div class="search-result-container hidden">
                    <div class="sub-title">查询结果</div>
                    <div class="empty-tip hidden">
                        <span>请输入“质保单号”查询结果</span>
                    </div>
                    <div class="empty-result-tip hidden">
                        <span>很遗憾！未能查询到您想要的结果</span>
                        <p>请认真核对质保单号后，重新查询</p>
                    </div>
                    <div id="J_code_result" class="hidden">
                        <div class="order-detail">
                            <div>
                                套餐类型：
                                <span id="J_code_package_type"></span>
                            </div>
                            <div>
                                质保单号：
                                <span id="J_code_order_code"></span>
                            </div>
                            <div>
                                质保单生成日期：
                                <span id="J_code_create_time"></span>
                            </div>
                        </div>
                        <table class="search-list">
                            <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>品牌</th>
                                    <th>产品</th>
                                    <th>施工部位</th>
                                    <th class="hidden J_work_option">部位选项</th>
                                    <th>数量</th>
                                    <th>导购</th>
                                    <th>技师</th>
                                </tr>
                            </thead>
                            <tbody id="J_code_package_list">
                                <script type="text/template" id="J_code_tpl_list">
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
                        <div class="other-info">
                            <div>
                                <div class="sub-title">车辆信息</div>
                                <div class="item">
                                    车主姓名
                                    <span id="J_code_owner_name"></span>
                                </div>
                                <div class="item">
                                    车品牌　
                                    <span id="J_code_car_brand"></span>
                                </div>
                            </div>
                            <div>
                                <div class="sub-title">施工信息</div>
                                <div class="item">
                                    施工门店
                                    <span id="J_code_construct_custom"></span>
                                </div>
                                <div class="item">
                                    施工日期
                                    <span id="J_code_construct_time"></span>
                                </div>
                                <div class="item">
                                    完成日期
                                    <span id="J_code_finished_custom"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
            <div class="searvice-search-container">
                    <label for="">　管芯号：</label>
                    <input type="text" name="" id="J_tubecode_input"/>
                    <span class="btn" id="J_tubecode_btn">立即查询</span>
                </div>
                <div class="search-result-container hidden">
                    <div class="sub-title">查询结果</div>
                    <div class="empty-tip hidden">
                        <span>请输入“管芯号”查询结果</span>
                    </div>
                    <div class="empty-result-tip hidden">
                        <span>很遗憾！未能查询到您想要的结果</span>
                        <p>请认真核对管芯号后，重新查询</p>
                    </div>
                    <div id="J_tubecode_result" class="hidden">
                        <table class="search-list">
                            <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>质保单号</th>
                                    <th>车主姓名</th>
                                    <th>施工门店</th>
                                    <th>车品牌</th>
                                    <th>质保单生成日期</th>
                                </tr>
                            </thead>
                            <tbody id="J_tubecode_package_list">
                                <script type="text/template" id="J_tubecode_tpl_list">
                                    {@each _ as it, index}
                                        <tr>
                                            <td>${index - 0 + 1}</td>
                                            <td><a target="_blank" href="/quality/quality-search/custom-detail?order_code=${it.code}">${it.code}</a></td>
                                            <td>${it.owner_name}</td>
                                            <td>${it.construct_unit}</td>
                                            <td>${it.car_brand}</td>
                                            <td>${it.construct_time}</td>
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
</div>