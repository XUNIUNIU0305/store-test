<?php
$this->params = ['js' => 'js/itemrank.js', 'css' => 'css/itemrank.css'];
?>

<div class="admin-main-wrap">
    <div class="admin-ranking-container">
        <!--nav-->
        <ul class="nav nav-tabs datepicker-nav">
            <li><strong>单品排名</strong></li>
            <li class="pull-right">
                <div class="query_time in">
                    <span>日期:</span>
                    <div class="input-group">
                        <input type="text" class="form-control date-picker J_search_time" value="">
                        <span class="input-group-btn J_date_btn">
                                <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                    </div>
                    <button class="btn btn-default" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </li>
        </ul>
        <div class="row">
            <div class="col-xs-6">
                <!--single panel start-->
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">单品销量排行榜</h3>
                    </div>
                    <div class="panel-body">
                    	<script type="text/template" id="J_tpl_rank">
            				{@each _ as it, index}
                                <tr>
                                    <td><i class="high-lighted">${index - 0 + 1}</i></td>
                                    <td><a href="/count/item-rank/detail?day=${it.time}&type=${it.type}&p_id=${it.product_id}" class="area_sales"><img src="http://images.9daye.com.cn/${it.filename}" class="img-responsive"></a></td>
                                    <td><a href="/count/item-rank/detail?day=${it.time}&type=${it.type}&p_id=${it.product_id}" class="area_sales">${it.title}</a></td>
                                    <td>${it.value}</td>
                                </tr>
            				{@/each}
                    	</script>
                        <table class="table table-fix">
                            <thead>
                                <tr>
                                    <th width="66">排名</th>
                                    <th width="96"></th>
                                    <th>商品名</th>
                                    <th width="110">数量</th>
                                </tr>
                            </thead>
                            <tbody id="J_count_rank">
                            </tbody>
                        </table>

                    </div>
                </div>
                <!--single panel end-->
            </div>
            <div class="col-xs-6">
                <!--single panel start-->
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">单品销售额排行榜</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-fix">
                            <thead>
                                <tr>
                                    <th width="66">排名</th>
                                    <th width="96"></th>
                                    <th>商品名</th>
                                    <th width="110">金额</th>
                                </tr>
                            </thead>
                            <tbody id="J_sum_rank">
                            </tbody>
                        </table>

                    </div>
                </div>
                <!--single panel end-->
            </div>
            <div class="col-xs-6">
                <!--single panel start-->
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">取消数量排行榜</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-fix">
                            <thead>
                                <tr>
                                    <th width="66">排名</th>
                                    <th width="96"></th>
                                    <th>商品名</th>
                                    <th width="110">数量</th>
                                </tr>
                            </thead>
                            <tbody id="J_count_cancel">
                            </tbody>
                        </table>

                    </div>
                </div>
                <!--single panel end-->
            </div>
            <div class="col-xs-6">
                <!--single panel start-->
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">取消金额排行榜</h3>
                    </div>
                    <div class="panel-body" >
                        <table class="table table-fix">
                            <thead>
                                <tr>
                                    <th width="66">排名</th>
                                    <th width="96"></th>
                                    <th>商品名</th>
                                    <th width="110">金额</th>
                                </tr>
                            </thead>
                            <tbody id="J_sum_cancel">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--single panel end-->
            </div>
        </div>
    </div>
</div>
