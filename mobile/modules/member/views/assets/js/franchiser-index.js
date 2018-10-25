

$(function(){

    var _g = {
        page : 1,
        status : '',
        index : 0,
        startDate : '',
        endDate : '',
        product_title : '',
        group_number : '',
        account : '',
        ladingCode : '',
        flag : false
    }
    var regAll = /^(\d{4})-(\d)-(\d)$/,
        regL = /^(\d{4})-(\d)-(\d{2})$/,
        regR = /^(\d{4})-(\d{2})-(\d)$/;
        
    $('.bottom-nav').addClass('hidden');

    //获取url参数
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

    var _flag = getSearchAtrr('flag');
    if(_flag == 'true'){
        var code = getSearchAtrr('code');
        _g.ladingCode = Number(code);
        queryData();
    }

    //拉取自提核销数据
    function getFranchiserList(param){
        var _default = {
            current_page : _g.page,
            page_size : 3
        }
        $.extend(_default,param);
        requestUrl('/member/franchiser/detail-list','GET',_default,function(data){
            if(data.total_count <= 0){
                $('#unrelated').removeClass('hidden');
                $('#self-lifting-footer').addClass('hidden');
                return;
            } 
            $('#unrelated').addClass('hidden');
            $('#self-lifting-footer').removeClass('hidden');
            _g.maxPage = Math.ceil(data.total_count / 3);
            $('#section-list').append(juicer($('#section-cont').html(),{data : data.data, total : data.total_count}));
        },function(){
            $('#unrelated').removeClass('hidden');
            $('#self-lifting-footer').addClass('hidden');
        });
    }
    getFranchiserList();

    //提货
    function queryData(){
        requestUrl('/member/franchiser/detail-info','GET',{picking_up_number : _g.ladingCode},function(data){
            if(data.quantity){
                var result = data.quantity - data.picked_up_quantity;
                if(result > 0){
                    $('.pick-up-goods').removeClass('hidden');
                    $('#write-off-box').html(juicer($('#write-off-cont').html(),{data : data, number : result}));
                    //重置
                    $('#hx-btn-reset').on('click',function(){   
                        if($('#J-number').val() != 1){
                            $('#J-del').css('color','#ccc');
                            $('#J-number').val(1);
                            $('#J-add').css('color','#000');
                        }
                    });
                }else {
                    alert('未找到自提拼购订单,请重新输入提货码');
                }
            }else {
                alert("未找到自提拼购订单,请重新输入提货码");
            }
        });
    }
    
    //确定提货
    $('#hx-btn-confirm').on('click',function(){
        var j_number = $('#J-number').val();
        if(_g.flag == true){
            _g.ladingCode = Number($('#nav-input').val().trim());
        }
        if(/^\d+$/.test(j_number)){
            _g.number = Number(j_number);
            pickUpGoods();
        }else {
            alert("请正确输入提取数量！");
        }
    });

    //确定提货
    function pickUpGoods(){
        requestUrl('/member/franchiser/pick-up','POST',{picking_up_number : _g.ladingCode, quantity : _g.number},function(data){
            $('#nav-input').val('');
            $('#section-list').html('');
            $('#pick-up-goods').addClass('hidden');
            getFranchiserList({current_page : 1});
            alert('提货成功！')
        });
    }

    //提货
    $('#th-btn').on('click',function(){
        _g.ladingCode = $('#nav-input').val().trim();
        if(/\d{6,}/.test(_g.ladingCode)){
            _g.flag = true;
            queryData();
        }else {
            alert('请输入6位或6位以上提货码');
        }
    });

    function contReset(){
        _g.index = 0;
        _g.status = '';
        $('#order-status .status-item').eq(0).addClass('active').siblings().removeClass('active');
        $('#status-date .status-item').eq(0).addClass('active').siblings().removeClass('active');
        $('#order-attr .order-attr-prompt').val('');
    }

    //筛选
    $('#filter-txt').on('click',function(){
        $('.condition-screening-box').removeClass('hidden');
        contReset();
    });
    
    //关闭提货
    $('#write-off-box').on('click','#goods-icon',function(){
        $('.pick-up-goods').addClass('hidden');
        $('#J-number').val(1);
    });

    //重置筛选
    $('#btn-reset').on('click',function(){
        _g.product_title = '';
        _g.group_number = '';
        _g.account = '';
        contReset();
    });

    //确认筛选
    $('#btn-confirm').on('click',function(){
        $('#section-list').html('');
        _g.page = 1;
        _g.product_title = $('#shop-name').val().trim();
        _g.group_number = $('#collage-number').val().trim();
        _g.account = $('#store-number').val().trim();
        _date = new Date();
        
        var year = _date.getFullYear(),
            month = _date.getMonth() + 1,
            day = _date.getDate(),
            _year = year - 1,
            _month = month - 1,
            _month3 = month - 3,
            _month6 = month - 6;

        switch(_g.index){
            case 1 :                //0-1个月
                _g.endDate = year + "-" + month + "-" + day;
                if(month == 1){
                    _g.startDate = _year + "-" + 12 + "-" + day;
                }else if(month == 2 || month == 4 || month == 6 || month == 8 || month == 9 || month == 11){
                    _g.startDate = year + "-" + _month + "-" + day;
                }else if(month == 3){
                    if(day >= 28){
                        _g.startDate = year + "-" + _month + "-" + 28;
                    }else {
                        _g.startDate = year + "-" + _month + "-" + day;
                    }
                }else if(month == 5 || month == 7 || month == 10 || month == 12){
                    if(day >= 30){
                        _g.startDate = year + "-" + _month + "-" + 30;
                    }else {
                        _g.startDate = year + "-" + _month + "-" + day;
                    }
                }
                break;
            case 2 :                 //1-3个月
                if(month == 1){
                    _g.startDate = _year + "-" + 10 + "-" + day;
                    _g.endDate = _year + "-" + 12 + "-" + day;
                }else if(month == 2){
                    _g.startDate = _year + "-" + 11 + "-" + day;
                    _g.endDate = year + "-" + _month + "-" + day;
                }else if(month == 3){
                    _g.startDate = _year + "-" + 12 + "-" + day;
                    if(day >= 28){
                        _g.endDate = year + "-" + _month + "-" + 28;
                    }else {
                        _g.endDate = year + "-" + _month + "-" + day;
                    }
                }else if(month == 4 || month == 6 || month == 8 || month == 9 || month == 11){
                    _g.startDate = year + "-" + _month3 + "-" + day;
                    _g.endDate = year + "-" + _month + "-" + day;
                }else if(month == 5){
                    if(_month3 == 2){
                        if(day >= 28){
                            _g.startDate = year + "-" + _month3 + "-" + 28;
                        }else {
                            _g.startDate = year + "-" + _month3 + "-" + day;
                        }
                    }
                    if(_month == 4){
                        if(day >= 30){
                            _g.endDate = year + "-" + _month + "-" + 30;
                        }else {
                            _g.endDate = year + "-" + _month + "-" + day;
                        }
                    }
                }else if(month == 7 || month == 12){
                    if(day >= 30){
                        _g.startDate = year + "-" + _month3 + "-" + 30;
                        _g.endDate = year + "-" + _month + "-" + 30;
                    }else {
                        _g.startDate = year + "-" + _month3 + "-" + day;
                        _g.endDate = year + "-" + _month + "-" + day;
                    }
                }else if(month == 10){
                    _g.startDate = year + "-" + _month3 + "-" + day;
                    if(day >= 30){
                        _g.endDate = year + "-" + _month + "-" + 30;
                    }else {
                        _g.endDate = year + "-" + _month + "-" + day;
                    }
                }
                break;
            case 3:               //3-6个月
                if(month == 1 || month == 2 || month == 3 || month == 4 || month == 6){
                    var month7 = month + 6;
                    var month10 = month + 9;
                    _g.startDate = _year + "-" + month7 + "-" + day;
                    if(month10 <= 12){
                        _g.endDate = _year + "-" + month10 + "-" + day;
                    }else {
                        _g.endDate = year + "-" + _month3 + "-" + day;
                    }
                }else if(month == 5){
                    if(month + 6 == 11){
                        if(day >= 30){
                            _g.startDate = _year + "-" + 11 + "-" + 30;
                        }else {
                            _g.startDate = _year + "-" + 11 + "-" + day;
                        }
                    }
                    if(_month3 == 2){
                        if(day >= 28){
                            _g.endDate = year + "-" + _month3 + "-" + 28;
                        }else {
                            _g.endDate = year + "-" + _month3 + "-" + day;
                        }
                    }
                }else if(month == 7){
                    _g.startDate = year + "-" + _month6 + "-" + day;
                    if(day >= 30){
                        _g.endDate = year + "-" + _month3 + "-" + 30;
                    }else {
                        _g.endDate = year + "-" + _month3 + "-" + day;
                    }
                }else if(month == 8){
                    if(day >= 28){
                        _g.startDate = year + "-" + _month6 + "-" + 28;
                    }else {
                        _g.startDate = year + "-" + _month6 + "-" + day;
                    }
                    _g.endDate = _year + "-" + _month3 + "-" + day;
                }else if(month == 9 || month == 11){
                    _g.startDate = year + "-" + _month6 + "-" + day;
                    _g.endDate = year + "-" + _month3 + "-" + day;
                }else if(month == 10){
                    if(day >= 30){
                        _g.startDate = year + "-" + _month6 + "-" + 30;
                    }else {
                        _g.startDate = year + "-" + _month6 + "-" + day;
                    }
                    _g.endDate = year + "-" + _month3 + "-" + day;
                }else if(month == 12){
                    if(day >= 30){
                        _g.startDate = year + "-" + _month6 + "-" + 30;
                        _g.endDate = year + "-" + _month3 + "-" + 30;
                    }else {
                        _g.startDate = year + "-" + _month6 + "-" + day;
                        _g.endDate = year + "-" + _month3 + "-" + day;
                    }
                }
                break;
            case 4:         //6-12个月
                _g.startDate = _year + "-" + month + "-" + day;
                if(month == 1 || month == 2 || month == 3 || month == 4 || month == 6 || month == 7 || month == 9 || month == 11){
                    var month7 = month + 6;
                    if(month7 <= 12){
                        _g.endDate = _year + "-" + month7 + "-" + day;
                    }else {
                        _g.endDate = year + "-" + _month6 + "-" + day;
                    }
                }else if(month == 5){
                    if(day >= 30){
                        _g.endDate = _year + "-" + 11 + "-" + 30;
                    }else {
                        _g.endDate = _year + "-" + 11 + "-" + day;
                    }
                }else if(month == 8){
                    if(day >= 28){
                        _g.endDate = year + "-" + _month6 + "-" + 28;
                    }else {
                        _g.endDate = year + "-" + _month6 + "-" + day;
                    }
                }else if(month == 10 || month == 12){
                    if(day >= 30){
                        _g.endDate = year + "-" + _month6 + "-" + 30;
                    }else {
                        _g.endDate = year + "-" + _month6 + "-" + day;
                    }
                }
                break;
            case 5:         //1年
                _g.startDate = _year + "-" + month + "-" + day;
                _g.endDate = year + "-" + month + "-" + day;
                break;
            case 6:         //1年以上
                _g.startDate = '';
                _g.endDate = _year + "-" + month + "-" + day;
                break;
            default :       //全部
                _g.startDate = '';
                _g.endDate = '';
        }
        //日期格式化
        if(regAll.test(_g.startDate)){
            _g.startDate = _g.startDate.replace(regAll,'$1-0$2-0$3');
            _g.endDate = _g.endDate.replace(regAll,'$1-0$2-0$3');
        }else if(regL.test(_g.startDate)){
            _g.startDate = _g.startDate.replace(regL,'$1-0$2-$3');
            _g.endDate = _g.endDate.replace(regL,'$1-0$2-$3');
        }else if(regR.test(_g.startDate)){
            _g.startDate = _g.startDate.replace(regR,'$1-$2-0$3');
            _g.endDate = _g.endDate.replace(regR,'$1-$2-0$3');
        }
        getFranchiserList({
            current_page : 1,
            status : _g.status,
            group_number : _g.group_number,
            account : _g.account,
            product_title : _g.product_title,
            pick_start_date : _g.startDate,
            pick_end_date : _g.endDate
        });
        $('.condition-screening-box').addClass('hidden');
    });

    //减少数量
    $('#write-off-box').on('click','#J-del',function(){
        var result = Number($('#J-number').val()) - 1;
        if(result > 1) {
            $(this).css('color','#000');
            $('#J-add').css('color','#000');
            $('#J-number').val(result);
        }else {
            $(this).css('color','#ccc');
            $('#J-number').val(1);
        }
    });
    
    //增加数量
    $('#write-off-box').on('click','#J-add',function(){
        var result = Number($('#J-number').val()) + 1;
        if(result > 1){
            $(this).css('color','#000');
            $('#J-del').css('color','#000');
            $('#J-number').val(result);
        }else {
            $(this).css('color','#ccc');
        }
    });

    //切换订单状态
    $('#order-status .status-item').on('click',function(){
        _g.status = $(this).data('status');
        $(this).addClass('active').siblings().removeClass('active');
    });
    
    //切换提货日期
    $('#status-date .status-item').on('click',function(){
        _g.index = $(this).data('index');
        $(this).addClass('active').siblings().removeClass('active');
    });

    // 溢出显示省略号
    function fontSize (biaoqian){
        var Size = parseInt($('.' + biaoqian).css('font-size'));
        var Width = parseInt($('.' + biaoqian).css('width'));
        var num = Width / Size * 2;
        var num1 = Width / Size;
        var num2 = Width / Size * 2 - 1; 
        $('.' + biaoqian).each(function() {
            if ($(this).text().length > num1) {
                $(this).html($(this).text().replace(/\s+/g, "").substr(0, num2) + "...")
            }
        })
    }
    fontSize('attribute-cont');
    fontSize('J-txt-1');

    //上拉加载
    $(window).on('scroll',function(){
        var scrollTop = $(this).scrollTop();   
        var dHeight = $(document).height();       
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight >= dHeight && _g.page != _g.maxPage){
            _g.page++;
            getFranchiserList({
                current_page : _g.page,
                status : _g.status,
                group_number : _g.group_number,
                account : _g.account,
                product_title : _g.product_title,
                pick_start_date : _g.startDate,
                pick_end_date : _g.endDate
            });            
        }
    });

    wechatShare();

    // 微信扫一扫
    function wechatShare() {
        $.ajax({
            url: 'http://106.14.255.215/api/9daye',
            data: {
                m: 'm_js_sdk',
                url: window.location.href
            },
            success: function(data) {
                var data = data.data
                wx.config({
                    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                    appId: data.appId, // 必填，公众号的唯一标识
                    timestamp: data.timestamp, // 必填，生成签名的时间戳
                    nonceStr: data.nonceStr, // 必填，生成签名的随机串
                    signature: data.signature,// 必填，签名，见附录1
                    jsApiList: ['checkJsApi', 'startRecord', 'stopRecord', 'translateVoice', 'scanQRCode', ,'openCard']
                });
                wx.ready(function() {
                    wx.checkJsApi({
                        jsApiList: ['scanQRCode'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
                        success: function(res) {
                            // 以键值对的形式返回，可用的api值true，不可用为false
                            // 如：{"checkResult":{"scanQRCode":true},"errMsg":"checkJsApi:ok"}
                        }
                    });
                    $('#nav-pic').on('click',function(){
                        wx.scanQRCode({
                            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                            scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
                            success: function (res) {
                                var result = res.resultStr; //返回的结果
                                window.location.href = result;
                            }
                        });
                    })
                })
            },
            error: function(data) {
                console.log(data)
            }
        })
    }

})