$(function() {
	var address = '';
    var address_info = $('#J_tpl_address').html();
    var province_id = '';
    var city_id = '';
    var district_id = '';
    var flag = '';
    var postal_code = '888888';

    var address_id = '';

    $('#registerCode').focus();
	$('input').not('#registerCode').attr('disabled','disabled');
	$('select').attr('disabled','disabled');

	//判断注册码是否可用
	function judgeCode(val) {
		requestUrl('/member/register/checkaccountstatus', 'POST', {account: val}, function(data) {
			$('#registerCode').parents('.form-group').removeClass('error');
			$('input').removeAttr('disabled');
			$('select').removeAttr('disabled');
		}, function(data) {
			$('#registerCode').parents('.form-group').addClass('error');
			$('input').not('#registerCode').attr('disabled','disabled');
			$('select').attr('disabled','disabled');
		})
	} 
    //初始化地址
    function init() {
        function initCB(data) {
            address = data.hostname;
            //绑定账户事件
            $('#registerCode').on('keyup paste', function() {
            	var $this = $(this);
            	setTimeout(function() {
	            	var val = $this.val();
	            	if ($this.data('tmp') == val) return;
	    			$('#registerCode').data('tmp', val);
	            	if (val.length != 9) {
						$('input').not('#registerCode').attr('disabled','disabled');
						$('select').attr('disabled','disabled');
						return;
	            	}
	            	judgeCode(val);
            	}, 300)
            })
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
            $('#selCity').parent('span').css('display', 'none');
            requestUrl(address + '/district/district', 'GET', {province: $('#selProvince').val(),city: 0},District);
            return
        }
        $("#selCity").append('<option value="-1">请选择</option>');
        $("#selDistrict").append('<option value="-1">请选择</option>');
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
            $('#selDistrict').parent('span').css('display', 'none');
            return
        }
        $("#selDistrict").append('<option value="-1">请选择</option>');
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
        if (selProvince == -1) return;
        $('#selCity').parent('span').css('display', 'inline-block');
        $('#selDistrict').parent('span').css('display', 'inline-block');
        $("#selCity option").remove();
        $("#selDistrict option").remove();
        requestUrl(address + '/district/city', 'GET', {province:selProvince},City );
    });

    $("#selCity").change(function() {
        var selCity = $(this).val();
        if (!selCity) return;
        $('#selDistrict').parent('span').css('display', 'inline-block');
        var selProvince = $('#selProvince').val();
        $("#selDistrict option").remove();
        requestUrl(address + '/district/district', 'GET', {province:selProvince,city:selCity},District );
    });

    init();

    //判断URL是否带有注册码
    (function() {
        var _r = url('?r')
        if (_r && _r.length > 0) {
            $('#registerCode').val(_r);
            judgeCode(_r);
        }
    })()

    //提交
    $('#J_register_submit').on('click.submit', function() {
    	var code = $('#registerCode').val();
    	var passwd1 = $('#pwd').val();
    	var passwd2 = $('#pwd_confirm').val();
    	var province = $('#selProvince').val();
    	var city = $('#selCity').val();
    	var district = $('#selDistrict').val();
    	var mobile = $('#mobile').val();
    	var email = $('#email').val();
    	var validate=$("#valid").val();
    	
    	if (code == '' || passwd1 == '' || passwd2 == '' || mobile == '' || email == '') {
    		alert('请把信息填写完整！')
    		return
    	}
    	var resultPW = passwd1.search(/[^0-9a-zA-Z]/g);
    	if (resultPW != -1) {
    		alert('密码格式错误！');
    		return
    	}
    	if (passwd1 != passwd2) {
    		alert('两次密码不一致！');
    		return
    	}
    	if (passwd1.length < 8) {
    		alert('密码长度至少为8位！');
    		return
    	}
    	if (province == -1 || city == -1 || district == -1) {
    		alert('请选择完整地址！');
    		return
    	}
    	if (city == '') {
    		city = 0
    	}
    	if (district == '') {
    		district = 0
    	}
    	var resultMB = mobile.search(/0?(1)[0-9]{10}/);
    	if (resultMB == -1) {
    		alert('手机号格式错误！');
    		return
    	}
    	var resultEM = email.search(/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/);
    	if (resultEM == -1) {
    		alert('邮箱格式错误！');
    		return
    	}
    	$('#J_register_submit').addClass('disabled');
    	var _data = {
    		account: code,
			passwd: passwd1,
			confirm_passwd: passwd2,
			province: province,
			city: city,
			district: district,
			mobile: mobile,
			email: email,
            verify_code:validate
    	}
    	function submitCB(data) {
    		alert('成功！')
    		window.location.href = data.url
    	}
    	function errCB(data) {
    		$('.J_register_submit').removeClass('disabled');
    		alert(data.data.errMsg)
    	}
    	requestUrl('/member/register/register', 'POST', _data, submitCB, errCB)
    })

    // get sms
    var timerAll = [],
        intervalAll = [];
    $('.J_get_verify_sms').click(function (e) {

        var mobile = $('#mobile').val();
        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            alert('手机号格式错误！');
            return
        }

        var _data={mobile:mobile,type:0};

        var $that=$(this);

    	//启用计时启
		function startTimer(){
			var timer, interval;
			e.preventDefault();
			var $this =$that;
			var countDown = 60;
			// disable it
			//if ($this.hasClass('disabled')) return;
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

        function submitCB(data){
			startTimer();
		}
        //错误返回
        function errCB(data) {
            $('.J_get_verify_sms').removeClass('disabled');
            alert(data.data.errMsg)
        }
        $that.addClass('disabled');

        requestUrl('/sms/send', 'GET', _data, submitCB, errCB)

    })
})