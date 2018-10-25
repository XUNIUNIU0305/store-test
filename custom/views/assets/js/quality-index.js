$(function () {
	$(".detail-contents-ft").css({"display":"block","opacity":1,"left":0});
	$("li").click(function(){
		var index =$(this).index();
        $(this).addClass("li-clicksty").siblings().removeClass("li-clicksty").addClass("li-small");
        $(".details-contents-container").children("div").not(".prompt").eq(index).show().animate({"left":"0","opacity":"1"},1000)
        .siblings().hide().css({"left":"-400px","opacity":"0"}).stop(false,true);
        $(".quality-query-container").hide();
        $("input").val("");
    });
    //重置
    $(".rest").click(function(){
         getCaptchaImg();
        $(this).parent().parent().find("input").val("");
    });
  
    //非空判断
    function isEmpty (str){
    	var $str_empty = $("."+str).find("input");  
    	var i=0;      
        $str_empty.each(function(){
            if($(this).val()==''){
                $(this).next("i").show();
                $("#error-i").show();
                i++;
            }else {
                $(this).next("i").hide(); 
                $("#error-i").hide();               
            }
        })
        return i;
    }
    //错误窗口
    $(".error-confirm-btn").on('click',function(){
        closeErrorScreen();
    });
    $(".close-png").on('click',function(){
        closeErrorScreen();
    });
    //错误窗口弹出
    function openErrorScreen(){
        $(".error-ms-window").show();
        $(".shadow-screen").show();
        $("body").css("overflow","hidden");
    }
    //错误窗口关闭
    function closeErrorScreen(){
        $(".error-ms-window").hide();
        $(".shadow-screen").hide();
        $("body").css("overflow","auto");
    }
    //页面滚动到详情页面
    function scroolTodetail(id){
        $("html, body").animate({scrollTop: $("#"+id).offset().top}, 1000);
    }

    //1质保单号、管芯号、车架号、手机号、验证码
    $(".order-code-carframe-captcha").click(function () {
        var order_no_str = $("#first-order-no").val();
        var code_str = $("#first-code").val();
        var car_frame_str = $("#first-car-frame").val();
        var mobile_str = $("#first-mobile").val();
        var captcha_str = $("#first-captcha").val();       
        var str_name = $(this).parent().parent().attr('class');
    	isEmpty(str_name);
    	if(isEmpty(str_name)<1){
    		var data = {
                order_no:order_no_str,
                code:code_str,
                car_frame:car_frame_str,
                mobile:mobile_str,
                captcha:captcha_str
    		};
    		requestUrl('/quality-search/search-detail-one','get',data,function(res){   
                $("#all-detail").show().siblings(".quality-query-container").hide();
                scroolTodetail("all-detail");
                getCaptchaImg();                
                $(".all_owner_name").html(res.owner_name);
                $(".all_owner_telephone").html(res.owner_telephone);
                $(".all_owner_mobile").html(res.owner_mobile);
                $(".all_owner_email").html(res.owner_email);
                $(".all_owner_address").html(res.owner_address);
                var tpl = $('#all_tpl_list').html();
                var html = juicer(tpl,res);
                $('#all_pro_list').html(html); 
                $('.all_quality_code').html(res.code);    
                $('.all_car_number').html(res.car_number);
                $('.all_type_name').html(res.type_name);
                $('.all_car_frame').html(res.car_frame);
                $('.all_shop_name').html(res.construct_unit);
                $('.all_construct_date').html(res.construct_date);
                $('.all_finished_date').html(res.finished_date);

    		},function(res){
                getCaptchaImg();
                openErrorScreen();            
                $(".error-ms-detail").html(res.data.errMsg);
    		});
    	}else {
        getCaptchaImg(); 
        }
    });

    //2按质保单号+验证码
    $(".order-no-btn").click(function() {
    	var order_no_str = $("#second-order-no").val();
        var captcha_str = $("#second-captcha").val() 
    	var str_name = $(this).parent().parent().attr('class');
    	isEmpty(str_name);
    	if(isEmpty(str_name)<1){
    		var data = {	    			
	    			order_no:order_no_str,
                    captcha:captcha_str
    			};
    		requestUrl('/quality-search/search-detail-two','get',data,function(res){
    			$("#result-detail").show().siblings(".quality-query-container").hide();;
                scroolTodetail("result-detail");
                getCaptchaImg();
                $(".result_owner_name").html(res.owner_name);
                $(".result_quality_code").html(res.code);
    		},function(res){
                getCaptchaImg();
                openErrorScreen();            
                $(".error-ms-detail").html(res.data.errMsg);
    		});
    	} else {
        getCaptchaImg();                
        }
    }); 

    //更新验证码
    $(".ms-details").click(function(){
    	getCaptchaImg();
    });
	$(".changcode").click(function(){
		getCaptchaImg();
	});
    //获取验证码
    function getCaptchaImg() {
         $.ajax({
            url: "/index/captcha",
            method: "GET",
            data: {
                'refresh': Math.random() + ''
            }
        })
        .done(function (data) {
            $('.ms-details').attr('src',data.url);
        })
        .fail(function () {
            setTimeout(function () {
                getCaptchaImg();
            }, 3000);
        })
    }
   
  
    //3按质保单号、车主姓名、车架号、车主手机号 、手机验证码
    $(".order-name-car-btn").click(function() {
    	var str_name = $(this).parent().parent().attr('class');
    	var order_no_str = $("#third-order-no").val();
        var owner_name_str = $("#third-name").val();
        var car_frame_str = $("#third-car-frame").val(); 
        var mobile_str = $("#third-mobile").val();
        var mobile_captcha_str = $("#third-mobile-captcha").val(); 

    	isEmpty(str_name);
    	if(isEmpty(str_name)<1){
    		var data = {   			
	    			order_no:order_no_str,
	    			owner_name:owner_name_str,
	    			car_frame:car_frame_str,
                    mobile:mobile_str,
                    mobile_captcha:mobile_captcha_str
    			};
               
    		requestUrl("/quality-search/search-detail-three",'get',data,function(res) {
                $("#all-detail").show().siblings(".quality-query-container").hide();
                scroolTodetail("all-detail");
                getCaptchaImg();
                $(".all_owner_name").html(res.owner_name);
                $(".all_owner_telephone").html(res.owner_telephone);
                $(".all_owner_mobile").html(res.owner_mobile);
                $(".all_owner_email").html(res.owner_email);
                $(".all_owner_address").html(res.owner_address);
                var tpl = $('#all_tpl_list').html();
                var html = juicer(tpl,res);
                $('#all_pro_list').html(html);
                $('.all_quality_code').html(res.code);    
                $('.all_car_number').html(res.car_number);
                $('.all_type_name').html(res.type_name);
                $('.all_car_frame').html(res.car_frame);
                $('.all_shop_name').html(res.construct_unit);
                $('.all_construct_date').html(res.construct_date);
                $('.all_finished_date').html(res.finished_date);		
    		},function(res) {
                getCaptchaImg();
                openErrorScreen();            
                $(".error-ms-detail").html(res.data.errMsg);
    		});
    	} 
    });	

     	
    //发送手机验证码
    $(".mobile-ms-btn").on('click',function(){
        var mobile_str = $("#third-mobile").val();
        var resultMB = mobile_str.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            alert("手机格式错误！");
            return
        }
        var $this = $(this);
        
        var data={
            type:6,
            mobile: mobile_str
        };
        requestUrl('/sms/send','GET',data,function(res){
          startTimer($this);  
        });
    })
    //开启
    var timerAll = [],
    intervalAll = [];
    //启用计时启
    function startTimer(dom) {
        var timer, interval;
        var $this = dom;
        var countDown = 60;
        // disable it
        if ($this.hasClass('disabled')) return;
        $this.addClass('disabled');
        // revert changes after 60s
        timer = setTimeout(function () {
            $this.text('获取验证码');
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

    //4按管芯号
    var allData='';
    $(".code-no-btn").click(function() {
    	var str_name = $(this).parent().parent().attr('class');
    	var code_str = $("#fourth-code").val();
        var captcha_str = $("#fourth-captcha").val();
    	isEmpty(str_name);
    	if(isEmpty(str_name)<1){
    		var data = {   			
	    			code:code_str,
                    captcha:captcha_str
    		};
    		requestUrl('/quality-search/search-detail-four','get',data,function(res){
                allData = res;
                $("#search").show().siblings(".quality-query-container").hide();
                scroolTodetail("search");
                getCaptchaImg();
                var tpl = $('#search_tpl_list').html();
                var html = juicer(tpl,res);
                $('#search_pro_list').html(html); 
    		},function(res){
                getCaptchaImg();
                openErrorScreen();            
                $(".error-ms-detail").html(res.data.errMsg);
    		});
    	} else{
        getCaptchaImg();    
        }
    }); 
    // allData 为暂存放所有数据
    $("#search_pro_list").click(function(event){
        var dataId = $(event.target).data('id');
        for(var i= 0;i < allData.length; i++ )
        {
            if(allData[i].id == dataId){
                var tpl = $('#search_detail_tpl_list').html();
                var html = juicer(tpl,allData[i]);
                $('#search_detail_pro_list').html(html);
                $(".J_quality_code").html(allData[i].code);
                $(".search_detail_type_name").html(allData[i].car_type);
                $(".search_detail_shop_name").html(allData[i].construct_unit);
                $(".search_detail_construct_date").html(allData[i].construct_date);
                $(".search_detail_finished_date").html(allData[i].finished_date); 
                $("#search-detail").show().siblings(".quality-query-container").hide();
                
            }
        }
    });
   
    $("#back-to-codelist").click(function(){
        $("#search").show().siblings(".quality-query-container").hide();
        return false;
    });
    //管芯号详情页数据显示

})