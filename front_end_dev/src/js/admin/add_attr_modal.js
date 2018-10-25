$(function() {
    $('.J_modal_admin_add_attr .detail-promo-ammount .input-group-addon').on('click', function(e) {
        // add minus function
        if ($(this).children('.glyphicon-minus').length != 0) {
            var _val = $(this).siblings('input').val() - 1;
            $(this).siblings('input').val(_val < 1 ? 1 : _val);
        } else {
            $(this).siblings('input').val($(this).siblings('input').val() - 0 + 1);
        }
        // change the count of inputs
        updateAttrDetailList($(this).siblings('input').val());
    });

    $('.J_modal_admin_add_attr .detail-promo-ammount input').on('change', function(e) {
        updateAttrDetailList($(this).val());
    })

    function updateAttrDetailList(count) {

        if ($('.attr-detail-box .form-group').length == (count - 0)) return;
        else if ($('.attr-detail-box .form-group').length > (count - 0)) {
            $('.attr-detail-box .form-group').last().remove();
            updateAttrDetailList(count)
        } else {
            var template = '<div class="form-group col-xs-4">\
	                        <input type="text" class="form-control">\
	                    </div>'
            $('.attr-detail-box').append(template);
            updateAttrDetailList(count)
        }
    }
})
