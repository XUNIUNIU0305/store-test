<?php
/**
 * @var $this \yii\web\View
 */
use custom\assets\HeaderFooterSearchAsset;


HeaderFooterSearchAsset::register($this)->addJs('js/error.js')->addCss('css/error.css');

?>

<div class="container apx-not-found">
    <!-- Not found -->
    <div class="text-center">
        <img src="/images/new_icon.png">
        <h4>
            <strong>孤儿门店/加盟店无权购买，请联系您的线下运营商，谢谢配合！</strong>
            <small><strong class="high-lighted">无权访问</strong></small>
        </h4>
        <a href="/" class="btn btn-danger btn-block btn-lg">返回首页</a>
    </div>
</div>
