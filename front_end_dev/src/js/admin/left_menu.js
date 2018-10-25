$(function () {
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
        })
        .on('click.left_menu_click', '#aside-menu .child-list', function (e) {
            hideSideMenu();
        });
})
