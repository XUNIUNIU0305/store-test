<?php
use custom\assets\EmptyAsset;
EmptyAsset::register($this)->addJs('js/quality-index.js')->addCss('css/quality-index.css');
?>

<div class="custom-quality-search">
    <!-- 错误信息弹出 -->
    <div class="error-ms-window">
        <div class="error-head">
            <p class="error-title">提示</p>
            <img class="close-png" src="/images/quality_search/close.png"/>
        </div>
        <div class="error-ms-detail"></div>
        <button class="error-confirm-btn">确定</button>
    </div>
    <div class="shadow-screen"></div>
    <div class="head">
        <img class="logo" src="/images/quality_search/logo_2.png"/>
        <p class="h-title">九大爷平台质保单查询</p>
    </div>
    <div class="contents">
        <div class="details">
            <p class="c-title">请选择合适您的搜索条件</p>
            <div class="d-contents">
                <ul class="list">
                    <li class="li-clicksty">
                        <i class="clickstyle"></i>
                        <p>质保单号、管芯号、车架号、车主手机号</p>
                    </li>
                    <li>
                        <i></i><p>质保单号</p>
                    </li>
                    <li>
                        <i></i><p>质保单号、车主姓名、车架号、车主手机号</p>
                    </li>
                    <li>
                        <i></i><p>管芯号</p>
                    </li>
                </ul>
                <div class="shadowbox"></div>
                <div class="details-contents-container">
                <!-- 质保单、管芯号、车架号、手机号 -->
                    <div class="detail-contents-ft">
                        <form class="list-contents">
                            <p>
                                搜索条件
                                <span>（*为必填项）</span>
                            </p>
                            <div class="layout">
                                <p>*<span>质保单号</span></p>
                                <input type="text" id="first-order-no">
                                <i>*请输入质保单号</i>
                            </div>
                            <div class="layout">
                                <p>*<span>管芯号</span></p>
                                <input type="text" id="first-code">
                                <i>*请输入管芯号</i>
                            </div>
                            <div class="layout">
                                <p>*<span>车架号（后五位）</span></p>
                                <input type="text" id="first-car-frame" maxlength="5">
                                <i>*请输入车架号（后五位）</i>
                            </div>
                            <div class="layout">
                                <p>*<span>车主手机号</span></p>
                                <input type="text" id="first-mobile">
                                <i>*请输入车主手机号</i>
                            </div>
                            <div class="layout">
                                <p>*<span>验证码：</span></p>
                                <input type="text" id="first-captcha">
                                <div class="ms-code">
                                    <img class="ms-details" src="/captcha"/>
                                    <a href="javascript:;" class="changcode">
                                        看不清?楚换一张
                                    </a>
                                </div>
                                <i id="error-i" class="error-ms">*请输入验证码</i>
                            </div>
                         
                            <div class="button">
                                <span class="rest">重置</span>
                                <span class="next order-code-carframe-captcha">验证</span>
                            </div>
                        </form>
                    </div>
                    <!-- 质保单号 -->
                    <div class="order-no">
                        <div class="order-no-container">
                            <p>
                               搜索条件
                               <span>（*为必填项）</span> 
                            </p>
                            <div class="layout">
                                <p>*<span>质保单号:</span></p>
                                <input type="text" id="second-order-no">
                                <i>*请输入质保单号</i>
                            </div>
                            <div class="layout">
                                <p>*<span>验证码：</span></p>
                                <input type="text" id="second-captcha">
                                <div class="ms-code">
                                    <img class="ms-details" src="/captcha"/>
                                    <a href="javascript:;" class="changcode">
                                        看不清?楚换一张
                                    </a>
                                </div>
                                <i id="error-i" class="error-ms">*请输入验证码</i>
                            </div>
                            <div class="order-conf-btn">
                                <span class="rest">重置</span>
                                <span class="confirm-btn next order-no-btn">验证</span>
                            </div>
                        </div>
                    </div>
                    <!-- 3质保单号、车主姓名、车架号、车主手机号 -->
                    <div class="order-name-car">
                        <form class="onc-contents">
                            <p>
                                搜索条件
                                <span>（*为必填项）</span>
                            </p>
                            <div class="layout">
                                <p>*<span>质保单号</span></p>
                                <input type="text" id="third-order-no">
                                <i>*请输入质保单号</i>
                            </div>
                            <div class="layout">
                                <p>*<span>车主姓名</span></p>
                                <input type="text" id="third-name">
                                <i>*请输入车主姓名</i>
                            </div>
                            <div class="layout">
                                <p>*<span>车架号（后五位）</span></p>
                                <input type="text" id="third-car-frame" maxlength="5">
                                <i>*请输入车架号（后五位）</i>
                            </div>
                            <div class="layout">
                                <p>*<span>车主手机号</span></p>
                                <input type="text" id="third-mobile" maxlength="11">
                                <i>*请输入车主手机号</i>
                            </div>
                            <div class="layout">
                                <p>*<span>手机验证码：</span></p>
                                <input type="text" id="third-mobile-captcha">
                                <div class="mobile-confcode">
                                    <a class="mobile-ms-btn">获取验证码
                                    </a>
                                 </div>
                                <i>*手机验证码错误</i>
                            </div>
                            <div class="button">
                                <span class="rest">重置</span>
                                <span class="next order-name-car-btn">验证</span>
                            </div>
                        </form>
                    </div>
                    <!-- 管芯号 -->
                    <div class="code-no">
                        <div class="code-no-container">
                            <p>
                            搜索条件
                                <span>（*为必填项）</span>
                            </p>
                            <div class="layout">
                                <p>*<span>管芯号:</span></p>
                                <input type="text" id="fourth-code">
                                <i>*请输入管芯号</i>
                            </div>
                            <div class="layout">
                                <p>*<span>验证码：</span></p>
                                <input type="text" id="fourth-captcha">
                                <div class="ms-code">
                                    <img class="ms-details" src="/captcha"/>
                                    <a href="javascript:;" class="changcode">
                                        看不清?楚换一张
                                    </a>
                                </div>
                                <i id="error-i" class="error-ms">*验证码错误</i>
                            </div>
                            <div class="code-conf-btn">
                                <span class="rest">重置</span>
                                <span class="confirm-btn next code-no-btn">验证</span>
                            </div>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div> 
   
    <!-- all -->
    <div class="quality-query-container all" id="all-detail">
        <div class="dashed-line"></div>
        <span class="show-title">查询结果</span>
        <!-- content start -->
        <div class="container">
            
            <div class="panel panel-default">
                  <div class="panel-heading">
                         车主信息
                  </div>
                  <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                                车主姓名：
                                <span class="all_owner_name"></span>
                            </div>
                            <div class="col-xs-6">
                                电话：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="all_owner_telephone"></span>
                            </div>
                            <div class="col-xs-6">
                                手机号：&nbsp;&nbsp;&nbsp;
                                <span class="all_owner_mobile"></span>
                            </div>
                            <div class="col-xs-6">
                                邮箱：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="all_owner_email"></span>
                            </div>
                            <div class="col-xs-6">
                                车主地址：
                                <span class="all_owner_address"></span>
                            </div>
                        </div>
                  </div>
            </div>
            
            <div class="panel panel-default">
                  <div class="panel-heading">
                         产品信息
                  </div>
                  <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>管芯号</th>
                                    <th>质保单号</th>
                                    <th>产品</th>
                                    <th>施工部位</th>
                                    <th>导购</th>
                                    <th>技师</th>
                                </tr>
                            </thead>
                            <tbody id="all_pro_list">
                            
                            </tbody>
                        </table>
                  </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    车辆信息
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            车牌号码：
                            <span class="all_car_number"></span>
                        </div>
                        <div class="col-xs-12">
                            车型：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="all_type_name"></span>
                        </div>
                        <div class="col-xs-12">
                            车架号码：
                            <span class="all_car_frame"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    施工信息
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            施工单位：
                            <span class="all_shop_name"></span>
                        </div>
                        <div class="col-xs-12">
                            施工日期：
                            <span class="all_construct_date"></span>
                        </div>
                        <div class="col-xs-12">
                            完成日期：
                            <span class="all_finished_date"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content end -->
    </div>
    <!-- result -->
    <div class="quality-query-container result" id="result-detail">  
        <div class="dashed-line"></div>
        <span class="show-title">查询结果</span>     
        <!-- content start -->
        <div class="container">
            <div class="panel panel-default">
                  <div class="panel-heading">
                         车主信息
                  </div>
                  <div class="panel-body">
                        车主姓名：
                        <span class="result_owner_name"></span>
                  </div>
            </div>
            
            <div class="panel panel-default">
                  <div class="panel-heading">
                         产品信息
                  </div>
                  <div class="panel-body">
                        质保卡号：
                        <span class="result_quality_code"></span>
                  </div>
            </div>
        </div>
        <!-- content end -->
    </div>
    <!-- search-detail -->
    <div class="quality-query-container detail" id="search-detail">
        <div class="dashed-line"></div>
        <span class="show-title">查询结果</span>
        <!-- content start -->
        <div class="container">
            <div class="panel panel-default">
                  <div class="panel-heading">
                         产品信息
                  </div>
                  <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>管芯号</th>
                                    <th>质保单号</th>
                                    <th>产品</th>
                                    <th>施工部位</th>
                                </tr>
                            </thead>
                            <tbody id="search_detail_pro_list">
                            
                               
                            </tbody>
                        </table>
                  </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    车辆信息
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            车型：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="search_detail_type_name"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    施工信息
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            施工单位：
                            <span class="search_detail_shop_name"></span>
                        </div>
                        <div class="col-xs-12">
                            施工日期：
                            <span class="search_detail_construct_date"></span>
                        </div>
                        <div class="col-xs-12">
                            完成日期：
                            <span class="search_detail_finished_date"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content end -->
        <a href="#" class="btn btn-block btn-bottom" id="back-to-codelist">返回</a>
    </div>
    <!-- search -->
    <div class="quality-query-container search" id="search">
        <div class="dashed-line"></div>
        <span class="show-title">请选择您的质保单号：</span>
        <!-- content start -->
        <div class="container">
            <div class="panel panel-default">
                  <div class="panel-heading">
                         产品信息
                  </div>
                  <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>质保单号</th>
                                </tr>
                            </thead>
                            <tbody id="search_pro_list">
                            <script type="text/template" id="search_tpl_list">
                            {@each _ as it,index}
                                <tr>
                                  <td data-id="${it.id}" class="code-list">${it.code}</td>    
                                </tr>
                            {@/each}                           
                            </script>
                               
                            </tbody>
                        </table>
                  </div>
            </div>
        </div>
        <!-- content end -->

    </div>
</div>

<script type="text/template" id="search_detail_tpl_list">
    {@each items as it , index}
        <tr >
            <td>${index - 0 + 1}</td>
            <td>${it.code}</td>
            <td class="J_quality_code">
                
            </td>
            <td >${it.package_name}</td>
            <td>${it.place_name}</td>
        </tr>
    {@/each}
</script>

<script type="text/template" id="all_tpl_list">
    {@each goods as it, index}
    <tr>
        <td>${index - 0 + 1}</td>
        <td>${it.code}</td>
        <td  class="all_quality_code"></td>
        <td >${it.package_name}</td>
        <td>${it.place_name}</td>
        <td>${it.sales}</td>
        <td>${it.technician}</td>
    </tr>
    {@/each}
</script>