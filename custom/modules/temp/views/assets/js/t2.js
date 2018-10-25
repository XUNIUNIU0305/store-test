$(function(){

    var _g = {
        current_page : 1,
        count : 0,
        total_count : 0,
        flag : true,
        total : 10
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

    var area_id = getSearchAtrr('area_id');
    var operator_id = getSearchAtrr('operator_id');
    
    if(location.href.indexOf('test') != -1){
        var _host1 = 'http://test.api.9daye.com.cn/djy/quaternary-area-fee-list';
    }else {
        var _host1 = 'http://api.9daye.com.cn/djy/quaternary-area-fee-list';
    }
    //获取运营商列表
    requestUrl(_host1,'GET',{ top_area_id : area_id },function(data){
        $('#region-list').html(juicer($('#region-list-item').html(),{ data : data , id : operator_id }));
        var name = $('.region-list-item:selected').text();
        $('#province').html(name);
        //更换所在区域
        $('#region-list').on('change',function(){
            var val = $(this).val();
            var name = $(this).find('[value='+ val +']').text();
            $('#province').html(name);
            getTotalFee(val);
            getAreaSku(val);
            _g.count = 0;
            $('#data-tab-tbody').html('');
            $('#look-at-more').removeClass('hidden');
            getAreaFeeList({ 
                quaternary_area_id : val,
                current_page : 1
            });
        });
    });
    
    //获取团长信息
    function getCommander(oid){
        if(location.href.indexOf('test') != -1){
            var _host2 = 'http://test.api.9daye.com.cn/djy/commander';
        }else {
            var _host2 = 'http://api.9daye.com.cn/djy/commander';
        }
        requestUrl(_host2,'GET',{ quaternary_area_id : oid },function(data){
            $('#commander-name').text(data.consignee);
            $('#commander-tel').text(data.mobile);
        })
    }
    getCommander(operator_id);

    function getTotal(res){
        var money;
        if(/^\d+$/.test(res)) {
            money = res+'.00';
        }else if(/^\d+\.\d$/.test(res)) {
            money = res+'0';
        }else {
            money = res;
        }
        return money;
    }

    //获取所在区域总销售额
    function getTotalFee(oid){
        if(location.href.indexOf('test') != -1){
            var _host3 = 'http://test.api.9daye.com.cn/djy/quaternary-area-fee';
        }else {
            var _host3 = 'http://api.9daye.com.cn/djy/quaternary-area-fee';
        }
        requestUrl(_host3,'GET',{ quaternary_area_id : oid },function(data){
            var result = Math.round(data.total_fee/10000* 100)/100;
            var _money = getTotal(result);
            $('#region-money').html(_money);
        });
    }
    getTotalFee(operator_id);

    //获取商品型号
    function getAreaSku(oid){
        if(location.href.indexOf('test') != -1){
            var _host4 = 'http://test.api.9daye.com.cn/djy/quaternary-area-sku';
        }else {
            var _host4 = 'http://api.9daye.com.cn/djy/quaternary-area-sku';
        }
        requestUrl(_host4,'GET',{ quaternary_area_id : oid },function(data){
            $('#model-list').html(juicer($('#model-list-item').html(),data));
        });
    }
    getAreaSku(operator_id);

    //获取门店数据
    function getAreaFeeList(res){
        if(location.href.indexOf('test') != -1){
            var _host5 = 'http://test.api.9daye.com.cn/djy/store-fee-list';
        }else {
            var _host5 = 'http://api.9daye.com.cn/djy/store-fee-list';
        }
        var param = {
            quaternary_area_id : operator_id,
            current_page : 1,
            page_size : 10,
        }
        $.extend(param,res);
        requestUrl(_host5,'GET',param,function(data){
            _g.flag = true;
            _g.total_count = data.total_count;
            var html;
            if(data.list.length > 0){
                if(_g.total >= data.total_count){
                    $('.look-at-more').addClass('hidden');
                }else {
                    $('.look-at-more').removeClass('hidden');
                }
                $('.no-data').addClass('hidden');
                $.each(data.list,function(idx,it){
                    html = `<tr>
                                <td>${+idx+1+_g.count}</td>
                                <td><a class="td-link" href="/temp/betabet/p?account=${it.account}">${it.account}</a></td>
                                <td class="td-alone">${it.shop_name}</td>
                                <td>${it.consignee}</td>
                                <td>${it.mobile}</td>
                                <td>${String(it.total_fee).indexOf('.') == -1 ? it.total_fee+'.00' : it.total_fee}</td>
                            </tr>`;
                    $('#data-tab-tbody').append(html);
                });
            }else {
                $('.look-at-more').addClass('hidden');
                $('.no-data').removeClass('hidden');
            }
        })
    }
    getAreaFeeList();

    //加载更多
    $('#look-at-more').on('click',function(){
        if(_g.flag == true){
            _g.flag = false;
            var _number = Math.ceil(_g.total_count / 10);
            _g.current_page+=1;
            if(_g.current_page <= _number){
                _g.total+=10;
                _g.count+=10;
                getAreaFeeList({ current_page : _g.current_page })
            }else {
                $('#look-at-more').addClass('hidden');
            }
        }
    });
    
    setInterval(function(){
        $('#region-list').trigger('change');
    },30000);

});