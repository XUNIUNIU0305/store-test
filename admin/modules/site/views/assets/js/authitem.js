/*
 *Author:Jiangyi
 * Date:2017/02/28
 * Desc:用于操作后台管理权限信息
 *
 */
$(function () {
    //获取模板信息
    var tplHtml = $("#J_tpl_list").html();
    var tplAuth = $("#A_tpl_list").html();
    var userHtml = $("#U_tpl_list").html();
    //用户列表对象
    var userObj = $("#U_tpl_list").parent();
    //权限列表对象
    var listAuth = $("#A_tpl_list").parent();
//   角色对象列表
    var list = $("#J_tpl_list").parent();

    //获取管理员用户列表
    function getUserList(keyword) {
        var compiled_tpl = juicer(userHtml);

        $.ajax({
            url: "/site/adminuser/getuserlist",
            method: "get",
            data: {"keyword": keyword},
            success: function (data) {
                if (data.status != 200) {
                    alert(data.data.errMsg);
                    return;
                }
                var result = compiled_tpl.render(data.data);

                //赋值结果
                userObj.empty().html(result);
                //添加事件
                userObj.find('li').hover(function () {
                    $(this).find('.pull-right').removeClass('invisible')
                }, function () {
                    $(this).find('.pull-right').addClass('invisible')
                });
                bindUserListEvents();

            },
            error: function (data) {
                alert("系统错误，获取管理员列表失败");
            }

        });
    }


    //绑定用户列表中的事件
    function bindUserListEvents() {
        //绑定用户名称事件
        userObj.find(".btn_account_list").off("click").on("click", function () {
            var userId = $(this).parent().attr("data-id");
            if (userId <= 0) {
                return;
            }
            //重置标题与用户id
            list.attr("data-id", userId);
            list.parent().parent().parent().find(".currentUser").html("当前用户：" + $(this).html());

            $.ajax({
                url: "/site/adminuser/getuserrole",
                method: "post",
                data: {"id": userId},
                dataType: "JSON",
                success: function (data) {
                    if (data.status != 200) {
                        alert(data.data.errMsg);
                        return;
                    }
                    var data = data.data;
                    //清空选项
                    list.find(".roleId").each(function () {
                        $(this).removeAttr("disabled");
                        $(this).prop({checked: false});
                        for (var i = 0; i < data.length; i++) {
                            if (parseInt($(this).val()) == parseInt(data[i].id)) {
                                $(this).prop({checked: true});
                            }
                        }
                    });

                },
                error: function () {
                    alert("获取用户角色信息失败");
                }


            });


        })
        //绑定编辑事件
        userObj.find(".U_edit_sort").off("click").on("click", function () {
            var userId = $(this).parent().parent().attr('data-id');
            $("#modalAddUser").find("#userName").attr("disabled", true);
            $("#modalAddUser").find(".modal-title").html("修改管理员密码");

            //编辑保存管理员信息
            $("#modalAddUser .U_btn_add").off("click").on("click", function () {

                var obj = $("#modalAddUser");
                if (obj.find("#passWord").val() == "") {
                    alert("请填写密码信息");
                    return false;
                }
                if (!confirm("您是否确定修改账户密码")) {
                    return false;
                }

                $.ajax({
                    url: "/site/adminuser/edit",
                    method: "POST",
                    data: {"id": obj.attr("data-id"), "passwd": obj.find("#passWord").val()},
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status != 200) {
                            alert(data.data.errMsg);
                            return;
                        }
                        alert("密码修改成功");
                        $("#modalAddUser").modal("hide");
                    },
                    error: function () {
                        alert("用户密码修改失败");
                    }
                });

            });

            var userName = $(this).parent().parent().find(".btn_account_list").html();

            $("#modalAddUser").find("#userName").val(userName.trim());
            $("#modalAddUser").find("#passWord").val("");
            $("#modalAddUser").attr("data-id", userId);
            $("#modalAddUser").modal("show");


        })


    }


    //添加管理员事件
    $(".U_add_user").off("click").on("click", function () {
        $("#modalAddUser").find("#userName").removeAttr("disabled");
        $("#modalAddUser").find(".modal-title").html("新增管理员信息");
        $("#modalAddUser").find("#userName").val("");
        $("#modalAddUser").find("#passWord").val("");

        //编辑保存管理员信息
        $("#modalAddUser .U_btn_add").off("click").on("click", function () {

            var obj = $("#modalAddUser");
            if (obj.find("#userName").val() == "") {
                alert("请填写管理员用户名");
                return false;
            }
            if (obj.find("#passWord").val() == "") {
                alert("请填写密码信息");
                return false;
            }

            $.ajax({
                url: "/site/adminuser/add",
                method: "POST",
                data: {"account": obj.find("#userName").val(), "passwd": obj.find("#passWord").val()},
                dataType: "JSON",
                success: function (data) {
                    if (data.status != 200) {
                        alert(data.data.errMsg);
                        return;
                    }
                    alert("用户信息新增成功");


                    //获取用户列表
                    getUserList();
                    //获取角色列表
                    getRoleList();

                    $("#modalAddUser").modal("hide");
                },
                error: function () {
                    alert("用户信息新增失败");
                }
            });

        });

    })


    //获取角色信息列表
    function getRoleList() {
        //解析模板信息
        var compiled_tpl = juicer(tplHtml);
        $.ajax({
            url: "/site/authitem/getrolelist",
            method: "get",
            dataType: "JSON",
            success: function (data, status, code) {
                if (data.status != 200) {
                    alert(data.data.errMsg);
                    return;
                }


                var result = compiled_tpl.render(data.data);

                //赋值结果

                list.empty().html(result);
                //添加事件
                list.find('li').hover(function () {
                    $(this).find('.pull-right').removeClass('invisible')
                }, function () {
                    $(this).find('.pull-right').addClass('invisible')
                });
                //添加编辑事件
                list.find("li").find(".J_edit_sort").off("click").bind("click", function () {
                    //获取角色ID
                    var roleId = $(this).parent().parent().attr("data-id");
                    $.ajax({
                        url: "/site/authitem/getroleinfo",
                        method: "get",
                        data: {"id": roleId},
                        dataType: "JSON",
                        success: function (json, status) {
                            //显示角色信息
                            if (json.status != 200) {
                                alert(json.data.errMsg);
                                return;
                            }
                            var obj = $("#modalAddRole");
                            //设置ID
                            obj.attr("data-id", json.data.id);
                            //赋值角色名
                            obj.find("#roleName").val(json.data.name);
                            //设置标题
                            obj.find(".modal-title").html("编辑角色信息");
                            //打开窗口
                            obj.modal();
                            //绑定事件
                            bindEvent();


                        },
                        error: function (req, status) {
                            alert("读取角色信息失败");
                        }
                    });
                })
                //添加事件.加载显示角色对应权限信息
                list.find("li").find(".btn_loadauthlist").off("click").on("click", function () {
                    var roleId = $(this).parent().attr("data-id");
                    //查询获取角色对应的权限列表

                    loadAuthList(roleId);
                });
                //新用户角色选取事件
                list.find(".roleId").on("click", function () {
                    var userId = list.attr("data-id");
                    if (userId <= 0) {
                        alert("请选择用户信息");
                        return;
                    }
                    var roleId = $(this).val();
                    $.ajax({
                        url: "/site/adminuser/setrole",
                        method: "post",
                        data: {"id": userId, "roleId": roleId},
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status != 200) {
                                alert(data.data.errMsg);
                                return;
                            }


                        },
                        error: function () {
                            alert("设置用户角色失败");
                        }

                    });


                });


                //添加删除事件
                list.find("li").find(".J_dele_sort").off('click').on("click", function () {
                    var roleId = $(this).parent().parent().attr("data-id");
                    if (!confirm('确定删除？')) {
                        return;
                    }
                    $.ajax({
                        url: '/site/authitem/remove',
                        method: 'POST',
                        data: {"id": roleId},
                        dataType: "JSON",
                        success: function (data) {
                            //错误终止
                            if (data.status != 200) {
                                alert(data.data.errMsg);
                                return;
                            }
                            //重新加载列表信息
                            getRoleList();
                            alert("角色信息删除成功");
                            return;
                        },
                        error: function (data) {
                            alert("调用请求失败！");
                        }
                    });


                });


            },
            error: function (req, status, errorThrown) {
                alert("获取角色列表信息失败！");
            }
        });
    }


//获取用户列表
    getUserList();
//获取角色列表
    getRoleList();
    $(".j_add_auth").hide();

    //执行权限设置操作
    function savePermission(roleId, authId) {
        $.ajax({
            url: "/site/authitem/savepermission",
            data: {"id": roleId, "permissionId": authId},
            method: "POST",
            dataType: "JSON",
            success: function (data) {
                if (data.status != 200) {
                    alert(data.data.errMsg);
                    return;
                }
            },
            error: function (data) {
                alert("更改角色权限操作失败");
            }
        });
    }

    //绑定权限事件
    function bindPermissionsEvents() {
        //给权限页面添加事件
        $(".T_auth_list").find(".btn_auth_item").off("click").on("click", function () {
            var roleId = $(".T_auth_list").attr("data-id");
            var authId = $(this).val();
            savePermission(roleId, authId);
            var isSub = $(this).attr("data-sub");

            if (isSub == 1 && $(this).prop("checked") == true) {
                //查找父级角色
                var parent = $(this).parent().parent().parent().find(".auth-folder .btn_auth_item");
                //置父级为选中
                if (parent.prop("checked") != true) {
                    parent.prop({checked: true});
                    authId = parent.val();
                    //保存更新权限
                    savePermission(roleId, authId);
                }


            }

        });


    }

    //加载权限列表s
    function loadAuthList(roleId) {
        $.ajax({
            url: "/site/authitem/authlist",
            method: "POST",
            data: {"id": roleId},
            dataType: "JSON",
            success: function (data) {
                if (data.status != 200) {
                    alert(data.data.errMsg);
                    return;
                }
                //读取模板，解析模板
                var compiled_tpl = juicer(tplAuth);
                var result = compiled_tpl.render(data.data);
                listAuth.empty().append(result);
                //赋值角色Id
                $(".T_auth_list").attr("data-id", roleId);
                $(".j_add_auth").show();
                //绑定权限事件
                bindPermissionsEvents();


            },
            error: function (data) {
                alert("加载角色权限信息失败")
            }
        });
    }

    /*添加权限按据，添加事件*/
    $(".j_add_auth").click(function () {
        $.ajax({
            url: '/site/authitem/parentauth',
            method: "get",
            dataType: "JSON",
            success: function (data) {
                if (data.status != 200) {
                    alert(data.data.errMsg);
                    return;
                }
                var js = data.data;
                $("#addAuth #parent").empty().append('<option value="0">无父级权限</option>');
                for (var i = 0; i < js.length; i++) {
                    $("#addAuth #parent").append('<option value="' + js[i].id + '">' + js[i].name + '</option>');
                }

            },
            error: function (data) {
                return false;

            }

        });


    });


    /*添加权限信息*/

    $(".J_add_auth").click(function () {
        var par = {};
        par.authName = $("#addAuth").find("#authName").val();
        par.controllerName = $("#addAuth").find("#controllerName").val();
        par.actionName = $("#addAuth").find("#actionName").val();
        par.parent = $("#addAuth").find("#parent").val();
        if (par.authName == "") {
            alert("请填写权限名称");
            return false;
        }
        if (par.controllerName == "") {
            alert("请填写控制器名称");
            return false;
        }
        if (par.actionName == "") {
            alert("请填写方法名称");
            return false;
        }
        $.ajax({
            url: '/site/authitem/addauth',
            data: par,
            method: "post",
            dataType: "JSON",
            success: function (data) {
                if (data.status != 200) {
                    alert(data.data.errMsg);
                    return;
                }
                var roleId = $(".T_auth_list").attr("data-id");
                loadAuthList(roleId);
                $("#addAuth").modal("hide");


            },
            error: function () {
                alert("保存权限信息失败");
            }
        });

    });


    /*添加角色*/
    $(".J_add_role").click(function () {
        var obj = $("#modalAddRole");
        obj.attr("data-id", 0);
        //赋值角色名
        obj.find("#roleName").val("");
        //设置标题
        obj.find(".modal-title").html("新增角色信息");
        //绑定事件
        bindEvent();

    });


    //绑定保存操作事件
    function bindEvent() {
        /*
         根据data-id属性的取值，判断操作，
         */
        $("#modalAddRole").find(".J_add_end").off("click").on("click", function () {
            var role_id = $("#modalAddRole").attr('data-id');

            //判断取值
            if ($(this).find("#roleName").val() == "") {
                alert("角色名称不能为空");
                return;
            }
            var par = {};
            par.id = role_id;
            par.name = $("#modalAddRole").find("#roleName").val();
            var url = "/site/authitem/add";
            if (role_id > 0) {
                url = "/site/authitem/save";
            }
            $.ajax({
                url: url,
                method: "POST",
                data: par,
                dataType: "JSON",
                success: function (data) {
                    if (data.status != 200) {
                        alert(data.data.errMsg);
                        return;
                    }
                    alert("角色信息保存成功！");

                    $("#modalAddRole").modal("hide");
                    getRoleList();
                    return;

                },
                error: function (data) {
                    alert("保存角色信息失败");
                    return false;
                }
            });

        });
    }
})
