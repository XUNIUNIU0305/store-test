$(function() {
	//获取邀请记录
	function getInfo() {
		requestUrl('/member/invite/code-info', 'GET', '', function(data) {
			$('#J_code').text(data.id);
			$('#J_address').text(data.address);
			$('#J_invite').attr('href', '/member/invite/list?id=' + data.id);
		})
	}
	getInfo()
})