$(function(){

    var _g = {
        text : '',
        hostname : '',
        protocol : '',
        code : '',
        flag : '',
        productId : ''
    }

    $('.bottom-nav').addClass('hidden');

    function getOrderDetail(){
        var id = url('?id');
        requestUrl('/gpubs/api/order-detail','GET',{order_id : id},function(data){
            _g.productId = data.product.id; //商品id
            $('#collage-detail-box').html(juicer($('#collage-detail-cont').html(),{data : data}));
            if(data.picking_up_log.length > 0){ //提货记录
                $('#collage-detail-box #delivery-record').height(data.picking_up_log.length * 30);
            }else {
                $('#collage-detail-box #delivery-record').height(30);
            }
            //联系客服
            loadScript();
            
            _g.code = $('.lading-code-number').data('code');
            _g.flag = $('.lading-code-number').data('flag');
            _g.hostname = window.location.hostname;
            _g.protocol = window.location.protocol;
            _g.text = _g.protocol + '//' + _g.hostname + '/member/franchiser/index?code=' + _g.code + '&flag=' + _g.flag;
        });
    }
    getOrderDetail();
    
    $('#layer-close').on('click',function(){
        $('#qrcode').html('');
        $('#cargo-floating-layer').addClass('hidden');
    });

    //生成二维码
    $('#collage-detail-box').on('click','#lading-code-pic',function(){
        $('#qrcode').html('');
        $('#cargo-floating-layer').removeClass('hidden');
        if(_g.hostname == 'test.m.9daye.com.cn' || _g.hostname == 'm.9daye.com.cn'){
            qrcode('qrcode',139);
        }
    }); 

    //生成二维码
    function qrcode(_box,_size){
        var qrcode = new QRCode(_box, {
            text: _g.text,
            width: _size,
            height: _size,
            colorDark : '#000000',
            colorLight : '#ffffff',
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    function loadScript() {
        var req_url = "/goods/get-goods-info";
        var _data = {
            id: _g.productId,
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