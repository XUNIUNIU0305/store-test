$(function(){
    //获取地址列表模板
    var address_list = $('#J_tpl_address').html();

    function loadAddressList(){
        var url="/member/address/get-address-list";
        function success(data){
            var compiled_edit = juicer(address_list);
            var html = compiled_edit.render(data);
            $('.J_address_list').html(html);
            bindEvents();//绑定事件
        }
        function errorCB(data){
            alert(data.data.errMsg);
        }
        //提交请求
        requestUrl(url,'get','',success,errorCB);
    }



    //绑定事件
    function bindEvents(){
        /*
        * Author:Jiangyi
        * Date:2017/05/23
        * Desc:设置默认地址
         */
        $(".J_set_default").off("click").on("click",function(){
            if ($(this).hasClass('active')) return;
            var url="/member/address/set-default";
            var _data={
                id:$(this).data("id"),
            };
            function success(data){
                loadAddressList();
            }
            function errorCB(data){
                alert(data.data.errMsg);
            }
            requestUrl(url,'get',_data,success,errorCB);

        });

        /*
         * Author:JiangYi
         * Date:2017/05/23
         * Desc:删除地址信息
         */
        $(".J_del_address").off("click").on("click",function(){

            var url='/member/address/delete';
            var _data={
                id:$(this).data("id"),
            };
            if(!confirm("您是否确认删除该地址信息?")){
                return false;
            }
            function success(data){
                loadAddressList();
            }
            function errorCB(data){
                alert(data.data.errMsg);
            }
            requestUrl(url,'get',_data,success,errorCB);

        });

        //编辑地址
        var flag;
        $('.J_edit_address').on('click',function(){
            if($(this).prev().hasClass('active')){
                flag = true;
            }else {
                flag = false;
            }
            var url='/member/address/add?'; 
            window.location.href = url +'address_id='+ $(this).data("id") + '&flag=' + flag ;
        });

    }


    //加载地址列表
    loadAddressList();

});