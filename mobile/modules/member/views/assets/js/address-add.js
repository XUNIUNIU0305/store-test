
$(function(){
    var address = '';
    var address_info = $('#J_tpl_address').html();
    var province_id = '';
    var city_id = '';
    var district_id = '';
    var flag = '';
    var postal_code = '';
    var address_id = '';

    //获取url参数
    function getSearchAtrr(attr){  
        var attrArr = window.location.search.substr(1).split('&');
        var newArr = attrArr.map((item) => item.split('='));
        var i, len = newArr.length;
        for(i = 0 ; i < len ; i++) {
            if(newArr[i][0] == attr){
                return newArr[i][1];
            }
        }
    }

    //初始化地址
    function init() {
        function initCB(data) {
            address = data.hostname ;
            //判断是新增还是编辑
            address_id = url("?address_id");
            if (address_id) {
                requestUrl('/member/address/edit-view', 'GET', {address_id:address_id}, function(data){
                $('#name').val(data.consignee);
                $('#mobile').val(data.mobile);
                $('#postal_code').val(data.postal_code);
                $('#address_detail').val(data.detail);

                if (data.is_default) {
                    $('#setDefault input').attr('checked',true);
                }    
                province_id = data.province.id;
                city_id = data.city.id;
                district_id = data.district.id;
                flag = true;

                },function(data){alert(data.data.errMsg)},false);
            }
            requestUrl(address + '/district/province', 'GET', '', ProvinceFn);
            
        }
        requestUrl('/api-hostname', 'GET', '', initCB);
    }

    function ProvinceFn(data) {
        $.each(data, function(k, p) {
            if (p.id == province_id) {
                var option = "<option value='" + p.id + "' selected='selected'>" + p.name + "</option>";
                requestUrl(address + '/district/city', 'GET', {province:province_id},City );
            }else{
                 var option = "<option value='" + p.id + "'>" + p.name + "</option>";
            }
            $("#selProvince").append(option);
        });


     }

     function City(data){
        if (data.length < 1) {
            $('#selCity').val('0');
            $('#selCity').css('display', 'none');
            requestUrl(address + '/district/district', 'GET', {province: $('#selProvince').val(),city: 0},District);
            return
        }
        $.each(data, function(k, p) {
            if (p.id == city_id) {
                var option = "<option value='" + p.id + "' selected='selected'>" + p.name + "</option>";
                requestUrl(address + '/district/district', 'GET', {province:province_id,city:city_id},District);
            }else{
                 var option = "<option value='" + p.id + "'>" + p.name + "</option>";
            }
            $("#selCity").append(option);
        });
     }

     function District(data){
        if (data.length < 1) {
            $('#selDistrict').val('0');
            $('#selDistrict').css('display', 'none');
            return
        }
        $.each(data, function(k, p) {
           if (p.id == district_id) {
                var option = "<option value='" + p.id + "' selected='selected'>" + p.name + "</option>";
           }else{
                var option = "<option value='" + p.id + "'>" + p.name + "</option>";
           }
            $("#selDistrict").append(option);
        });
     }

    $("#selProvince").change(function() {
        var selProvince = $(this).val();
        if (!selProvince) return;
        $('#selCity').css('display', 'block');
        $('#selDistrict').css('display', 'block');
        $("#selCity option").each(function(index, val) {
            if (index > 0) {
                $(this).remove()
            }
        })
        $("#selDistrict option").each(function(index, val) {
            if (index > 0) {
                $(this).remove()
            }
        })
        requestUrl(address + '/district/city', 'GET', {province:selProvince},City );
    });

    $("#selCity").change(function() {
        var selCity = $(this).val();
        if (!selCity) return;
        $('#selDistrict').css('display', 'block');
        var selProvince = $('#selProvince').val();
        $("#selDistrict option").each(function(index, val) {
            if (index > 0) {
                $(this).remove()
            }
        })
        requestUrl(address + '/district/district', 'GET', {province:selProvince,city:selCity},District );
    });

    init();

     //添加收货地址
    $('.J_save_address').on('click', function() {
        if (flag) {
            setAddress('/member/address/edit-address', address_id);
        } else {
             setAddress('/member/address/add-address', '');
            
        }
    })

    //添加或修改地址
    function setAddress(url, _id) {
        var province = $('#selProvince').val();
        var city = $('#selCity').val();
        var district = $('#selDistrict').val(); 
        var setDefault = $('#setDefault input').is(':checked');
        if ($('#selCity').css('display') == 'none') {
            city = 0;
        }
        if ($('#selDistrict').css('display') == 'none') {
            district = 0;
        }
        if (province === '' || province == undefined) {alert('未选择省份'); return};
        if (city === '' || city == undefined) {alert('未选择城市'); return};
        if (district === '' || district == undefined) {alert('未选择区'); return};

        var data = {
            consignee: $('#name').val(),
            province:  province,
            city: city,
            district: district,
            detail: $('#address_detail').val(),
            mobile: $('#mobile').val(),
            postal_code : $('#postal_code').val(),
            id: _id,
            is_default:(setDefault == false) ? 0 : 1
        }
        if (data.consignee.trim() == '') {
            alert('收货人不能为空！')
            return
        }
        if (data.detail.trim() == '') {
            alert('请填写详细地址！')
            return
        }
        if (data.mobile == '') {
            alert('手机号码不能为空！')
            return
        }
        if (data.postal_code.length < 6) {
            alert('请填写正确的邮政编码！')
            return
        }    
        //提交请求
        $('.J_save_address').css('pointer-events', 'none')
        requestUrl(url,'POST',data,function(data){
            window.location.href = '/member/address/index';
        },function(data){
            $('.J_save_address').css('pointer-events', 'auto')
             alert(data.data.errMsg);
        });
    }

    (function(){
        var result = window.location.href.indexOf('flag');
        if(result == -1){
            $('#setDefault').on('click', function() {
                $(this).find('input').prop('checked', !$(this).find('input').prop('checked'));
            })
            return;
        }else {
            var _flag = getSearchAtrr('flag');
            if(_flag == 'true'){
                $('#setDefault input').attr({'checked' : true, 'disabled' : true});
            }else {
                $('#setDefault input').removeAttr('checked');
                $('#setDefault input').removeAttr('disabled');
                $('#setDefault').on('click', function() {
                    $(this).find('input').prop('checked', !$(this).find('input').prop('checked'));
                })
            }
        }
    })();

});