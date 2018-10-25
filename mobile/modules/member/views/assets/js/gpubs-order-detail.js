$(function(){

    $('.bottom-nav').addClass('hidden');
    var _g = {
        productId : '',
        supplier : '',
        number : ''
    }

    //拉取数据
    function getOrderDetail(){
        var id = url('?id');
        requestUrl('/gpubs/api/order-detail','GET',{order_id : id},function(data){
            _g.number = data.detail_number;
            _g.productId = data.product.id;
            $('#footer-btn-2').attr('href','/member/express?no=' + data.express_detial.nu);
            if(data.delivery_status == 1){
                $('#footer-btn-2').addClass('hidden');
                $('#footer-btn-3').addClass('hidden');
            }
            $('#collage-detail-box').html(juicer($('#collage-detail-cont').html(),{data : data}));
            //联系客服
            loadScript();
            //确认收货
            $('#footer-btn-3').on('click',function(){
                requestUrl('/member/order/confirm','POST',{no : _g.number},function(data){
                    window.location.href = '/member/gpubs-order/index';
                });
            });
        });
    }
    getOrderDetail();
    
    function loadScript() {
        var req_url = "/goods/get-goods-info";
        var _data = {
            id: url('?id'),
        };

        function success(data) {
            // 绑定客服事件
            var _srp = document.createElement('script');
            _srp.type = 'text/javascript';
            _srp.sync = 'sync';
            _srp.src = JDY_SERVICE_LIST[data.supplier];
            if (!JDY_SERVICE_LIST[data.supplier]) {
                _srp.src = JDY_SERVICE_LIST['default'];
            }
            $('body').append(_srp);
        }
        function errorCB(data) {
            alert(data.data.errMsg);
        }
        requestUrl(req_url, 'get', _data, success, errorCB);
    }

    //复制
    $('#collage-detail-box').on('click','.detail-btn',function(e){
        e.preventDefault();
        var clipboard = new ClipboardJS('.detail-btn');
        
        clipboard.on('success', function(e) {
            alert('复制成功，文本为：' + e.text);
        });
        
        clipboard.on('error', function(e) {
            alert('复制失败，请重新复制');
        });
    });
    

})