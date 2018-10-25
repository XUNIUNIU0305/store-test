<?php
use yii\helpers\Html;
use supply\assets\MainAsset;

MainAsset::register($this)->addJs(isset($this->params['js']) ? $this->params['js'] : null)->addCss(isset($this->params['css']) ? $this->params['css'] : null);
?>

<?php $this->beginContent('@supply/views/layouts/global.php'); ?>

<!-- banner start -->
<nav class="apx-top-nav navbar navbar-default navbar-lg top-fixed">
    <div class="container">
        <div class="row">
            <!-- mobile icon -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div class="row">
                    <a class="navbar-brand" href="/">
                        <img src="/images/new_icon.png" class="logo-icon">
                    </a>
                    <ul class="nav navbar-nav navbar-right with-seperator">
                        <li>
                            <a href="#">商城首页</a>
                        </li>
                        <li>
                            <a href="#">在线帮助</a>
                        </li>
                        <li>
                            <a href="#">店铺信息修改</a>
                        </li>
                        <li>
                            <a href="/index/logout">退出</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="top-fixed-placeholder"></div>

<?= $content ?>

<?php $this->endContent(); ?>


