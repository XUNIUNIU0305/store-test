$(function() {
	// free lunch modalFreeLunch
	$('#free-modal').on('click', function() {
    	$('#modalFreeLunch').modal('show');
	})
    $('#modalFreeLunch').on('click', '.entrance .btn', function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#modalFreeLunch .sub-content').removeClass('in');
        $($(this).attr('href')).addClass('in');
    })
    $('#modalFreeLunch').on('hide.bs.modal', function() {
    	$('#modalFreeLunch .sub-content').removeClass('in');
    	$('#modalFreeLunch .entrance').addClass('in')	
    })
})