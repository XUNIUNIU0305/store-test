$(function () {
    //获取顶级菜单
    function getAmenu() {
        function amenuCB(data) {
            var tpl = $('#J_tpl_Amenu').html();
            var menu = juicer(tpl, data);
            $('#J_Amenu').html(menu);
        }
        requestUrl('/menu/top', 'GET', '', amenuCB)
    }
    getAmenu();
    //获取二级菜单
    var Stpl = $('#J_tpl_Smenu').html();
    var compiled_tpl = juicer(Stpl);

    function getSmenu(id, $src) {
        var data = {top_menu_id: id};
        function smenuCB(data) {
            var adata = {
                id: id,
                dat: data
            }
            var html = compiled_tpl.render(adata);
            $('#J_Smenu').append(html);
            var forceLayout = $($src.attr('href')).height();
            $($src.attr('href')).addClass('in');
        }
        requestUrl('/menu/secondary', 'GET', data, smenuCB)
    }
    //绑定二级菜单事件
    $('#J_Amenu').on('mouseenter.getSmenu', 'li', function () {
        var $this = $(this);
        var id = $(this).data('id');
        $(this).children('a').addClass('hover');
        if ($('#J_Smenu ul[data-id="' + id + '"]').length > 0) return;
        show_timer = setTimeout(function() {
            getSmenu(id, $this);
        }, 300)
    }).on('mouseleave.getSmenu', 'li', function() {
        clearTimeout(show_timer)
    })
    $('#J_Smenu').on('click', 'li a', function() {
        $('.J_iframe_name').html($(this).html());
        hideSideMenu();
    })

    var aside_menu_timer;

    function hideSideMenu(){
        $('#aside-menu .child-list').removeClass('in');
        $('#aside-menu .collapsed a').removeClass('hover');
    }

    $('body')
        // 取消默认行为
        .on('click.left_menu_click', '[data-parent="#aside-menu"]', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
        })
        // 鼠标enter显示
        .on('mouseenter.left_menu_click', '[data-parent="#aside-menu"]', function (e) {
            clearTimeout(aside_menu_timer);
            hideSideMenu();
            $($(this).attr('href')).addClass('in');
            $(this).children('a').addClass('hover');
        })
        // 鼠标leave隐藏
        .on('mouseleave.left_menu_click', '[data-parent="#aside-menu"]', function (e) {
            var _this = this;
            aside_menu_timer = setTimeout(function(){
                $('#aside-menu .child-list').removeClass('in');
                $('#aside-menu .collapsed a').removeClass('hover');
            }, 400)
        })
        .on('mouseenter.left_menu_click', '#aside-menu .child-list', function (e) {
            clearTimeout(aside_menu_timer);
        })
        .on('mouseleave.left_menu_click', '#aside-menu .child-list', function (e) {
            hideSideMenu();
        });
})