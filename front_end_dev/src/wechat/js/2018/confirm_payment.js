$(function(){
    $('#mask_select_payment li').on('click', function(e){
        var $this = $(this);
        var result = $this.data('name');
        console.log(result);
        $this.siblings().removeClass('actived');
        $this.addClass('actived');

        $this.siblings().find($(".mode-payment-right span")).removeClass("mode-payment-right-btn")
        $this.find($(".mode-payment-right span")).addClass("mode-payment-right-btn")
        $(".pattern-payment").html(result+" ");
        
    });
    function getBalance() {
        requestUrl('/member/index/get-user-balance', 'GET', '', function(data){
            console.log(data);
             $('.mode-payment-else-yue span').html("￥"+data.rmb.toFixed(2))
        })
    }

})