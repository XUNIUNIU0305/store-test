$(function(){
	//生成商品列表
	var tpl = $('#J_tpl_product').html();
	var compiled_tpl = juicer(tpl);
	function getProductList(data) {
		var result = compiled_tpl.render(data);
		return result;
	}

	//获取商品数据
	function getProducts(page, size) {
		var data = {
			current_page: page,
			page_size: size
		}
		function productCB(data) {
			//生成商品列表
			var result = getProductList(data);
			$('#J_products_box').html(result);
			//生成分页
			var total = Math.ceil(data.total_count/size);
			var pagination = getPagination(page, total);
			$('.J_pagination_box').html(pagination);
			//页面上拉
			var _top = $('#J_products_box').offset().top;
			if ($(document).scrollTop() > (_top - 0 + 200)) {
				$(document).scrollTop(_top - 50);
			}
			// 修改状态
			function setStatus(id, status, $this) {
				var data = {
					product_id: id,
					sale_status: status
				}
				function setCB(data) {
					$this.parents('.apx-item-edit').removeClass('edit-order');
					var newsStatus = '';
					if (status == 1) {
						newsStatus = '状态：在售';
						$this.parents('.apx-item-edit').removeClass('disabled');
					}
					if (status == 2) {
						newsStatus = '状态：未售';
						$this.parents('.apx-item-edit').addClass('disabled');
					}
					$this.parents('.J_id_box').find('.J_now_status').html(newsStatus);
				}
				requestUrl('/product/status', 'POST', data, setCB)
			}
			$('.J_product_submit').off().on('click', function() {
				var $this = $(this);
				var id = $(this).parents('.J_id_box').data('id');
				var status = $(this).parent('.J_select').find('.J_select_brand').val();
				if (status == '上架') {
					status = 1;
				}
				if (status == '下架') {
					status = 2;
				}
				setStatus(id, status, $this);
			})

			//修改状态按钮
			$('.J_edit_product_order').on('click', function (e) {
				var status = $(this).parents('.J_id_box').data('status');
				if (status == 0) {
					alert('商品未完成！');
					return false;
				}
				var $this = $(this).parents('.apx-item-edit');
				if ($this.hasClass('edit-order')) {
					$this.removeClass('edit-order')
					return
				}
			    $(this).parents('.apx-item-edit').addClass('edit-order');
			});

			//点击换页
			$('#J_page_box').on('click', 'li', function() {
				var val = $(this).data('page');
				if (val == undefined) {
					return false
				}
				getProducts(val, size)
			})

			//输入换页
			$('#J_page_search input').on('keyup', function() {
				var number = $(this).val().replace(/\D/g,'') - 0;
				$(this).val(number);
				if ($(this).val().length < 1) {
					$(this).val('1');
					return false
				}
				if ($(this).val() < 1) {
					$(this).val('1');
					return false
				}
				if ($(this).val() > $('#J_page_box').data('max')) {
					$(this).val($('#J_page_box').data('max'))
					return false
				}
			})
			$('#J_page_search a').on('click', function() {
				var n = $('#J_page_search input').val();
				if (n > $('#J_page_box').data('max')) {
					alert('已超过最大分页数')
					return false;
				}
				getProducts(n, size);
			})

			//修改商品按钮
			$('.J_alter').on('click', function() {
				var id = $(this).parents('.J_id_box').data('id');
				window.open('/price?product_id=' + id);
			})
			//修改商品分类
			$('.J_edit_sort').on('click', function() {
				var id = $(this).parents('.J_id_box').data('id');
				var sort = $(this).parents('.J_id_box').data('sort');
				window.open('/release?product_id=' + id + '&category=' + sort);
			})
		}
		requestUrl('/product/list', 'GET', data, productCB)
	}
	getProducts(1, 12);
})