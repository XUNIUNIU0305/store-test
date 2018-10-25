
$(function(){
 var _area_id;
 var _config_data;
 var _page_size=14;
 var _current_area_id=0;


    var tpl = $('#J_tpl_list').html();
    //获取质保列表
    function getList(page) {
        var data = {
            current_page: page,
            page_size: _page_size,
            area_id: _current_area_id,

        }
        requestUrl('/quality/technican/get-list', 'GET', data, function(data) {
            var html = juicer(tpl, data);
            $('.J_user_list').html(html);

            //选择事件
            $(".J_user_list li").mouseover(function(){
                $(this).addClass('active');
            }).mouseout(function(){
                $(this).removeClass('active');
            });

            //删除事件
            $(".J_btn_remove").click(function(){
                $("#apxModalBusinessDel").find(".J_btn_del_confirm").attr("data-id",$(this).attr("data-id"));
                $("#apxModalBusinessDel").modal("show");
                return false;
            });


            //添加修改事件
            $(".J_btn_modify").click(function(){

                $(".J_edit_sure").attr("data-id",$(this).data('id'));
                var name=$(this).parent().parent().find("div").eq(0).html();
                var mobile=$(this).parent().parent().find("div").eq(1).html();
                var remark=$(this).parent().parent().find("div").eq(2).html();
                _area_id=$(this).data("area");

                $("#apxModalManageEdit #employee_name").val(name);
                $("#apxModalManageEdit #employee_mobile").val(mobile);
                $("#apxModalManageEdit #employee_remarks").val(remark);
                $("#apxModalManageEdit").modal("show");
                return false;
            });





            //分页
            var pages = getPagination(page, Math.ceil(data.total_count/_page_size));
            $('.J_user_page').html(pages);
            $('#J_page_box li').on('click', function() {
                var val = $(this).data('page');
                if (val == undefined) {
                    return false
                }
                getList(val);
            })
            $('#J_page_search input').on('keyup', function() {
                var number = $(this).val().replace(/\D/g,'') - 0;
                $(this).val(number);
                if ($(this).val().length < 1) {
                    $(this).val('1');
                    return false
                }
                if ($(this).val() < 1) {
                    $(this).val('1');
                    return false
                }
                if ($(this).val() > $('#J_page_box').data('max')) {
                    $(this).val($('#J_page_box').data('max'))
                    return false
                }
            })
            $('#J_page_search a').on('click', function() {
                var n = $('#J_page_search input').val();
                if (n > $('#J_page_box').data('max')) {
                    alert('已超过最大分页数')
                    return false;
                }
                getList(n);
            })

        })
    }
    getList(1);

    //执行删除操作
    $(".J_btn_del_confirm").click(function () {
        var url="/quality/technican/remove";
        var _data={
            id:$(this).attr("data-id"),
        };
        function success(data){
            alert('删除成功');
            $("#apxModalBusinessDel").modal('hide');
            getList(1);
        }
        function errorCB(data){
            alert(data.data.errMsg);
            $("#apxModalBusinessDel").modal('hide');
        }
        requestUrl(url,'get',_data,success,errorCB);
    });
    //检测数据
    function validate(){
       //var name=$("#apxModalManageEdit #employee_name").val();
       var mobile= $("#apxModalManageEdit #employee_mobile").val();
       //var remark= $("#apxModalManageEdit #employee_remarks").val();
        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            alert('手机号码格式错误！');
            return false;
        }
        return true;

    }



    //加载配置数据
    function loadConfig(){
        var url="/leader/area/level";
        function success(data){
            _config_data=data;
            loadoptions(0,'J_top',0,1);
        }
        requestUrl(url,'get','',success);
    }

    //初始化配置
   /*
   * parentId:父级ID
   * targetObj:显示目标对象
   * _value : 取值
   * index:层级缩引
    */
    function loadoptions(parentId,targetObj,_value,index){
        var _url="/leader/area/list";
        var _data={
            parent_id:parentId,
        };
        function success(data){
             var html="<option value='-1'>请选择"+_config_data[index]+"</option>";
             var _obj=$('#'+targetObj);
             _obj.empty();
             for(var i=0;i<data.list.length;i++){
                 html+='<option value="'+data.list[i].id+'">'+data.list[i].name+'</option>';
             }
             _obj.html(html);
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        }
        requestUrl(_url,'get',_data,success);
    }


    //选择二级
    $("#J_top").change(function(){
        loadoptions($(this).val(),'J_second',0,2);
    });

    //选择三级
    $("#J_second").change(function(){
        loadoptions($(this).val(),'J_third',0,3);
    });

    // 选择四级
    $('#J_third').change(function() {
        loadoptions($(this).val(),'J_four',0,4);
    })

    //搜索
    $(".J_btn_search").click(function(){
        if($("#J_four").val()==-1) {
            _current_area_id =0;
        }else{
            _current_area_id = $("#J_four").val();
        }
        getList(1);
    });

    loadConfig();


    //添加用户
    $(".J_btn_add_employee").click(function(){
        if($("#J_four").val()==-1) {
            alert("请选择所属区域");
            return false;
        }
        _area_id=$("#J_four").val();

        $(".J_edit_sure").attr("data-id",0);
        $("#apxModalManageEdit #employee_name").val('');
        $("#apxModalManageEdit #employee_mobile").val('');
        $("#apxModalManageEdit #employee_remarks").val('');
        $("#apxModalManageEdit").modal("show");

    });


    //绑定提交事件
    $(".J_edit_sure").click(function(){

        var url="/quality/technican/save";

        if(parseInt($(this).attr("data-id"))==0){
            url="/quality/technican/add";
        }
        if(!validate()){
            return false;
        }
        var _data={
            id:$(this).attr("data-id"),
            name:$("#apxModalManageEdit #employee_name").val(),
            mobile:$("#apxModalManageEdit #employee_mobile").val(),
            remark:$("#apxModalManageEdit #employee_remarks").val(),
            area_id:_area_id,
        };
        function success(data){
            alert("保存成功");
            $("#apxModalManageEdit").modal("hide");
            getList(1);
        }

        requestUrl(url,'post',_data,success);
    });

})