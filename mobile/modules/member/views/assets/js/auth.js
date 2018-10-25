$(function () {
	// 点击条款，出现模态框，底层不滚动
	$('#model').on('touchend',function(){
		$('#modelCon').show();
		$('body').css('overflow','hidden');
		return false;
	})
	//点击模态框 关闭按钮，模态框消失，底层可以滚动
	$('#close').on('touchend',function(){
		$('#modelCon').hide();
		$('body').css('overflow','auto');
	})
	//点击同意切换class
	var agree = true;
	$('#agree').on('touchend',function(){
		if(agree){
			$(this).addClass('agree-active');
		}else{
			$(this).removeClass('agree-active');
		}
		agree = !agree;
	})

	var address = '';
    var province_id = '';
    var city_id = '';
    var district_id = '';
    var flag = '';

	var address_id = '';
    //初始化地址
    function init() {
        function initCB(data) {
            address = data.hostname ;
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
            $('#selCity').addClass('hidden');
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
            $('#selDistrict').addClass('hidden');
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
        $('#selCity').removeClass('hidden');
        $('#selDistrict').removeClass('hidden');
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
        $('#selDistrict').removeClass('hidden');
        var selProvince = $('#selProvince').val();
        $("#selDistrict option").each(function(index, val) {
            if (index > 0) {
                $(this).remove()
            }
        })
        requestUrl(address + '/district/district', 'GET', {province:selProvince,city:selCity},District );
    });

    // 获取审核信息
    function getAuthInfo() {
    	requestUrl('/member/auth/info', 'GET', '', function(data) {
    		if (data.status) {
    			province_id = data.district.province.id;
	            city_id = data.district.city.id;
	            district_id = data.district.district.id;
	            flag = true;
	            $('#J_store_name').val(data.store_name);
	            $('#J_company_name').val(data.corp_name);
	            $('#J_detail_address').val(data.address);
	            $('#J_leader_name').val(data.manager_name);
				data.card_front.name && $('#J_card_front').siblings('img').data('filename', data.card_front.name).attr('src', data.card_front.path);
				data.card_back.name && $('#J_card_reverse').siblings('img').data('filename', data.card_back.name).attr('src', data.card_back.path);
	            $('#J_contact_name').val(data.contact_name);
	            $('#J_contact_phone').val(data.contact_mobile);
	            $('#J_email').val(data.email);
				data.business_licence.name && $('#J_business_licence').siblings('img').data('filename', data.business_licence.name).attr('src', data.business_licence.path);
				data.store_front.name && $('#J_store_front').siblings('img').data('filename', data.store_front.name).attr('src', data.store_front.path);
				data.store_inside.name && $('#J_store_reverse').siblings('img').data('filename', data.store_inside.name).attr('src', data.store_inside.path);
	    		$('#J_header_tip').removeClass('hidden');
	    		if (data.status != 3) {
	    			$('#J_submit_btn').parent('.btn').addClass('hidden');
	    			$('#J_submit_disabled').removeClass('hidden');
	    			$('input[type="file"]').attr('disabled', 'disabled');
				}
				agree = false;
				$('#agree').addClass('agree-active');
	    		//审核中提示
                if (data.status == 2) {
                    $('#J_header_tip').text('您的信息正在审核，请耐心等待')
                }
                //审核拒绝留言
                if (data.status == 3) {
                    $('#J_header_tip').text(data.comment)
                }
                if (data.status == 4) {
                    $('#J_header_tip').text('恭喜，您的资质审核已通过！扫货特权将于审核通过后生效')
                }
                if (data.status == 5) {
                    $('#J_header_tip').text('恭喜，您的资质审核已通过！扫货特权已经开通')
                }
    		}
	    	init()	
    	})
    }
    getAuthInfo()


    // 上传图片
    $('input[type="file"]').on('change', function() {
    	var $this = $(this);
    	mobileUploadUtils($this, function(data) {
    		$this.siblings('img').attr('src', data.data.url).data('filename', data.data.filename);
    	})
    })

    $('#J_submit_btn').on('click', function() {
    	if (agree) {
    		alert('请确保您已阅读并同意《九大爷协议》！')
            return
    	}
    	var store_name = $('#J_store_name').val();
    	var company_name = $('#J_company_name').val();
	    var province = $('#selProvince').val();
	    var city = $('#selCity').val();
	    var district = $('#selDistrict').val();
	    var detail = $('#J_detail_address').val();
	    var leader = $('#J_leader_name').val();
	    var card_front = $('#J_card_front').siblings('img').data('filename');
	    var card_reverse = $('#J_card_reverse').siblings('img').data('filename');
	    var contact_name = $('#J_contact_name').val();
	    var contact_phone = $('#J_contact_phone').val();
	    var email = $('#J_email').val();
	    var business_licence = $('#J_business_licence').siblings('img').data('filename');
	    var store_front = $('#J_store_front').siblings('img').data('filename');
	    var store_reverse = $('#J_store_reverse').siblings('img').data('filename');
	    if (store_name == '') {
	    	alert('请填写门店名称！')
	    	return
	    }
	    // if (company_name == '') {
	    // 	alert('请填写公司名称！')
	    // 	return
	    // }
	    if (province == '') {
	    	alert('请选择省份！')
	    	return
	    }
	    if (city == '') {
	    	if (!$('#selCity').hasClass('hidden')) {
	    		alert('请选择城市！')
	    		return
	    	}
	    }
	    if (district == '') {
	    	if (!$('#selDistrict').hasClass('hidden')) {
	    		alert('请选择区/县！')
	    		return
	    	}
	    }
	    if (detail == '') {
	    	alert('请填写详细地址！')
	    	return
	    }
	    if (leader == '') {
	    	alert('请填写负责人！')
	    	return
	    }
	    // if (card_front == undefined || card_reverse == undefined) {
	    // 	alert('请上传身份证照片！')
	    // 	return
	    // }
	    // if (contact_name == '') {
	    // 	alert('请填写联系人！')
	    // 	return
	    // }
	    // if (contact_phone == '') {
	    // 	alert('请填写联系人手机号！')
	    // 	return
	    // }
	    if (email == '') {
	    	alert('请填写邮箱！')
	    	return
	    }
	    // if (business_licence == undefined) {
	    // 	alert('请上传营业执照照片！')
	    // 	return
	    // }
	    // if (store_front == undefined || store_reverse == undefined) {
	    // 	alert('请上传门店照片！')
	    // 	return
	    // }
	    // var resultMB = contact_phone.search(/0?(1)[0-9]{10}/);
        // if (resultMB == -1) {
        //     alert('手机号格式错误！')
        //     return
        // }
        var resultEM = email.search(/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/);
        if (resultEM == -1) {
            alert('邮箱格式不正确！')
            return
        }
	    var params = {
	    	store_name: store_name,
	    	corp_name: company_name,
	    	province_id: province,
	    	city_id: city,
	    	district_id: district,
	    	address: detail,
	    	manager_name: leader,
	    	card_front: card_front,
	    	card_back: card_reverse,
	    	contact_name: contact_name,
	    	contact_mobile: contact_phone,
	    	email: email,
	    	business_licence: business_licence,
	    	store_front: store_front,
	    	store_inside: store_reverse
	    }
	    requestUrl('/member/auth/submit', 'POST', params, function(data) {
	    	alert('上传成功！')
            window.location.href = '/member/index';
	    })
    })

})