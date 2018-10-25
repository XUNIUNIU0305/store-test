$(function(){
	//1:固定商品加载
	var fixed_goods = [1523, 1357, 1617, 1271, 1417];
	function getFixedGoods(data) {
		var data = {
			id : data
		}
		requestUrl('/product-recommend/goods','GET',data,function(res) {
			var tpl = $('#J_fixed_goods').html();
			var tplb = $('#B_fixed_goods').html();
			var html = juicer(tpl,res);
			var htmlb = juicer(tplb,res);
			$('#J_fixed_contain').html(html);
			$('#J_error_goods').html(htmlb);
		});
	}
	getFixedGoods(fixed_goods);


	var g = {
		end_category_id: url("?end_category_id"),
		current_page:1,
		page_size:20,
		option_id:{},
		order_by: {}
	}
	$('#sort_name').text(url('?end_category_name'));
	var	pageBox = $("#J_page_footer"),
		goods_tpl = $('#J_detail_list').html();
	//获取数据
	function sortSearch(data){
		requestUrl('/search/category-goods','GET',data,function(res){
			var sort_html = juicer(goods_tpl,res.codes);
			$("#J_detail_content").html(sort_html);	
			if(res.total_count == 0){
				hiddenTag();
			} else {

				pagingBuilder.build(pageBox, data.current_page, data.page_size, res.total_count);
				setpage();
				pagingBuilder.click(pageBox,function (page) {
					g.current_page = page;
					sortSearch(g);
				})
			}

		},function(res){
		// 错误操作 即都查询不到
			hiddenTag();
		})
	}

	//分类列表数据填充
	var data = {
		end_category_id: g.end_category_id
	}
	var data_len;
	requestUrl('/search/category-attribute','GET',data,function(res){
		var sort_tpl = $("#J_sort_list").html();
		var sort_html = juicer(sort_tpl,res);
		$("#J_sort_content").html(sort_html);
		data_len = res.length;
		if( data_len <= 4) {
			$('.slideUp').addClass('hidden');
		} else {
			// 计算最大显示高度
			countHeight()
		}
		$("#slideimg").attr('src','/images/custom_category/pulldown.png');
		$(".slideUp").css('color','#333');
		
	},function(res) {
		// 错误操作位置
		hiddenTag();
	});

	sortSearch(g);

	function hiddenTag(){
			$('.error-contain').removeClass('hidden');
			// $('.Screening-condition-content').addClass('hidden');
			$('.search-contain').addClass('hidden');
			// $('.slideUp').addClass('hidden');
			// $('header,.line').css('border','none');
			$('#error_goods_name').text(url('?end_category_name'));
			$('.error-only').removeClass('hidden');
			$('.error-all').addClass('hidden');
			return ;
	}

	//设置上面页数
	function setpage(){
		var page_now;  
		setTimeout(function(){
			var page_max = $('#J_page_footer').find('.J_page_box').attr('data-max');
			page_now= $('#J_page_footer').find('.J_page_box').find('.active').attr('data-page');
			$('#page-now').html(page_now);
			$('#page-max').html(page_max);
		}, 400);
	}
	setpage();
	//顶部分页
	$('#prev-page').click(function(){
		var page_now = $('#page-now').html() - 0;
		var page_max = $('#page-max').html() - 0;
		page_now <= 1 ? page_now = 2 : page_now;
		g.current_page = page_now -1 ;
		sortSearch(g);
	});

	$('#next-page').click(function(){
		var page_now = $('#page-now').html() - 0;
		var page_max = $('#page-max').html() - 0;
		page_now >= page_max ? page_now = page_max - 1 : page_now;
		g.current_page = page_now + 1;
		sortSearch(g);
	});
	

	// 计算筛选框高度
	function countHeight(h) {
		var _h = h || 0;
		for (var i = 0; i < 4; i++) {
			_h += $("#J_sort_content .sCoditionone").not('.hidden').eq(i).height()
			if ($("#J_sort_content .sCoditionone").eq(i).hasClass('sctiActive')) {
				_h += 4
			} else {
				_h += 1
			}
		}
		$('#J_sort_content').css('height', _h).data('height', _h);
	}

	//隐藏和显示多余分类
	var slipe = false;

	$('.slideUp').on('click', function() {
		if( data_len <= 4){
			return;
		}
		if(slipe){
			$('header').css('borderColor','#f2f2f2');
			$(this).css({'color':'#f00','border':'2px solid #f2f2f2','border-top':'1px solid #fff'});
			$("#slidetext").html('显示更多');
			countHeight()
			$("#slideimg").attr('src','/images/custom_category/pulldown.png');
			$(this).css('color','#333');
		}else{
			$("#J_sort_content").css({'height':'auto'});
			$("#slidetext").html('收起');
			$(this).css({'color':'#f00','border':'1px solid #f00','border-top':'1px solid #fff'});
			$('header').css('borderBottom','1px solid #f00');
			$("#slideimg").attr('src','/images/custom_category/pullup_red.png');
		}
		slipe =! slipe;
	})
	
	//复选框点击
	$('#J_sort_content').on('click','.more-chose',function(){
		var status = $(this).attr('data-id');
		var height = $(this).parents('.sCoditionone').find('.more-list').css('height');
		$(this).prev().find(".select-list").hide();
		$(this).parents('.sCoditionone').addClass("sctiActive");
		$(this).prev().find('.more-list').show();	
		$(this).attr('data-id','open');
		$(this).addClass('hidden');
		if (!slipe) {
			// 计算最大显示高度
			countHeight()
		}
	});

	//点击取消按钮隐藏复选框
	$('#J_sort_content').on('click','.calcon',function(){
		$(this).parents('.more-list').hide();
		$(this).parents('.sCoditionone').find('.more-chose').removeClass('hidden');
		$(this).parents('.sCoditionone').find(".select-list").show();
		$(this).parents('.sCoditionone').find('.m-select-details').removeClass('chosed');		
		$(this).parents('.sCoditionone').find('.chosecheck').attr('data-id','false').removeClass('chosecheckshow');
		$(this).parents('.sCoditionone').removeClass("sctiActive");
		if (!slipe) {
			countHeight()
		}
	});

	//点击复选框出现对勾
	$('#J_sort_content').on('click','.m-select-details',function(){
		var data_str = $(this).find('.chosecheck').attr("data-id");
		if(data_str == 'false'){
			$(this).find('.chosecheck').addClass("chosecheckshow");
			$(this).find('.chosecheck').parent().addClass("chosed");
			$(this).find('.chosecheck').attr("data-id",'true');
		} else {
			$(this).find('.chosecheck').removeClass("chosecheckshow");
			$(this).find('.chosecheck').parent().removeClass("chosed");
			$(this).find('.chosecheck').attr("data-id",'false');
		}
	});

	//点击红叉删除分类条件
	$(".screen-condition-detail").on('click','img',function(){
		var show_id = $(this).parent().attr('data-id');
		var options_id = $(this).parent().attr('id');
		if(options_id){
			var options = g.option_id[show_id];
			for(var i=0;i<options.length;i++){
				if(options[i] == options_id ){
					options.splice(i,1);
				}
			}
			if(options.length == 0) {
				delete g.option_id[show_id]
			}
		} else {
			delete g.option_id[show_id];
		}
		g.current_page = 1;
		sortSearch(g);
		$(this).parent().remove();
		$(".sCoditionone").each(function(index,element){
			if($(this).find('.sctitle').attr('id') == show_id){
				$(this).removeClass('sctiActive hidden');
				$(this).find('.select-list').show();
				$(this).find('.more-list').hide();
				$(this).find(".select-details").removeClass("chosed");
				$(this).find(".more-chose").removeClass('hidden');
				$(this).find(".search-contain").removeClass('hidden');
			}
		});
		$('.search-contain').removeClass('hidden');
		$('.error-contain').addClass('hidden');
		if (!slipe) {
			countHeight()
		}
	});
	
	var $that = $("#J_sort_content");
	//单个分类出现在筛选条件里
	$that.on('click.onechose','.select-details', function(event) {
		var sort_id = $(this).parents('.sCoditionone').find('.sctitle').attr('id');
		var options_id = $(this).attr('id');
		g.option_id[sort_id]=[];
		var arr = g.option_id[sort_id];
		arr.push(options_id);
		g.current_page = 1;
		$('.search-contain').removeClass('hidden');
		$('.error-contain').addClass('hidden');
		sortSearch(g);
		var str = $(this).find('span').text();
		$(this).parents('.sCoditionone').addClass('hidden');
		$(".screen-condition-detail").append('<div class="detail-list" id="'+options_id+'" data-id="'+sort_id+'"><div class="detail-name">'+str+'</div><img src="/images/custom_category/cancel.png"></div>');
		if (!slipe) {
			countHeight()
		}
	});

	//复选框确定按钮,多个分类出现在筛选条件中
	var manyArr; //存放选中id
	var text_str;//存放内容
	$that.on('click.twochose','.conbtn',function(){
		manyArr = [];
		text_str = [];
		var many_str = $(this).parents(".sCoditionone").find(".chosed");
		var sort_id = $(this).parents('.sCoditionone').find('.sctitle').attr('id');
		many_str.each(function(){
			manyArr.push($(this).attr('id'));
			text_str.push($(this).find('span').text());
		});
		g.option_id[sort_id] = manyArr;
		if(manyArr == '') {
			alert('未选择任何分类！');
			return;
		}
		$(this).parents('.sCoditionone').find('.m-select-details').removeClass('chosed');		
		$(this).parents('.sCoditionone').find('.chosecheck').attr('data-id','false').removeClass('chosecheckshow');
		$(this).parents(".sCoditionone").find('.select-list').show();
		$(this).parents(".sCoditionone").find('.more-list').hide();
		$(this).parents(".sCoditionone").removeClass("sctiActive");
		$(".screen-condition-detail").append('<div class="detail-list" data-id="'+sort_id+'"><div class="detail-name">'+text_str.toString()+'</div><img src="/images/custom_category/cancel.png"></div>');
		$(this).parents('.sCoditionone').hide();
		if (!slipe) {
			countHeight()
		}
		g.current_page = 1;
		sortSearch(g);
	})
	
	//清除条件
	$(".clear").on('click',function(){
		$(".screen-condition-detail").text('');
		$(".sCoditionone").removeClass("hidden");
		g.option_id = {};
		sortSearch(g);
		$('.search-contain').removeClass('hidden');
		$('.error-contain').addClass('hidden');
		if (!slipe) {
			countHeight();
		}
	});
	
	//获取点击小图切换大图
	$('#J_detail_content').on('click','.min-png',function() {
		var src_srt = $(this).data('src');
		$(this).parent().addClass('active').siblings().removeClass('active');
		$(this).parents('.img-list').find('.max-png').attr('src',src_srt);
	});

	var temp = 0;
	//上一个
	$('#J_detail_content').on('click.prev','.prev-img',function() {
		var $chosed = $(this).parent().find('.active');
		var chosed_img = $(this).parent().find('.active').index();
		var li_length = $(this).next().find('li').length;
		if ( chosed_img > 0) {
			$chosed.prev().addClass('active').siblings().removeClass('active');
			var img_src = $chosed.prev().find('.min-png').data('src');
			$(this).parents('.img-list').find('.max-png').attr('src',img_src);
			temp = chosed_img;
			temp--;
		} 
		if ( temp < 1) {
			$(this).next().find('ul').css('left','0px');
		}
	});
	//下一个
	$('#J_detail_content').on('click.next','.next-img',function() {
		var $chosed = $(this).prev().find('.active');
		var chosed_img = $(this).prev().find('.active').index();
		var li_length = $(this).prev().find('li').length;
		if ( chosed_img < li_length - 1) {
			$chosed.next().addClass('active').siblings().removeClass('active');
			var img_src = $chosed.next().find('.min-png').data('src');
			$(this).parents('.img-list').find('.max-png').attr('src',img_src);
			temp = chosed_img;
			temp++;
		}
		if (temp > 3) { 
			$(this).prev().find('ul').css('left',-45*(temp-3)+'px');
		} 
	});
	//页面商品点击跳转
	$('#J_detail_content').on('click','p',function() {
		var href_str = $(this).parents('.img-list').attr('data-id');
		window.open("/product?id=" + href_str);
	});

	// 商品排序
	$('.J_sort_btn').on('click', function() {
		g.order_by = {};
		var type = $(this).data('type');
		$('img.top-img').attr('src', "/images/custom_category/up.png")
		$('img.down-img').attr('src', "/images/custom_category/down.png")
		if ($(this).hasClass('active')) {
			if ($(this).hasClass('top')) {
				$(this).removeClass('top').parent('div').find('.top-img').attr('src',  "/images/custom_category/up.png")
				.siblings('.down-img').attr('src', '/images/custom_category/down_chosen.jpg');
				g.order_by[type] = 'desc';
			} else {
				$(this).addClass('top').parent('div').find('.top-img').attr('src',  "/images/custom_category/up_chosen.jpg")
				.siblings('.down-img').attr('src', '/images/custom_category/down.png');
				g.order_by[type] = 'asc'
			}
		} else {
			$('.J_sort_btn').removeClass('active');
			$(this).addClass('active');
			$(this).addClass('top').parent('div').find('.top-img').attr('src',  "/images/custom_category/up_chosen.jpg")
			.siblings('.down-img').attr('src', '/images/custom_category/down.png');
			g.order_by[type] = 'asc'
		}
		sortSearch(g)
	})

});