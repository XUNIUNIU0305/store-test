<?php
use yii\helpers\Html;
use admin\assets\MenuAsset;

MenuAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@admin/views/layouts/global.php'); ?>
<!-- header start -->
<header class="admin-frame-header">
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <a class="navbar-brand" href="#top">
                <img alt="logo" src="../images/logo_white.png" height="24">
            </a>
        </div>
        <ul class="nav navbar-nav navbar-left">
            <li class="J_iframe_name">管理控制中心</li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li>
            </li>
            <li>
                <a href="/index/logout">退出</a>
            </li>
        </ul>
    </nav>
</header>
<!-- header end -->
<!-- aside nav start -->
<aside class="admin-frame-aside" id="aside-menu">
    <div id="J_Amenu">
        <script type="text/template" id="J_tpl_Amenu">
            <ul class="nav nav-pills nav-stacked">
                {@each _ as it}
                    <li class="collapsed" data-parent="#aside-menu" data-toggle="collapse" href="#aside_menu_${it.id}" data-id="${it.id}">
                        <a href="javascript:void(0)">${it.title}</a>
                    </li> 
                {@/each}
        </script>
    </div>
    <div id="J_Smenu">
        <script type="text/template" id="J_tpl_Smenu">
            <ul id="aside_menu_${_.id}" class="list-unstyled child-list collapse" data-id="${_.id}">
                {@each _.dat as it}
                    <li><a href="${it.url}" target="iframe">${it.title}</a></li>
                {@/each}
            </ul>
        </script>
    </div>
</aside>
<!-- aside nav end -->
<!-- main area start -->
<div class="admin-frame-main">
    <?= $content ?>
</div>

<?php $this->endContent(); ?>
