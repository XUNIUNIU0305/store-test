(function($){ 
    // 输入框格式化 
    $.fn.bankInput = function(options){ 
        var defaults = { 
            min : 0, // 最少输入字数 
            max : 25, // 最多输入字数 
            deimiter : ' ', // 账号分隔符 
            onlyNumber : true, // 只能输入数字 
            copy : false // 允许复制 
        }; 
        var opts = $.extend({}, defaults, options); 
        var obj = $(this); 
        obj.css({imeMode:'Disabled',borderWidth:'1px',color:'#000',fontFamly:'Times New Roman'}).attr('maxlength', opts.max); 
        if(obj.val() != '') obj.val( obj.val().replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1"+opts.deimiter) ); 
        obj.bind('keyup',function(event){ 
            if(opts.onlyNumber){ 
                if(!(event.keyCode>=48 && event.keyCode<=57)){ 
                    this.value=this.value.replace(/\D/g,''); 
                } 
            } 
            this.value = this.value.replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1"+opts.deimiter); 
        }).bind('dragenter',function(){ 
            return false; 
        }).bind('onpaste',function(){ 
            return !clipboardData.getData('text').match(/\D/); 
        }).bind('blur',function(){ 
            this.value = this.value.replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1"+opts.deimiter); 
            if(this.value.length < opts.min){ 
                //alertMsg.warn('最少输入'+opts.min+'位账号信息！'); 
                obj.focus(); 
            } 
        }) 
    } 
    // 列表显示格式化 
    $.fn.bankList = function(options){ 
        var defaults = {
            deimiter : ' ' // 分隔符 
        }; 
        var opts = $.extend({}, defaults, options); 
        return this.each(function(){ 
            $(this).text($(this).text().replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1"+opts.deimiter)); 
        }) 
    } 
})(jQuery); 
$(function() {

    // 公共弹窗
    function showMsg(msg) {
        $('#businessCommonAlertMsg').html(msg);
        $('#businessCommonAlert').modal('show');
    }
    // 暂存全局数据
    var g = {
        bankId: '',
        province: '-1',
        city: '-1',
        district: '-1',
        subId: null,
        user_type: null
    }

    // 刷新select
    function refreshS() {
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
    }

    // 获取API
    var _commenAPI = '';
    requestUrl('/api-hostname', 'GET', '' , function(data) {
        _commenAPI = data.hostname;
    }, function(data) {
        alert('获取API失败！请重新刷新页面！')
    }, false)


    // 银行选择
    $('#J_select_bank_name').on('click', function() {
        $(this).addClass('hidden').siblings('.select-bank-list').removeClass('hidden');
    })
    $('body').on('click', function(e) {
        var _target = $('#J_select_bank_name');
        var _target2 = $('#J_select_bank_box');
        if (!_target.is(e.target) && _target.has(e.target).length === 0 && !_target2.is(e.target)) {
            $('#J_select_bank_box').addClass('hidden').siblings('#J_select_bank_name').removeClass('hidden');
        }
    })

    // 获取证件类型
    function getIdType() {
        requestUrl(_commenAPI + '/bank/id-type', 'GET', '', function(data) {
            var options = '<option value="-1">请选择证件类型</option>';
            $.each(data, function(i, val) {
                options += '<option value="' + val.id + '">' + val.name + '</option>'
            })
            $('#J_id_type').html(options);
            refreshS()
        })
    }
    getIdType()

    // 获取银行列表
    var tpl_bank = $('#J_tpl_bank').html();
    function getBankList() {
        requestUrl(_commenAPI + '/bank/list', 'GET', '', function(data) {
            var html = '<li data-id=""><span>请选择银行</span></li>';
            $.each(data, function(i, val) {
                html += '<li data-id="' + val.id + '"><img src="' + val.image + '" alt="" />' + val.name + '</li>';
            })
            $('#J_bank_list').html(html);
        })
    }
    getBankList()
    // 选择银行
    $('#J_bank_list').on('click', 'li', function() {
        var id = $(this).data('id');
        var html = $(this).html();
        $('#J_select_bank_name').html(html);
        if (id != g.bankId) {
            $('#J_select_bank').html('请选择开户支行');
            g.subId = null;
        }
        g.bankId = id;
        $('#J_select_bank_box').addClass('hidden').siblings('#J_select_bank_name').removeClass('hidden');
    })
    
    //初始化地址
    function init() {
        //获取省级信息
        var address = '';
        address = _commenAPI + '/district/province';
        getMsg(address,'',ProvinceFn);
    }
    init();

    //获取地址信息
    function getMsg(address, data, fn) {
        function msgCB(data) {
            var result = '<option value="-1">请选择</option>';
            for (var i = 0; i < data.length; i++) {
                result += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            if (data.length == 0) {
                result = ''
            }
            if (typeof(fn) == 'function') fn(result);
        }
        requestUrl(address, 'GET', data, msgCB)
    }
    //省级下拉函数
    function ProvinceFn(data) {
        $('select[class*=J_province]').html(data);
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
        //省市联动
        $('select[class*=J_province]').off('.pro').on('change.pro', function() {
            reset($('select[class*=J_city]'));
            reset($('select[class*=J_district]'));
            $('.address .col-xs-4').eq(1).removeClass('hidden');
            $('.address .col-xs-4').eq(2).removeClass('hidden');
            if ($(this).val() == -1) return;
            $('select[class*=J_city]').val('-1');
            $('select[class*=J_district]').val('-1');
            var _data = {province: $(this).val()}
            getMsg(_commenAPI + '/district/city', _data, CityFn)
        })
    }
    //市级下拉函数
    function CityFn(data) {
        $('select[class*=J_city]').html(data);
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
        if (data == '') {
            $('.address .col-xs-4').eq(1).addClass('hidden');
            $('select[class*=J_city]').val('-1');
            var _data = {
                province: $('select[class*=J_province]').val(),
                city: 0
            }
            getMsg(_commenAPI + '/district/district', _data, DistrictFn)
            return
        }
        //市区联动
        $('select[class*=J_city]').off('.cit').on('change.cit', function() {
            $('.address .col-xs-4').eq(2).removeClass('hidden');
            $('select[class*=J_district]').val('-1');
            if ($(this).val() == -1) return;
            var _data = {province: $('select[class*=J_province]').val(),city: $('select[class*=J_city]').val()}
            getMsg(_commenAPI + '/district/district', _data, DistrictFn)
        })
    }
    //区级菜单函数
    function DistrictFn(data) {
        $('select[class*=J_district]').html(data);
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
        if (data == '') {
            $('.address .col-xs-4').eq(2).addClass('hidden');
            $('select[class*=J_district]').val('-1');
            return
        }
    }
    //重置下拉菜单
    function reset(item) {
        item.html('<option value="-1">请选择</option>');
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
    }

    // 搜索支行
    function getSubBank(params) {
        var data = {
            current_page: 1,
            page_size: 200
        }
        $.extend(data, params);
        var html = `<div class="modal-loading-box">
                    <div class="loading-inner">
                        <div class="line-spin-fade-loader">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>`;
        $('#J_sub_bank').html(html);
        requestUrl(_commenAPI + '/bank/code', 'GET', data, function(data) {
           var lis = ''; 
           $.each(data.list, function(i, val) {
               lis += '<li data-id="' + val.id + '" data-name="' + val.name + '">' + val.name + '</li>' 
           })
           $('#J_sub_bank').html(lis)
        })
    }

    $('#J_select_bank').on('click', function() {
        $('#J_sub_bank').html('');
        $('#J_search_input').val('');
        $(this).addClass('hidden').siblings('.search-bank').removeClass('hidden');
        var params = {
            bank_id: g.bankId == '' ? null : g.bankId
        }
        getSubBank(params)
    })
    $('body').on('click', function(e) {
        var _target = $('#J_search_bank');
        var _target2 = $('#J_select_bank');
        if (!_target.is(e.target) && _target.has(e.target).length === 0 && !_target2.is(e.target)) {
            $('#J_search_bank').addClass('hidden').siblings('#J_select_bank').removeClass('hidden');
        }
    })
    $('#J_search_icon').on('click', function() {
        var keys = $('#J_search_input').val().trim();
        getSubBank({
            bank_id: g.bankId == '' ? null : g.bankId,
            string: keys
        })
    })
    $('#J_search_input').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $('#J_search_icon').click();
        }
    })

    // 选择支行 
    $('#J_sub_bank').on('click', 'li', function(d) {
        var subId = $(this).data('id');
        var name = $(this).data('name');
        g.subId = subId;
        $('#J_select_bank').html(name).attr('title', name);
        $('#J_search_bank').addClass('hidden').siblings('#J_select_bank').removeClass('hidden');
    })

    // 客户类型
    $('#J_user_type span').on('click', function() {
        $(this).addClass('active').siblings().removeClass('active');
        var type = $(this).data('type');
        if (type == 0) {
            $('#J_type_tip').removeClass('hidden')
        } else {
            $('#J_type_tip').addClass('hidden')
        }
        g.user_type = type;
    })

    // 银行卡号显示
    $('#J_bank_code').bankInput();

    // 提交信息
    $('#J_submit_info').on('click', function() {
        $('#modalBandkTip').modal('hide');
        var name = $('#J_user_name').val().trim();
        var code = $('#J_bank_code').val().trim().replace(/\s/g, '');
        var id_type = $('#J_id_type').val();
        var id_code = $('#J_id_code').val();
        var mobile = $('#J_mobile').val().trim();
        if (g.user_type == null) {
            showMsg('请选择客户类型！')
            return
        }
        if (name == '') {
            showMsg('请填写开户户名！')
            return
        }
        if (code == '') {
            showMsg('请填写银行账号！')
            return
        }
        if (g.subId == '' || g.subId == null) {
            showMsg('请选择开户支行！')
            return
        }
        if (id_type == -1) {
            showMsg('请选择证件类型！')
            return
        }
        if (id_code.length < 1) {
            showMsg('请填写证件号码！')
            return
        }
        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1 || mobile.length < 11) {
            showMsg('手机号格式错误！')
            return
        }
        var params = {
            mobile_phone: mobile,
            id_type: id_type,
            id_no: id_code,
            acct_type: g.user_type,
            acct_no: code,
            branch_id: g.subId,
            acct_name: name
        }
        $('#modalLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
        requestUrl('/bank/open-account/add-card', 'POST', params, function(data) {
            if (data.is_success) {
                setTimeout(function() {
                    $('#modalLoading').modal('hide');
                    showMsg('提交成功！');
                    $('#businessCommonAlert').one('hidden.bs.modal', function() {
                        window.location.href = '/bank/card';
                    })
                }, 3000)
            } else {
                $('#modalLoading').modal('hide');
                showMsg(data.err_msg)
            }
        }, function(data) {
            $('#modalLoading').modal('hide');
            showMsg(data.data.errMsg)
        })
    })
    
})