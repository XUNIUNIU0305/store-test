$(function(){
    var id_arr = [];
    $.each($('.item[data-id]'), function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
            $('.item[data-id="' + val.id + '"]').find('.J_pro_price').text('￥' + val.price.min) //.toFixed(2)
            $('.item[data-id="' + val.id + '"]').find('.J_pro_title').text(val.title)
            $('.item[data-id="' + val.id + '"]').find('.J_pro_img').attr('src', val.main_image)
          })
        }
      )
    }
    getPrice(id_arr);

    $(".day-nav li a").click(function(e){
        var index = $(this).parent('li').index()
        var sortid = $(this).data("id");
        e.currentTarget.style.color = '#E36147'
        e.currentTarget.style.backgroundColor = '#fff'
        resetStyle(sortid)
        clearInterval(interval);
        $('.item-list-center').children('div').eq(index).children('div').eq(0).attr('class','img_lun item img2')
        $('.item-list-center').children('div').eq(index).children('div').eq(1).attr('class','img_lun item img3')
        $('.item-list-center').children('div').eq(index).children('div').eq(2).attr('class','img_lun item img4')
    	interval = setInterval(function (){
            getRTime(e.currentTarget.getAttribute('data-start-time'),e.currentTarget.getAttribute('data-end-time'));
		},100);
        var id = '#slide'+ sortid;
        $(id).addClass("slide");
        for(var i=1;i<5;i++){
            if(i != Number(sortid)){
                $("#slide"+i).removeClass("slide");
            }
        }
        slideImg()
    });

    function  resetStyle(id) {
    	var eleArr = $(".day-nav a");
    	for(var i=0;i< eleArr.length;i++){
    		if(i+1 != Number(id)){
                eleArr[i].style.color = '#fff';
                eleArr[i].style.backgroundColor = '#E36147';
    		}
    	}
    }


  });
  $(function(){
      var backButton=$('.top');
      function backToTop() {
          $('html,body').animate({
              scrollTop: 0
          }, 800);
      }
      backButton.on('click', backToTop);

      $(window).on('scroll', function () {/*当滚动条的垂直位置大于浏览器所能看到的页面的那部分的高度时，回到顶部按钮就显示 */
          if ($(window).scrollTop() > $(window).height())
              backButton.fadeIn();
          else
              backButton.fadeOut();
      });
      $(window).trigger('scroll');
    });

    function getRTime(startDate,endDate){
           var t = 0
    	   var startTime = new Date(startDate)
    	   var endTime= new Date(endDate); //截止时间
    	   var nowTime = new Date();
    	   if(endTime.valueOf() > nowTime.valueOf() && nowTime.valueOf() >= startTime.valueOf()){
    	       $('#active-info').text('距离活动结束');
               t =endTime.getTime() - nowTime.getTime();
               $('#active-time').show();
               $.each($(".img_lun"),function(index,item){
                $(this).find('a').attr('href',$(this).find('a').attr('data-src'));
               })
               $(".lun_ps p").attr("style","color:#000;");
               $(".lun_ps strong").attr("style","color:#E36147;");

    	   }else if(nowTime.valueOf() < endTime.valueOf()){
               $('#active-info').text('距离活动开始');
               t =startTime.getTime() - nowTime.getTime();
               $('#active-time').show();
               $(".img_lun").children('a').attr("href","javascript:;");
               $(".lun_ps p").attr("style","color:gray;");
               $(".lun_ps strong").attr("style","color:gray;");

    	   }else{
               $('#active-info').text('活动已结束');
               $('#active-time').hide();
               $(".img_lun").children('a').attr("href","javascript:;");
               $(".lun_ps p").attr("style","color:gray;");
               $(".lun_ps strong").attr("style","color:gray;");

           }
           if(nowTime.valueOf() > new Date('2018-09-29 00:00:00')){
               $('.floor0').hide();
               $('.aside_center_content #first').hide();
           }
    	   /*var d=Math.floor(t/1000/60/60/24);
    	   t-=d*(1000*60*60*24);
    	   var h=Math.floor(t/1000/60/60);
    	   t-=h*60*60*1000;
    	   var m=Math.floor(t/1000/60);
    	   t-=m*60*1000;
    	   var s=Math.floor(t/1000);*/
           t = t > 0 ? t : 0
    	   var d=Math.floor(t/1000/60/60/24);
    	   var h=Math.floor(t/1000/60/60%24);
    	   var m=Math.floor(t/1000/60%60);
    	   var s=Math.floor(t/1000%60);

    	   if(d < 10){
    		   d = "0" + d;
    	   }
    	   if(h < 10){
    		   h = "0" + h;
    	   }
    	   if(m < 10){
    		   m= "0" + m;
    	   }
    	   if(s < 10){
    		   s = "0" + s;
    	   }
    	   document.getElementById("t_d").innerHTML = d ;
    	   document.getElementById("t_h").innerHTML = h;
    	   document.getElementById("t_m").innerHTML = m;
    	   document.getElementById("t_s").innerHTML = s ;
       }
       var interval =setInterval(function () {
           getRTime('2018/09/19 10:00:00','2018/09/19 23:59:59')
       },100)


     function slideImg(){
     $(".slide").height($(".slide").width()*0.56);
     slideNub = $(".slide .img_lun").size();             //获取轮播图片数量
     for(i=0;i<slideNub;i++){
     	$(".slide .img_lun:eq("+i+")").attr("data-slide-imgId",i);
     }

     //根据轮播图片数量设定图片位置对应的class
     if(slideNub==1){
     	for(i=0;i<slideNub;i++){
     		$(".slide .img_lun:eq("+i+")").addClass("img3");
     	}
     }
     if(slideNub==2){
     	for(i=0;i<slideNub;i++){
     		$(".slide .img_lun:eq("+i+")").addClass("img"+(i+3));
     	}
     }
     if(slideNub==3){
     	for(i=0;i<slideNub;i++){
     		$(".slide .img_lun:eq("+i+")").addClass("img"+(i+2));
     	}
     }
     if(slideNub>3&&slideNub<6){
     	for(i=0;i<slideNub;i++){
     		$(".slide .img_lun:eq("+i+")").addClass("img"+(i+1));
     	}
     }
     if(slideNub>=6){
     	for(i=0;i<slideNub;i++){
     		if(i<5){
     		   $(".slide .img_lun:eq("+i+")").addClass("img_lun"+(i+1));
     		}else{
     			$(".slide .img_lun:eq("+i+")").addClass("img5");
     		}
     	}
     }

     }
     slideImg()

     //右滑动
     function right(){
     var fy = new Array();
     for(i=0;i<slideNub;i++){
     	fy[i]=$(".slide .img_lun[data-slide-imgId="+i+"]").attr("class");
     }
     for(i=0;i<slideNub;i++){
     	if(i==0){
     		$(".slide .img_lun[data-slide-imgId="+i+"]").attr("class",fy[slideNub-1]);
     	}else{
     	   $(".slide .img_lun[data-slide-imgId="+i+"]").attr("class",fy[i-1]);
     	}
     }

     }

     //左滑动
     function left(){
     var fy = new Array();
     for(i=0;i<slideNub;i++){
     	fy[i]=$(".slide .img_lun[data-slide-imgId="+i+"]").attr("class");
     }
     for(i=0;i<slideNub;i++){
     	if(i==(slideNub-1)){
     		$(".slide .img_lun[data-slide-imgId="+i+"]").attr("class",fy[0]);
     	}else{
     	   $(".slide .img_lun[data-slide-imgId="+i+"]").attr("class",fy[i+1]);
     	}
     }
     }
