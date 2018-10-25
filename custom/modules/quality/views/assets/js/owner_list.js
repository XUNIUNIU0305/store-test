$(function() {
    // 获取查询列表
    var list = {
        tpl: $('#J_tpl_list').html(),
        getList: function() {
            requestUrl('/quality/quality-search/list-by-owner', 'GET','', function(data) {
                $('#J_owner_list').html(juicer(list.tpl, data))
            })
        }
    }
    list.getList()
})