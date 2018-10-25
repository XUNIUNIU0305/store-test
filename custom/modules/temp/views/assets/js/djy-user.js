//custom/modules/temp/views/assets/js/djy-user.js
$(function(){

    var _g = {
        current_page : 1,
        count : 0,
        total_count : 0,
        flag : true,
        total : 10
    }

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
        requestUrl('/temp/djy/total','GET',{},function(data){
            var result = Math.round(data.total_fee/10000* 100)/100;
            var _money = getTotal(result);
            $('#region-money').html(_money);
        });
    }
    getTotalFee();

    //获取商品型号
    function getAreaSku(oid){
        requestUrl('/temp/djy/sku','GET',{},function(data){
            $('#model-list').html(juicer($('#model-list-item').html(),data));
        });
    }
    getAreaSku();

    //获取门店数据
    function getAreaFeeList(res){
        var param = {
            current_page : 1,
            page_size : 10,
        }
        $.extend(param,res);
        requestUrl('/temp/djy/store-list','GET',param,function(data){
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
                                <td><a class="td-link" href="/temp/djy/order?account=${it.account}">${it.account}</a></td>
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

});
