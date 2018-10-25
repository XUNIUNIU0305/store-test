<?php
/**
 * Created by PhpStorm.
 * User: kun
 * Date: 2018/8/22
 * Time: 18:21
 */
$this->params = ['js' => 'js/activity-rules.js', 'css' => 'css/activityRules.css'];
$this->title = '拼购活动规则';
?>
<div class="container">
<!-- 拼购规则详情 -->
    <div class="count-down-rule-detail-wrap" id="count-down-rule-detail-wrap">
        <div class="count-down-rule-detail hidden" id="songhuo">
            <div class="rule-detail-title">
                送货拼购规则：
            </div>
            <div class="rule-detail-mess">
                <div class="rule-detail-mess-item">1.所有级别账户均可发起送货拼购或参加拼购；</div>
                <div class="rule-detail-mess-item">2.选择送货拼购商品，完成支付，即视为开团成功；</div>
                <div class="rule-detail-mess-item">3.拼购链接分享到朋友圈或指定朋友；</div>
                <div class="rule-detail-mess-item">4.参团人点击分享链接进行拼团（如人数已满或拼购失效，可自行开团）；</div>
                <div class="rule-detail-mess-item">5.参团人确认商品信息，完成支付，即视为参团成功；</div>
                <div class="rule-detail-mess-item">6.若在规定时间内未达拼购人数，款项将自动原路返回；</div>
                <div class="other">
                    <div class="rule-detail-mess-item other-mess">其他说明：</div>
                    <div class="rule-detail-mess-item">一旦开团成功或参团成功，无法取消订单，带来不便请谅解。</div>
                </div>
                        
            </div>
        </div>
        <div class="count-down-rule-detail hidden" id="ziti">
            <div class="rule-detail-title">
                自提拼购规则：
            </div>
            <div class="rule-detail-mess">
                <div class="rule-detail-mess-item">1.只有服务商才能发起自提拼购；</div>
                <div class="rule-detail-mess-item">2.服务商选择自提拼购商品，完成支付，即视为开团成功；</div>
                <div class="rule-detail-mess-item">3.拼购链接分享给指定朋友；</div>
                <!-- <div class="rule-detail-mess-item">4.参团人点击分享链接进行拼团（如人数已满或拼购失效，可自行开团）；</div> -->
                <div class="rule-detail-mess-item">4.参团人点击分享链接进行拼购（如人数已满，请参加其他未满拼购）；</div>
                <div class="rule-detail-mess-item">5.参团人确认商品信息，完成支付，即视为参团成功；</div>
                <div class="rule-detail-mess-item">6.若在规定时间内未达拼购人数，则视为拼购失败，款项将自动原路返回；</div>
                <!-- 提货流程 -->
                <div class="pickup-flow">
                    <div class="flow-title">
                        提货流程
                    </div>
                    <div class="flow-mess">
                        <div class="rule-detail-mess-item">1.一旦自提拼团成功，可在“我的拼购—自提拼购提 货”中查看该订单提货码，预约服务商，凭提货码提货；</div>
                        <div class="rule-detail-mess-item">2.服务商核销自提商品时，进入“我的拼购——自提拼购提货核销”，输入提货人出示的提货码，完成提货；</div>
                        <div class="rule-detail-mess-item">3. 如一次不能全部提完，则系统将生成新的提货码，直至全部提完为止，原提货码将无效；</div>
                    </div>
                </div>
                <div class="other">
                    <div class="rule-detail-mess-item other-mess">其他说明：</div>
                    <div class="rule-detail-mess-item">一旦开团成功或参团成功，无法取消订单，带来不便请谅解。</div>
                </div>
                        
            </div>
        </div>
    </div>           
</div>