<?php
use yii\helpers\Html;

$this->params = ['js' => 'js/price.js', 'css' => 'css/price.css'];
?>

<div class="apx-seller-attr-wrap pull-left" id="J_addAttr">
    <a  data-toggle="collapse" href="#form_attr_box_1" id="J_toggle" class="btn btn-default"><i class="glyphicon glyphicon-plus text-success"></i> 添加属性价格</a>
    <div class="collapse" id="form_attr_box_1">
    <div  class="apx-seller-attr-box text-center">
       <form id="J_attr_form">
            <div class="col-xs-3">
                <input type="text" class="form-control" placeholder="输入分类类别">
            </div>
            <span class="btn btn-danger pull-right add-sort-name">添加</span>
            <div class="clearfix"></div>
            <div class="clearfix sort-container">
                <div class="col-xs-3">
                    <input type="text" class="form-control" placeholder="输入分类名称">
                </div>
            </div>
          
            <a href="javascript:;" id="J_getAttr" class="btn btn-warning btn-lg">确认提交</a>
            <script type="text/template" id="J_tpl_attr">
                <div class="J_attr_box" data-id="${_.id}">
                    <p><span>${_.attrs[0]}</span>:</p>
                    <div class="apx-seller-attr-box">
                        <div class="btn-toggle">
                            {@each _.attrs as it, index}
                                {@if index != 0}
                                    <label class="btn btn-default btn-lg text-ellipsis" title="${it}">${it}</label>
                                {@/if}
                            {@/each}
                        </div>
                        <a href="javascript:;" class="can-delete J_delAttr">删除</a>
                    </div>
                </div>
            </script>
       </form>
    </div>
    </div>
</div>
<div id="J_table_box" class="apx-seller-attr-wrap pull-left">
    <script type="text/template" id="J_tpl_table">
        <table class="table table-bordered text-center">
            <tr class="text-center active">
            {@each attrName as it}
                <td><div class="text-ellipsis" title="${it}">${it}</div></td>      
            {@/each}
                <td width="100">未含税价格</td>
                <td width="100">指导价</td>
                <td width="100">单价</td>
                <td width="100">原价</td>
                <td width="100">原指导价</td>
                <td width="100">数量</td>
                <td width="75">商品内部ID</td>
                <td width="75">商品条形码</td>
            </tr>
            {@each i in range(0,row)}
                <tr>
                    {@each allAttr as x, index}
                        {@if x[i] != undefined}
                            <td rowspan="${[x, row]|rowspan_build}"><div class="text-ellipsis" title="${x[i]}">${x[i]}</div></td>   
                        {@/if}
                    {@/each}
                    <td>
                        <div class="col-xs-10">
                            <input class="form-control cost-fill J_price" maxlength="8" type="text">
                        </div>
                        <div class="col-xs-2">元</div>
                    </td>
                    <td>
                        <div class="col-xs-10">
                            <input class="form-control guidance-fill J_price" maxlength="9" type="text">
                        </div>
                        <div class="col-xs-2">元</div>
                    </td>
                    <td>
                        <div class="col-xs-10">
                            <input class="form-control price-fill" data-toggle="tooltip" data-placement="bottom" title="此价格为系统自动生成，不可更改" type="text" disabled>
                        </div>
                        <div class="col-xs-2">元</div>
                    </td>
                    <td>
                        <div class="col-xs-10">
                            <input class="form-control original-fill J_price" maxlength="8" type="text">
                        </div>
                        <div class="col-xs-2">元</div>
                    </td>
                    <td>
                        <div class="col-xs-10">
                            <input class="form-control original-guidance-fill J_price" maxlength="8" type="text">
                        </div>
                        <div class="col-xs-2">元</div>
                    </td>
                    <td>
                        <div class="col-xs-10">
                            <input class="form-control number-fill" maxlength="8" type="text">
                        </div>
                        <div class="col-xs-2">份</div>
                    </td>
                    <td>
                        <input class="form-control ownid" type="text">
                    </td>
                    <td>
                        <input class="form-control barcode" type="text">
                    </td>
                </tr>
            {@/each}
    </script>
</div>
</div>
<div class="apx-seller-attr-wrap pull-left">
    <div class="col-sm-offset-5 col-sm-2">
        <button type="submit" class="btn btn-primary btn-block">发布</button>
    </div>
</div>