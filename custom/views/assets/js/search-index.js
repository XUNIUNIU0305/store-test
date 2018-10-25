$(function() {
	var data = {
		keyword:url('?keyword')
	}
	$('#search-ipt').val(data.keyword);
	var gsdata = {
		data:[],
		pageBox:$("#J_page_footer"),
		page:1,
		size:20,
		goods_tpl:$("#J_detail_list").html()
	}
	
	function searchData(data){
		requestUrl('/search/result','GET',data,function(res){
			//品牌
			var tpl = $("#J_tpl_list").html();
			var html = juicer(tpl,res.brand);
			$("#J_tpl_content").html(html);
			//分类字
			if(res.data == '' || (res.category.length == 0 && res.goods.length == 0)) {
				//所有错误
				hiddenTag(1);
			} else if ( res.goods.length == 0 && res.category.length != 0) {
				//只有分类
				hiddenTag(2);
			} else if (res.goods.length != 0 && res.category.length == 0) {
				$('.main-category').addClass('hidden')
			}

			var sort_tpl = $("#J_sort_list").html();
			var sort_html = juicer(sort_tpl,res.category);
			$("#J_sort_content").html(sort_html);
			pageDate(1,20,res.goods);
			gsdata.data = res.goods;
			pagingBuilder.build(gsdata.pageBox, 1, 20, res.goods.length);
			pagingBuilder.click(gsdata.pageBox,function (page) {
				pageDate(page,gsdata.size,gsdata.data);
				$("#J_page_footer").find('li').each(function(){
					if($(this).attr("data-page") == page){
						$(this).addClass('active').siblings().removeClass('active');
					}
				});
			})
		},function(res) {
			hiddenTag(1);
		});
	}
	searchData(data);
	
	//报错隐藏
	function hiddenTag(type){
		if( type === 1 ) {
			$('.error-contain').removeClass('hidden');
			$('#error_all_name').text(url('?keyword'));
			$('.mian-cont').addClass('hidden');
			$('#search_brand_category').addClass('hidden');
			$('#J_page_footer').addClass('hidden');
		}
		if( type === 2 ) {
			$('.error-contain').removeClass('hidden');
			$('#error_goods_name').text(url('?keyword'));
			$('.mian-cont').addClass('hidden');
			$('.main-brand').addClass('hidden');
			$('#J_page_footer').addClass('hidden');
			$('.error-only').removeClass('hidden');
			$('.error-all').addClass('hidden');
		}
	}
	
	//获取点击小图切换大图
	$('#J_detail_content').on('click','.min-png',function() {
		var src_srt = $(this).data('src');
		$(this).parent().addClass('active').siblings().removeClass('active');
		$(this).parents('.img-list').find('.max-png').attr('src',src_srt);
	});

	function pageDate(page,size,total){
		var page_data = total.slice(((page-1)*size), (page * size));
		var goods_html = juicer(gsdata.goods_tpl,page_data);
		$("#J_detail_content").html(goods_html);
	}
	//前一个
	var temp = 0;
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
			$(this).prev().find('ul').css('left','-45px');
		} 
	});

	$("#J_sort_content").on("click","span",function(){
		var end_category_id = $(this).attr("data-id");
		var end_category_name = $(this).text();
		window.location.href = "/search/category?end_category_id="+end_category_id + '&end_category_name=' + end_category_name;
	});
	//页面商品点击跳转
	$('#J_detail_content').on('click', 'p', function () {
		var href_str = $(this).parents('.img-list').attr('data-id');
		window.open("/product?id=" + href_str);
	});

	//获取固定商品
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
})