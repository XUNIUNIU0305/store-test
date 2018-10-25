<?php
$this->params = ['js' => 'js/price.js', 'css' => 'css/price.css'];

$this->title = '九大爷平台 - 账户中心 - 价格查询';
?>

<div class="container">
    <div class="row">
        <div class="apx-price-container">
            <!--car show case-->
            <div class="car-box clearfix">
                <div class="pull-left">
                    <div class="h3">前档</div>
                    <div class="h3 text-warning"><span class="J_package_name"></span></div>
                    <ul class="list-unstyled J_package_attr"></ul>
                </div>
                <div>
                    <div class="h3">侧档</div>
                    <div class="h3 text-warning"><span class="J_package_name"></span></div>
                    <ul class="list-unstyled J_package_attr"></ul>
                </div>
                <div class="pull-right">
                    <div class="h3">后档</div>
                    <div class="h3 text-warning"><span class="J_package_name"></span></div>
                    <ul class="list-unstyled J_package_attr"></ul>
                </div>
            </div>
            <!--price and fever show case-->
            <div class="row">
                <!--price-->
                <div class="col-xs-7">
                    <div class="price-box">
                        <div class="h2 text-danger">套餐价格：<span class="J_package_price"></span></div>
                        <small>
                        未列车型报价参照相似同类车型；<br>
                            本报价为9座以下车辆报价，9座以上车辆及客车可比照相关车型报价。
                            报价系统中套餐默认指前档，侧挡及后档，不包含天窗。
                    </small>
                    </div>
                </div>
                <!--fever-->
                <div class="col-xs-5 ">
                    <div class="fever-box">
                        <div class="h4">车主选择热度</div>
                        <ul class="list-unstyled">
                            <li>
                                <span>吉祥套餐</span>
                                <div class="progress-wrap">
                                    <div class="progress">
                                        <div class="progress-inner" style="width:70%"></div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span>如意套餐</span>
                                <div class="progress-wrap">
                                    <div class="progress">
                                        <div class="progress-inner" style="width:60%"></div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span>幸福套餐</span>
                                <div class="progress-wrap">
                                    <div class="progress">
                                        <div class="progress-inner" style="width:40%"></div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span>平安套餐</span>
                                <div class="progress-wrap">
                                    <div class="progress">
                                        <div class="progress-inner" style="width:100%"></div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span>开心套餐</span>
                                <div class="progress-wrap">
                                    <div class="progress">
                                        <div class="progress-inner" style="width:90%"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--select show case-->
            <div class="select-box">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="">品牌：</label>
                        <select id="J_brand_list" class="form-control">
                            <option value="">请选择</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">型号：</label>
                        <select id="J_type_list" class="form-control">
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
                <ul class="list-unstyled">
                    <li>
                        <div class="dropdown">
                            <button type="button" class="package active" data-id="1">
                                <span>APEX</span> 吉祥套餐
                                <!-- <span class="caret"></span> -->
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">吉祥套餐 前挡U9</a></li>
                                <li><a href="#">吉祥套餐 侧后挡U7</a></li>
                                <li><a href="#">吉祥套餐 前挡U9+侧后挡U7</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown">
                            <button type="button" class="package" data-id="2">
                                <span>APEX</span> 如意套餐
                                <!-- <span class="caret"></span> -->
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">如意套餐 前挡U9</a></li>
                                <li><a href="#">如意套餐 侧后挡U7</a></li>
                                <li><a href="#">如意套餐 前挡U9+侧后挡U7</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown">
                            <button type="button" class="package" data-id="3">
                                <span>APEX</span> 幸福套餐
                                <!-- <span class="caret"></span> -->
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">幸福套餐 前挡U9</a></li>
                                <li><a href="#">幸福套餐 侧后挡U7</a></li>
                                <li><a href="#">幸福套餐 前挡U9+侧后挡U7</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown">
                            <button type="button" class="package" data-id="4">
                                <span>APEX</span> 平安套餐
                                <!-- <span class="caret"></span> -->
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">平安套餐 前挡U9</a></li>
                                <li><a href="#">平安套餐 侧后挡U7</a></li>
                                <li><a href="#">平安套餐 前挡U9+侧后挡U7</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown">
                            <button type="button" class="package" data-id="5">
                                <span>APEX</span> 开心套餐
                                <!-- <span class="caret"></span> -->
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">开心套餐 前挡U9</a></li>
                                <li><a href="#">开心套餐 侧后挡U7</a></li>
                                <li><a href="#">开心套餐 前挡U9+侧后挡U7</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <!--instruction show case-->
            <div class="instruction-box">
                <p>您选择的欧帕斯隔热膜报价如下：</p>
                <p>本报价已含欧帕斯膜施工费；原已贴膜车辆换贴欧帕斯膜的，施工店可另收取全车旧膜除胶费600元-5座车、800元-7座车。</p>
                <table class="table table-bordered table-hover hidden">
                    <thead>
                        <tr>
                            <th>区分</th>
                            <th>膜型号</th>
                            <th class="attrname">可见光透射指数</th>
                            <th class="attrname">紫外线阻隔指数</th>
                            <th class="attrname">红外线阻隔指数</th>
                            <th class="attrname">太阳能阻隔指数</th>
                            <th>施工难度</th>
                            <th>施工时间</th>
                            <th>指导价</th>
                        </tr>
                    </thead>
                    <tbody id="J_price_list">
                        <script type="text/template" id="J_tpl_list">
                            {@each _ as it}
                                <tr>
                                    <td>${it.place}</td>
                                    <td>${it.material.name}</td>
                                    <td>$${it.material.attribute[0].star|star_build}</td>
                                    <td>$${it.material.attribute[1].star|star_build}</td>
                                    <td>$${it.material.attribute[2].star|star_build}</td>
                                    <td>$${it.material.attribute[3].star|star_build}</td>
                                    <td>${it.hard}级</td>
                                    <td>${it.time}小时</td>
                                    <td>${it.price}</td>
                                </tr>
                            {@/each}
                        </script>
                    </tbody>
                </table>
                <div class="text-right hidden">
                    <span class="text-danger">总价：￥</span>
                </div>
                <p class="text-danger">报价说明及收费标准：</p>
                <p>
                    *车身报价说明：上述车身报价不包含天窗；
                    <br> <span>*天窗收费标准：</span>上述车身报价不包含天窗施工；
                    <br> <span class="invisible">*天窗收费标准：</span>小型天窗（不超过一个侧窗面积），按车身报价15%收取；
                    <br> <span class="invisible">*天窗收费标准：</span>中型天窗（不超过两个侧窗面积），按车身报价30%收取；
                    <br> <span class="invisible">*天窗收费标准：</span>大型天窗（超过两个侧窗面积）， 按车身报价50%收取。
                  </p>
            </div>
        </div>
    </div>
</div>
