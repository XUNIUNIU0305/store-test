$(function() {
	
	//获取提现详情
	function getDetail(id) {
	 	requestUrl('/bank/draw-detail/detail', 'GET', {draw_id: id}, function(data) {
	 		$('#J_draw_number').html(data.draw_number);
	 		var status = '';
	 		if (data.status === 0) {
	 			status = '未审核'
	 		}
	 		if (data.status === 1) {
	 			status = '通过'
	 		}
	 		if (data.status === 2) {
				status = '驳回'
				$('.J_last_status').addClass('active') 
				$('.J_last_status p').text('驳回') 
				$('#J_last_time').text(data.reject_time)
	 		}
	 		if (data.status === 3) {
				status = '失败'
				$('.J_last_status').addClass('active') 
				$('.J_last_status p').text('提现失败')
				$('#J_last_time').text(data.failure_time)
	 		}
	 		if (data.status === 4) {
				status = '成功'
				$('.J_last_status').addClass('active') 
				$('.J_last_status p').text('提现成功')
				$('#J_last_time').text(data.success_time)
	 		}
	 		$('#J_status').html(status);
	 		$('#J_apply_time').html(data.apply_time);
	 		$('#J_bank_name').html(data.bank.bank_name);
	 		$('#J_user_name').html(data.bank.acct_name);
	 		$('#J_bank_code').html(data.bank.acct_no);
	 		$('#J_extract_num').html(data.rmb);
	 		$('#J_remark').html(data.verify_msg);
	 		$('#J_pass_time').html(data.pass_time);
	 		$('#J_reject_time').html(data.reject_time);
	 		$('#J_failure_time').html(data.failure_time);
	 		$('#J_success_time').html(data.success_time);
	 	})
	}
	getDetail(url('?id'))
})