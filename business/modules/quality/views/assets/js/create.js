
 $(function () { //初始化时间
    var nowDate = new Date();
    var month = nowDate.getMonth() + 1;
    var day = nowDate.getDate();
    if (month.toString().length == 1) {
        month = '0' + month
    }
    if (day.toString().length == 1) {
        day = '0' + day
    }
    var nowTime = nowDate.getFullYear() + '-' + month + '-' + day;
    $('input.J_search_timeStart').val(nowTime);
    $('input.J_search_timeEnd').val(nowTime);
    // add locale
    $.fn.datepicker.dates["zh-CN"] = {
        days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
        daysMin: ["日", "一", "二", "三", "四", "五", "六"],
        months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        today: "今日",
        clear: "清除",
        format: "yyyy年mm月dd日",
        titleFormat: "yyyy年mm月",
        weekStart: 1
    }
    // init the datepicker
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN'
    });
    $('.J_date_btn').on('click', function() {
        $(this).siblings('input').focus()
    })
    //获取API地址
    var api = '';
    var _proInfo = {
        pro: '',
        place: '',
        technician: ''
    }

    requestUrl('/api-hostname', 'GET', '', function(data) {
        api = data.hostname;
        //获取产品列表
        requestUrl(api + '/quality/business-package', 'GET', '', function(data) {
            var options = '<option value="-1">请选择</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            $('select.J_package_list').html(options);
            _proInfo.pro = options;
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        })
        //获取施工位置
        requestUrl( api + '/quality/business-place', 'GET', '', function(data) {
            var options = '<option value="-1">请选择</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            $('select.J_place_list').html(options);
            _proInfo.place = options;
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        })
        //获取汽车品牌
        requestUrl(api + '/car/brand', 'GET', '', function(data) {
            var options = '<option value="-1">请选择</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' +data[i].sign+' '+ data[i].name + '</option>'
            }
            $('#J_brand_list').html(options);
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        })
        //获取门店技师
        requestUrl('/quality/qualityorder/get-technican-list', 'GET', '', function(data) {
                var codes = data.codes;
                var options = '<option value="-1">请选择</option>';
                for (var i = 0; i < codes.length; i++) {
                    options += '<option value="' + codes[i].id + '">' + codes[i].name + '</option>'
                }
                $('select.J_technician_list').html(options);
                _proInfo.technician = options;
                $('.selectpicker').selectpicker('refresh');
                $('.selectpicker').selectpicker('show');

        });

    })
    //获取汽车类型
    $('#J_brand_list').on('change', function() {
        var id = $(this).val();
        if (id == -1) return;
        requestUrl(api + '/car/type', 'GET', {brand_id: id}, function(data) {
            var options = '<option value="-1">请选择</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">'+ data[i].sign +' '+ data[i].name + '</option>'
            }
            $('#J_type_list').html(options);
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        })
    })
    //获取验证码
    var timerAll = [],
        intervalAll = [];
    $('.J_get_verify_sms').off("click").on("click",function (e) {
        var $that=$(this);
        //启用计时启
        function startTimer(){
            var timer, interval;
            e.preventDefault();
            var $this =$that;
            var countDown = 60;
            // disable it
            // if ($this.hasClass('disabled')) return;
            $this.addClass('disabled');
            // revert changes after 60s
            timer = setTimeout(function () {
                $this.text('点击获取');
                $this.removeClass('disabled');
                interval && clearInterval(interval);
            }, 60 * 1000);
            timerAll.push(timer);
            // set count down text
            $this.text(countDown + '秒后重试');
            interval = setInterval(function () {
                countDown--;
                $this.text(countDown + '秒后重试');
            }, 1000);
            intervalAll.push(interval);
        }
        $that.addClass('disabled');


    });
    $(".J_reset_button").click(function(){
        $(".J_warring").show();
        $(".J_result_content").hide();
    });

    //添加产品信息
    $('#J_add_pro').on('click', function() {
        var len = $('.J_pro_info').length;
        if (len < 7) {
            var html = '<tr class="J_pro_info">\
                        <td>' + (len - 0 + 1) + '</td>\
                        <td>\
                            <input type="text" class="form-control J_round_num">\
                        </td>\
                        <td>\
                            <select class="selectpicker J_package_list" data-width="100%">\
                                <option value="">请选择</option>\
                            </select>\
                        </td>\
                        <td>\
                            <select class="selectpicker J_place_list" data-width="100%">\
                                <option value="">请选择</option>\
                            </select>\
                        </td>\
                        <td>\
                            <select class="selectpicker J_technician_list" data-width="100%">\
                                <option value="">请选择</option>\
                            </select>\
                        </td>\
                        <td>\
                            <input type="text" class="form-control J_sales_list" maxlength="12"> \
                        </td>\
                    </tr>'
            $('#J_pro_box').append(html);
            $('select.J_package_list').eq(len).html(_proInfo.pro);
            $('select.J_place_list').eq(len).html(_proInfo.place);
            $('select.J_technician_list').eq(len).html(_proInfo.technician);
            $('.J_pro_info:gt(' + (len - 1)  + ')').find('.selectpicker').selectpicker('refresh');
            $('.J_pro_info:gt(' + (len - 1)  + ')').find('.selectpicker').selectpicker('show');
        }
    })
    //删除产品信息
    $('#J_minus_pro').on('click', function() {
        var len = $('.J_pro_info').length; 
        if (len > 1) {
            $('.J_pro_info').eq(len - 1).remove()
        }
    })

    //创建质保单
    $('#J_submit_quality').on('click', function() {
        var name = $('#J_user_name').val().trim();
        var mobile = $('#J_user_mobile').val().trim();
        var area_code = $('#J_area_code').val().trim();
        var telephone = $('#J_user_telephone').val().trim();
        var email = $('#J_user_email').val().trim();
        var address = $('#J_user_address').val().trim();
        var code = $('#J_user_code').val().trim();
        var frame = $('#J_user_frame').val().trim();
        var brand = $('#J_brand_list').val();
        var type = $('#J_type_list').val();
        var car_price = $('#J_car_price').val().trim();
        var start_time = $('.J_search_timeStart').val();
        var end_time = $('.J_search_timeEnd').val();
        var pro_price = $('#J_pro_price').val().trim();
        // var technician = $('select.J_technician_list').val();
        var construct_unit = $('#J_shop_name').val();

        var goods = [];
        if (name == '' || mobile == '' || address == '') {
            alert('请填写完整的车主信息！');
            $("#modalWarrantyAdd").modal('hide');
            return
        }
        for (var i = 0; i < $('.J_pro_info').length; i++) {
            goods[i] = {};
            goods[i]['sales'] = $('.J_sales_list').eq(i).val();
            goods[i]['technician'] = $('select.J_technician_list').eq(i).val();
            goods[i]['round_num'] = $('.J_round_num').eq(i).val();
            goods[i]['place_id'] = $('select.J_place_list').eq(i).val();
            goods[i]['package_id'] = $('select.J_package_list').eq(i).val();
            if ($('select.J_technician_list').eq(i).val() == -1 || $('.J_round_num').eq(i).val() == '' || $('.J_sales_list').eq(i).val() == '' || $('select.J_place_list').eq(i).val() == -1 || $('select.J_package_list').eq(i).val() == -1) {
                alert('产品信息不完整！');
                $("#modalWarrantyAdd").modal('hide');
                return
            }
        }
        if (code == '' || frame == '' || brand == -1 || type == -1 || car_price == '') {
            alert('请填写完整的车辆信息！');
            $("#modalWarrantyAdd").modal('hide');
            return
        }
        if (pro_price == ''  || construct_unit =='') {
            alert('施工信息不完整！');
            $("#modalWarrantyAdd").modal('hide');
            return
        }

        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            alert('手机号码格式错误！');
            $("#modalWarrantyAdd").modal('hide');
            return
        }
        
        var resultCF = frame.search(/[^0-9A-Za-z]/g);
        if (resultCF != -1) {
            alert('车架号格式错误！');
            $("#modalWarrantyAdd").modal('hide');
            return
        }
        var resultCP = car_price.search(/[^0-9-.]/g);
        var resultPP = pro_price.search(/[^0-9.]/g);
        if (resultCP != -1 || resultPP != -1) {
            alert('价格格式错误！');
            $("#modalWarrantyAdd").modal('hide');
            return
        }
        // var resultEM = email.search(/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/);
        // if (resultEM == -1) {
        //     alert('邮箱格式错误！');
        //     $("#modalWarrantyAdd").modal('hide');
        //     return
        // }
        var resultCD = code.search(/^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领]{1}[A-Z]{1}[A-Z0-9]{5,6}$/);
        if (resultCD == -1) {
            alert('车牌号格式错误！');
            $("#modalWarrantyAdd").modal('hide');
            return
        }
        var data = {
            username: name,
            telephone: telephone,
            area_code:area_code,
            mobile: mobile,
            email: email,
            address: address,
            car_code:code,
            car_frame: frame,
            car_brand: brand,
            car_type: type,
            start_time: start_time,
            finished_time:end_time,
            car_price: car_price,
            // technician: technician,
            goods: goods,
            price:pro_price,
            construct_unit:construct_unit
        }

        $('#J_submit_quality').addClass('disabled');
        requestUrl('/quality/qualityorder/create-order', 'post', data, function(data) {
            //window.location.href = '/quality/qualityorder/index';
            $(".J_result_content").find(".text-danger").html(data.orderCode);
            $(".J_warring").hide();
            $(".J_result_content").removeClass('result').show();
            $(".J_warring").hide();
            $("#modalWarrantyAdd").modal('show');
            $('#modalWarrantyAdd').one('hidden.bs.modal', function() {
                window.location.reload()
            })

        }, function(data) {
            $('#J_submit_quality').removeClass('disabled');
            $("#modalWarrantyAdd").modal('hide');
            alert(data.data.errMsg);
        })
    })
    $('#J_user_code').on('blur', function() {
        $(this).val($(this).val().toUpperCase())
    })

})
