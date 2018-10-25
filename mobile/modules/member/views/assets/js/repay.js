$(function(){
    var payment,result;
    $(".bottom-nav").addClass("hidden");
    $(".container").css("bottom","0")
    payment = $('#mask_select_payment').find('li.actived').data("pay")
    result = $('#mask_select_payment').find('li.actived').data("name")
    $(".pattern-payment").html(result+" ");
    $('#mask_select_payment li').on('click', function(e){
        var $this = $(this);
        var result = $this.data('name');
        $this.siblings().removeClass('actived');
        $this.addClass('actived');

        $this.siblings().find($(".mode-payment-right span")).removeClass("mode-payment-right-btn")
        $this.find($(".mode-payment-right span")).addClass("mode-payment-right-btn")
        $(".pattern-payment").html(result+" ");
        payment = $(this).data("pay");
        
    });
    // 判断价格是整数还是小数
    function getPrice(price) {
        if (typeof price == "number") {
            price = String(price);
            if (price.split(".").length == 1) {
                // 整数
                return 1;
            } else if (price.split(".").length > 1) {
                // 带小数
                return 2;
            }
        }
    }
    

    // 返回数据
    function huoquPrice(price, integerText, decimalsText) {
        if (getPrice(price) == 1) {
            integerText.html(price);
            decimalsText.html(".00");
        } else if ((getPrice(price) == 2)) {
            integerText.html(String(price).split(".")[0]);
            decimalsText.html("." + String(price).split(".")[1]);
        }
    }

    function getSearchAtrr(attr){
        var attrArr = window.location.search.substr(1).split('&');
        var newArr = attrArr.map((item) => item.split('='));
        var i, len = newArr.length;
        for(i = 0 ; i < len ; i++) {
            if(newArr[i][0] == attr){
                return newArr[i][1];
            }
        }
    }
    var total_price = Number(getSearchAtrr("total_fee"));
    var t1 = $(".Price .pay-price");
    var t2 = $(".Price .pay-decimals");
    huoquPrice(total_price,t1,t2);
    $(".pattern-money").html(total_price+"元")
    function getBalance() {
        requestUrl('/member/index/get-user-balance', 'GET', '', function(data){
             $('.mode-payment-else-yue span').html("￥"+data.rmb.toFixed(2))
        })
    }
    $(".footer").click(function(){
        repayment()
    })
    function repayment() {
        var req_url = "/member/order/repayment";
        var _data = {
            orders_no: [url("?no")],
            payment:payment,
        };
        function success(data) {
            location.href = data.url
        }
        function errorCB(data) {
            alert(data.data.errMsg);
        }
        requestUrl(req_url, 'post', _data, success, errorCB);
    }

})