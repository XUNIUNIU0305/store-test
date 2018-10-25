<?php
$this->params = ['css' => 'css/main.css', 'js' => 'js/main.js'];
?>

<!-- header start -->
<header class="business-frame-header">
    <nav class="navbar navbar-default">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <i class="glyphicon glyphicon-user"></i><span id="J_user_name"></span>
            </li>
            <li>
                <a href="/main/logout"><i class="glyphicon glyphicon-off"></i>退出</a>
            </li>
        </ul>
    </nav>
    <nav class="sub-nav">
        <div class="pull-left">
            ◀
        </div>
        <ul class="list-inline" id="J_tag_list">
            <li class="first active" data-url="/overview">总  览</li>
        </ul>
        <div class="pull-right">
            ▶
        </div>
    </nav>
</header>
<!-- header end -->
<!-- aside nav start -->
<aside class="business-frame-aside">
    <div class="aside-logo">
        <img alt="logo" src="/images/logo_white.png" height="24">
    </div>
    <div id="J_menu_list">
        <script type="text/template" id="J_tpl_menu">
            {@each _ as it}
                <div class="aside-list">
                    <a data-toggle="collapse" href="#aside_panel_${it.id}" class="collapsed">
                        ${it.name}<i class="glyphicon glyphicon-chevron-down"></i>
                    </a>
                    <div class="collapse" id="aside_panel_${it.id}">
                        <ul class="list-unstyled clearfix">
                            {@each it.secondary as second}
                                <li class="J_menu_url" data-id="${second.id}" data-url="${second.url}"><a href="javascript:;">${second.name}</a></li>
                            {@/each}
                        </ul>
                    </div>
                </div>
            {@/each}
        </script>
    </div>
</aside>
<!-- aside nav end -->
<!-- main area start -->
<div class="business-frame-main">
    <iframe src="/overview" class="active" frameborder="0" width="98%" height="100%" marginheight="0" marginwidth="0"></iframe>
</div>