$(function(){

    $('.bottom-nav').addClass('hidden');

    var _g = {
        provinceId : 0,
        cityId : 0,
        districtId : 0,
        id : url('?id')
    }

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

    //设置默认
    $('#default-btn').on('click',function(){
        var $this = $(this);
        if($this.data('flag') == 0){
            $this.attr('src','/images/self_lifting_manage/checkbox_sele_44_icon.png');
            $this.data('flag','1');
        }else {
            $this.attr('src','/images/self_lifting_manage/checkbox_no_44_icon.png');
            $this.data('flag','0');
        }
    });
    
    //获取省信息
    function getProvinceInfo(){
        requestUrl('http://test.api.9daye.com.cn/district/province','GET',{},function(data){
            $('#sel_p').html('');
            $('#sel_p').append('<option disabled selected>请选择省</option>');
            $.each(data,function(index,item){
                var _option = `<option value='${item.id}' ${item.id == _g.provinceId && 'selected'}>${item.name}</option>`;
                $('#sel_p').append(_option);
            });
        });
    }
    !_g.id && getProvinceInfo();

    $('#sel_p').on('change',function(){
        var $province = $(this).val();
            _g.provinceId = $province;
            getCityInfo();
        var $province = $(this).val();
            _g.cityId = 0;
            getDistrictInfo();
    });
    
    //获取市信息
    function getCityInfo(){
        requestUrl('http://test.api.9daye.com.cn/district/city','GET',{province : _g.provinceId},function(data){
            $('#sel_c').html('');
            if(data.length == 0){
                $('#sel_c').addClass('hidden');
            }else{
                $('#sel_c').removeClass('hidden');
                $('#sel_c').append('<option disabled selected>请选择市</option>');
                $.each(data,function(index,item){
                    var _option = `<option value='${item.id}' ${item.id == _g.cityId && 'selected'}>${item.name}</option>`;
                    $('#sel_c').append(_option);
                });
            }
        });
    }

    $('#sel_c').on('change',function(){
        var $val = $(this).val();
        _g.cityId = $val;
        getDistrictInfo();
    });
    
    //获取区信息
    function getDistrictInfo(){
        requestUrl('http://test.api.9daye.com.cn/district/district','GET',{province : _g.provinceId, city : _g.cityId},function(data){
            $('#sel_a').html('');
            if(data.length == 0){
                $('#sel_a').addClass('hidden');
            }else{
                $('#sel_a').removeClass('hidden');
                $('#sel_a').append('<option disabled selected>请选择区</option>');
                $.each(data,function(index,item){
                    var _option = `<option value='${item.id}' ${item.id == _g.districtId && 'selected'}>${item.name}</option>`;
                    $('#sel_a').append(_option);
                });
            }
        });
    }

    //获取页面表单数据
    function getAttrInfo(){
        return {
            spot_name : $('#spot_name').val().trim(),
            consignee : $('#consignee').val().trim(),
            mobile : $('#mobile').val().trim(),
            postal_code : $('#postal_code').val().trim(),
            province : $('#sel_p').val(),
            city : $('#sel_c').val() || 0,
            district : $('#sel_a').val() || 0,
            detailed_address : $('#detailed_address').val().trim(),
            default : $('#default-btn').data('flag')
        }
    }

    //编辑自提点
    function editAddrInfo(){
        var param = getAttrInfo();
        param.id = _g.id;
        requestUrl('/member/spot-address/edit-gpubs-address','POST',param,function(){
            window.location.href = '/member/spot-address/gpubs-index';
        });
    }
    
    //新增自提点
    function addAddrInfo(){
        var param = getAttrInfo();
        requestUrl('/member/spot-address/add-gpubs-address','POST',param,function(){
            window.location.href = '/member/spot-address/gpubs-index';
        });
    }

    $('#mobile').on('focus',function(){
        $('#new-addr-icon').removeClass('hidden');
    })
    $('#mobile').on('blur',function(){
        if($(this).val()){
            $('#new-addr-icon').removeClass('hidden');
        }else {
            $('#new-addr-icon').addClass('hidden');
        }
    });

    //清空手机号
    $('#new-addr-icon').on('click',function(){
        $('#mobile').val('').focus();
    });

    //非空验证
    function required(dom){
        var val = $('#' + dom).val();
        return /[^\s]/.test(val);
    }

    //手机号验证
    function telNumber(){
        return /^((0\d{2,3}-\d{7,8})|(1[345789]\d{9}))$/.test($('#mobile').val());
    }

    //邮编验证
    function postalCode(){
        return /^\d{6}$/g.test($('#postal_code').val());
    }

    //保存自提点信息
    $('#push-btn').on('click',function(e){
        e.preventDefault();
        if(required('spot_name') && required('consignee') && required('mobile') && required('postal_code') && required('detailed_address') && telNumber() && postalCode()){
            (_g.id != null) ? editAddrInfo() : addAddrInfo();
        }else if(required('spot_name') == false){
            alert('自提点名称不能为空');
        }else if(required('consignee') == false){
            alert('收货人不能为空');
        }else if(required('mobile') == false){
            alert('手机号不能为空');
        }else if(telNumber() == false){
            $('#mobile').val('');
            alert('手机号格式不对');
        }else if(required('postal_code') == false){
            $('#postal_code').val('');
            alert('邮政编码不能为空');
        }else if(postalCode() == false){
            alert('邮政编码格式不对');
        }else if(required('detailed_address') == false){
            alert('详细地址不能为空');
        }
        
    });

    //获取自提点详细信息
    _g.id && getAddrInfo();
    function getAddrInfo(){
        requestUrl('/member/spot-address/get-gpubs-address','GET',{id : _g.id},function(data){
            if(getSearchAtrr('flag') == 1){
                $('#default-btn').attr('src','/images/address_gpubs_add/checkbox_sele_44_icon.png').data('flag','1').off('click');
            }else {
                $('#default-btn').attr('src','/images/address_gpubs_add/checkbox_no_44_icon.png').data('flag','0').on('click');
            }
            var _data = JSON.parse(JSON.stringify(data));
            $('#spot_name').val(_data.spot_name);
            $('#consignee').val(_data.consignee);
            $('#mobile').val(_data.mobile);
            var length = String(_data.postal_code).length;
            var postal_code = _data.postal_code;
            if (length < 6){
                var str = '';
                for (var i=0;i<6-length;i++) {
                    str += '0';
                }
                postal_code = str + postal_code;
            }
            $('#postal_code').val(postal_code);
            $('#detailed_address').val(_data.detailed_address);
            _g.provinceId = data.province.id
            _g.cityId = data.city.id
            _g.districtId = data.district.id
            getProvinceInfo();
            getCityInfo();
            getDistrictInfo();
        });
    }

})