$(function(){

    //提货
    $('#th-btn').on('click',function(){
        $('.pick-up-goods').removeClass('hidden');
    });

    //筛选
    $('#filter-txt').on('click',function(){
        $('.condition-screening-box').removeClass('hidden');
    });
    
    //关闭提货
    $('#goods-icon').on('click',function(){
        $('.pick-up-goods').addClass('hidden');
        $('#J-number').text(1);
    });

    //关闭筛选
    $('#btn-confirm').on('click',function(){
        $('.condition-screening-box').addClass('hidden');
    });

    //减少数量
    $('#J-del').on('click',function(){
        var result = Number($('#J-number').text()) - 1;
        if(result > 0) {
            $('#J-number').text(result);
        }else {
            $('#J-number').text(0);
        }
    });
    
    //增加数量
    $('#J-add').on('click',function(){
        var result = Number($('#J-number').text()) + 1;
        $('#J-number').text(result);
    });

    //订单状态
    $('#order-status .status-item').on('click',function(){
        $(this).addClass('active').siblings().removeClass('active');
    });
    
    //提货日期
    $('#status-date .status-item').on('click',function(){
        $(this).addClass('active').siblings().removeClass('active');
    });

})