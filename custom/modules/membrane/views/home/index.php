<?php
/**
 * @var $this \yii\web\View
 */
use custom\modules\membrane\assets\MembraneAsset;

$this->title = '购买';
MembraneAsset::register($this)->addJs('js/index.js')->addCss('css/index.css');
?>
<div class="modal fade" tabindex="-1" role="dialog" id="product-detail">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <img src="/images/membrane/new/1-01.jpg" alt=""/>
        <img src="/images/membrane/new/2-01.jpg" alt=""/>
        <img src="/images/membrane/new/3-01.jpg" alt=""/>
        <img src="/images/membrane/new/4-01.jpg" alt=""/>
        <img src="/images/membrane/new/5-01.jpg" alt=""/>
        <img src="/images/membrane/new/6-01.jpg" alt=""/>
        <img src="/images/membrane/new/7-01.jpg" alt=""/>
        <img src="/images/membrane/new/8-01.jpg" alt=""/>
        <img src="/images/membrane/new/9-01.jpg" alt=""/>
        <img src="/images/membrane/new/10-01.jpg" alt=""/>
        <img src="/images/membrane/new/11-01.jpg" alt=""/>
        <img src="/images/membrane/new/12-01.jpg" alt=""/>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="apex-product-detail">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <img src="/images/membrane/apex/1.jpg" alt=""/>
        <img src="/images/membrane/apex/2.jpg" alt=""/>
        <img src="/images/membrane/apex/3.jpg" alt=""/>
        <img src="/images/membrane/apex/4.jpg" alt=""/>
        <img src="/images/membrane/apex/5.jpg" alt=""/>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="price-detail">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <img src="/images/membrane/price_detail_1.png" alt=""/>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="apex-price">
  <div class="modal-dialog" role="document">
    <div class="modal-content text-center" style="width: 20%;">
        <img src="/images/membrane/apex_price14.jpg" alt=""/>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="tianyu-price" style="top: 20%;">
  <div class="modal-dialog" role="document">
    <div class="modal-content text-center" style="width: 20%;">
        <img src="/images/membrane/ty_price.jpg" alt=""/>
    </div>
  </div>
</div>
<div class="apex-membran">
    <div class="container">
        <ul id="myTab" class="nav nav-tabs">
            <li class="active" data-type="1"><a href="#imperial-court" data-toggle="tab">天御车膜</a></li>
            <li data-type="2" class="J_apex_tab hidden"><a href="#european-park" data-toggle="tab">欧帕斯车膜</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <!-- 天御车膜 -->
            <div class="tab-pane fade in active" id="imperial-court">
                <div class="tab-pane-left">
                    <img src="/images/special_supply_membrane/ty.jpg" alt="">
                    <a href="#" data-toggle="modal" data-target="#product-detail">了解<br/>详情</a>
                </div>
                <div class="tab-pane-right">
                    <div class="tab-pane-right-tit">
                        <h3>天御特效护眼护肤功能膜</h3>
                        <div class="mabenchmark-price">
                            <span>基&nbsp;准&nbsp;价&nbsp;&nbsp;</span>
                            <div class="total-price">￥<span id="benchmark-price-1">1180.00</span></div>
                            <a href="#" data-toggle="modal" data-target="#tianyu-price">售价参考</a>
                        </div>
                    </div> 
                    <div class="tab-pane-right-setMeal">
                        <span>选择套餐</span>
                        <ul class="nav nav-tabs" id="J_tianyu_package">
                            <li class="active" data-type="1"><a href="#setMeal1" data-toggle="tab">Z8+G15</a></li>
                            <li data-type="2"><a href="#setMeal2" data-toggle="tab">Z8+G20</a></li>
                            <li data-type="3"><a href="#setMeal3" data-toggle="tab">Z8+G30</a></li>
                            <li data-type="4"><a href="#setMeal4" data-toggle="tab">自定义</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane-right-cont">
                        <div class="coefficient-cont">
                            <div class="coefficient-sele">
                                <span>选择系数</span>
                                <select name="coefficient" class="coefficient" id="coefficient_1">
                                    <option value="-1">请选择系数</option>
                                    <option value="1">1.00系数</option>
                                    <option value="2">1.08系数</option>
                                    <option value="3">1.12系数</option>
                                    <option value="4">1.20系数</option>
                                    <option value="31">1.50系数</option>
                                    <option value="5">2.00系数</option>
                                </select>
                                <a href="#" data-toggle="modal" data-target="#price-detail">如何选定系数</a>
                            </div>
                            <div class="coefficient-cont-number">
                                <span>采购数量</span>
                                <div class="calculation-btn btn-disabled" id="ty-del-number">-</div>
                                <div id="ty-purchase-quantity" data-val="1">1</div>
                                <div class="calculation-btn" id="ty-add-number">+</div>
                            </div>
                            <div class="mabenchmark-price">
                                <span>采&nbsp;购&nbsp;价&nbsp;&nbsp;</span>
                                <div class="total-price">￥<span id="purchase-price-1">0.00</span></div>
                            </div>
                        </div>
                        <div class="parameter-cont tab-content">
                            <ul class="tab-pane fade in active" id="setMeal1">
                                <li>前档：<span>Z8</span></li>
                                <li>后档：<span>G15</span></li>
                                <li>左前档：<span>G15</span></li>
                                <li>右前档：<span>G15</span></li>
                                <li>左后档：<span>G15</span></li>
                                <li>右后档：<span>G15</span></li>
                            </ul>
                            <ul class="tab-pane fade" id="setMeal2">
                                <li>前档：<span>Z8</span></li>
                                <li>后档：<span>G20</span></li>
                                <li>左前档：<span>G20</span></li>
                                <li>右前档：<span>G20</span></li>
                                <li>左后档：<span>G20</span></li>
                                <li>右后档：<span>G20</span></li>
                            </ul>
                            <ul class="tab-pane fade" id="setMeal3">
                                <li>前档：<span>Z8</span></li>
                                <li>后档：<span>G30</span></li>
                                <li>左前档：<span>G30</span></li>
                                <li>右前档：<span>G30</span></li>
                                <li>左后档：<span>G30</span></li>
                                <li>右后档：<span>G30</span></li>
                            </ul>
                            <ul class="tab-pane fade" id="setMeal4">
                                <li>
                                    <span class="car-gear">前档：</span>
                                    <span>
                                        <select name="frontGear" id="frontGear" data-name="前档" data-id="1">
                                            <option value="1">Z8</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span class="car-gear">后档：</span>
                                    <span>
                                        <select name="rearBumper" id="rearBumper" data-name="后档" data-id="2">
                                            <option value="-1">请选择</option>
                                            <option value="2">G15</option>
                                            <option value="3">G20</option>
                                            <option value="4">G30</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span class="car-gear">左前档：</span>
                                    <span>
                                        <select name="leftFrontFile" id="leftFrontFile" data-name="左前档" data-id="4">
                                            <option value="-1">请选择</option>
                                            <option value="2">G15</option>
                                            <option value="3">G20</option>
                                            <option value="4">G30</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span class="car-gear">右前档：</span>
                                    <span>
                                        <select name="rightFrontFile" id="rightFrontFile" data-name="右前档" data-id="5">
                                            <option value="-1">请选择</option>
                                            <option value="2">G15</option>
                                            <option value="3">G20</option>
                                            <option value="4">G30</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span class="car-gear">左后档：</span>
                                    <span>
                                        <select name="leftRearGear" id="leftRearGear" data-name="左后档" data-id="3">
                                            <option value="-1">请选择</option>
                                            <option value="2">G15</option>
                                            <option value="3">G20</option>
                                            <option value="4">G30</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span class="car-gear">右后档：</span>
                                    <span>
                                        <select name="rightRearGear" id="rightRearGear" data-name="右后档" data-id="6">
                                            <option value="-1">请选择</option>
                                            <option value="2">G15</option>
                                            <option value="3">G20</option>
                                            <option value="4">G30</option>
                                        </select>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane-right-remarks">
                        <label for="remarks" class="remarks">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</label>
                        <div class="remarks-message"><input type="text" maxlength="100" id="remarks" placeholder="请填写你的信息"></div>
                    </div>         
                </div>  
            </div>
            <!-- 欧帕斯车膜 -->
            <div class="tab-pane fade" id="european-park">
                <div class="tab-pane-left">
                    <img src="/images/special_supply_membrane/ops.jpg" alt="">
                    <a href="#" data-toggle="modal" data-target="#apex-product-detail">了解<br/>详情</a>
                </div>
                <div class="tab-pane-right">
                    <div class="tab-pane-right-tit">
                        <h3>欧帕斯全功能隔热安全膜</h3>
                        <div class="mabenchmark-price">
                            <span>基&nbsp;准&nbsp;价&nbsp;&nbsp;</span>
                            <div class="total-price">￥<span id="benchmark-price-2"></span></div>
                            <a href="#" id="ops-reference" data-toggle="modal" data-target="#apex-price">售价参考</a>
                        </div>
                    </div> 
                    <div class="tab-pane-right-setMeal">
                        <span>选择套餐</span>
                        <ul class="nav nav-tabs european-package" id="european-package">
                            <li class="active" data-type="1"><a href="#europeanPark1" data-toggle="tab" data-value="13800.00">吉祥套餐</a></li>
                            <li data-type="2"><a href="#europeanPark2" data-toggle="tab" data-value="11800.00">如意套餐</a></li>
                            <li data-type="3"><a href="#europeanPark3" data-toggle="tab" data-value="7800.00">幸福套餐</a></li>
                            <li data-type="4"><a href="#europeanPark4" data-toggle="tab" data-value="4980.00">平安套餐</a></li>
                            <li data-type="5"><a href="#europeanPark5" data-toggle="tab" data-value="3980.00">开心套餐</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane-right-cont">
                        <div class="coefficient-cont">
                            <div class="coefficient-sele">
                                <span>选择系数</span>
                                <select name="coefficient" class="coefficient" id="coefficient_2">
                                    <option value="-1">请选择系数</option>
                                    <option value="1">1.00系数</option>
                                    <option value="2">1.08系数</option>
                                    <option value="3">1.12系数</option>
                                    <option value="4">1.20系数</option>
                                    <option value="5">1.50系数</option>
                                    <option value="6">2.00系数</option>
                                </select>
                                <a href="#" data-toggle="modal" data-target="#price-detail">如何选定系数</a>
                            </div>
                            <div class="coefficient-cont-number">
                                <span>采购数量</span>
                                <div class="calculation-btn btn-disabled" id="ops-del-number" data-val="1">-</div>
                                <div id="ops-purchase-quantity" data-val="1">1</div>
                                <div class="calculation-btn" id="ops-add-number" data-val="2">+</div>
                            </div>
                            <div class="mabenchmark-price">
                                <span>采&nbsp;购&nbsp;价&nbsp;&nbsp;</span>
                                <div class="total-price">￥<span id="purchase-price-2">0.00</span></div>
                            </div>
                        </div>
                        <div class="parameter-cont tab-content">
                            <ul class="tab-pane fade in active" id="europeanPark1">
                                <li>前档：<span>U9</span></li>
                                <li>后档：<span>U7</span></li>
                                <li>左前档：<span>U7</span></li>
                                <li>右前档：<span>U7</span></li>
                                <li>左后档：<span>U7</span></li>
                                <li>右后档：<span>U7</span></li>
                            </ul>
                            <ul class="tab-pane fade" id="europeanPark2">
                                <li>前档：<span>U9</span></li>
                                <li>后档：<span>I8</span></li>
                                <li>左前档：<span>I8</span></li>
                                <li>右前档：<span>I8</span></li>
                                <li>左后档：<span>I8</span></li>
                                <li>右后档：<span>I8</span></li>
                            </ul>
                            <ul class="tab-pane fade" id="europeanPark3">
                                <li>前档：<span>U9</span></li>
                                <li>后档：<span>R5</span></li>
                                <li>左前档：<span>R5</span></li>
                                <li>右前档：<span>R5</span></li>
                                <li>左后档：<span>R5</span></li>
                                <li>右后档：<span>R5</span></li>
                            </ul>
                            <ul class="tab-pane fade" id="europeanPark4">
                                <li>前档：<span>U9</span></li>
                                <li>后档：<span>V5</span></li>
                                <li>左前档：<span>V5</span></li>
                                <li>右前档：<span>V5</span></li>
                                <li>左后档：<span>V5</span></li>
                                <li>右后档：<span>V5</span></li>
                            </ul>
                            <ul class="tab-pane fade" id="europeanPark5">
                                <li>前档：<span>U9</span></li>
                                <li>后档：<span>E5</span></li>
                                <li>左前档：<span>E5</span></li>
                                <li>右前档：<span>E5</span></li>
                                <li>左后档：<span>E5</span></li>
                                <li>右后档：<span>E5</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane-right-remarks">
                        <label for="remarks" class="remarks">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</label>
                        <div class="remarks-message"><input type="text" id="remarks-2" placeholder="请填写你的信息"></div>
                    </div>               
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h2 class="fir-h">选择收货地址</h2>
        <div class="address-box">
            <div id="address-box" class="clearfix"></div>
            <div class="address-handle">
                <a class="address-all hidden" href="javascript:void();">全部地址</a>
                <a class="address-add" href="/account/address">新增收货地址</a>
            </div>
        </div>
    </div>
    <div class="container">
        <h2 class="fir-h">选择支付方式</h2>
        <div class="apx-cart-pay-box clearfix J_pay_box" data-toggle="buttons">
            <div class="media">
                <div class="media-left">
                    <img src="/images/cart/rest.png">
                </div>
                <div class="media-body media-middle">
                    <label class="btn btn-default btn-sm payment-btn"  data-payment="1">
                        <input type="radio" name="pay_way"  autocomplete="off"> 账户余额付款
                    </label>
                    <p class="media-heading text-danger">* 账户当前余额：<span id="balance">0.00</span>元 </p>
                </div>
            </div>
            <div class="media">
                <div class="media-left">
                    <img src="/images/cart/zhifubao.png">
                </div>
                <div class="media-body media-middle">
                    <label class="btn btn-default btn-sm payment-btn" data-payment="2">
                        <input type="radio" name="pay_way" autocomplete="off">支付宝付款
                    </label>
                    <p class="media-heading text-danger">* 当您的订单金额大于5000元时，推荐先向支付宝（余额宝）预充金额，再使用余额宝进行消费 <a href="" onclick="window.open('https://cshall.alipay.com/lab/cateQuestion.htm?cateId=237763&pcateId=237707')" target="_blank"><i class="glyphicon glyphicon-question-sign"></i></a></p>
                </div>
            </div>
            <div class="media">
                <div class="media-left">
                    <img src="/images/cart/bank-icon.png">
                </div>
                <div class="media-body media-middle">
                    <div class="flex-box">
                        <label class="btn btn-default btn-sm payment-btn hidden" id="J_bank_pay" data-payment="4">
                            <input type="radio" name="pay_way" autocomplete="off">南行-个人网关
                        </label>
                        <label class="btn btn-default btn-sm payment-btn"  data-payment="5">
                            <input type="radio" name="pay_way" autocomplete="off">南行-企业网关
                        </label>
                    </div>
                    <p class="media-heading text-danger">* 请确保您的银行卡已开通在线支付功能，详情咨询各银行，开通流程不同</p>
                </div>
            </div>
            <div class="media">
                <div class="media-left">
                    <img src="/images/cart/abcbank.png">
                </div>
                <div class="media-body media-middle">
                    <label class="btn btn-default btn-sm payment-btn" id="J_abcbank_pay" data-payment="6">
                        <input type="radio" name="pay_way" autocomplete="off">农行-网银
                    </label>
                    <p class="media-heading text-danger"> </p>
                </div>
            </div>
        </div>
        <div class="sub-order">
            <div class="order-msg">
                <div class="pro-msg clearfix">
                    <div class="pro-account">共<b id="total-num">0</b>件商品</div>
                    <div class="pro-money">
                        <p>应付总额:<span id="total-price">￥ 0.00</span></p>
                        <p class="receive-money hidden">应付总额:<span id='real-price'>￥ 0.00</span></p>
                    </div>
                </div>
                <div class="order-address" id="send-to"></div>
            </div>
        </div>
        <div class="sub-btn">
            <a class="btn" id="submit">提交订单</a>
        </div>
    </div>
</div>

<div class="apx-modal-admin-alert modal fade" id="apxModalPass" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close hidden" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <p class="text-center h3" id="J_bank_success">订单生成成功...</p>
                        <p class="text-center h3 hidden">已支付完成？</p>
                        <p class="h4 text-danger text-center hidden">支付返回信息存在300秒左右的延迟，请勿重复支付订单！</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger hidden" data-dismiss="modal">是</button>
                <button type="button" class="btn btn-lg btn-default hidden" data-dismiss="modal">否</button>
                <button type="button" class="btn btn-lg btn-default" id="J_open_window">前往支付</button>
            </div>
        </div>
    </div>
</div>


<script type="text/template" id="J_tpl_item">
    <div class="product" data-price="${price}">
        <div class="pro-descip">
            <p class="pro-name">${title}</p>
            <div class="pro-text">
                <img src="/images/membrane/product/item.png" alt="">
                <div class="pro-txt">
                    <p>在具有护眼护肤的特效功能的同时，沿袭了传统膜隔热、防爆、防眩等基本功能。</p>
                    <p class="red-txt">￥<span>${price}</span></p>
                </div>
            </div>
        </div>
        <div class="pro-form">
            <ul class="form-content clearfix">
                {@each blocks as label}
                    <li>
                        <label for="" class="two-txt">${label.label.name}：</label>
                        <select class="form-select" data-id="${label.label.id}">
                        {@each label.options as option}<option value="${option.id}">${option.name}</option>{@/each}
                        </select>
                    </li>
                {@/each}
            <li>
                <label for="" class="two-txt">备注：</label>
                <input type="text" class="form-remark" placeholder="请填写您的备注信息"><input type="hidden" class="form-id" value="${id}"/>
            </li>
            </ul>
            <button class="delete-btn btn">删除</button>
        </div>
    </div>
</script>
