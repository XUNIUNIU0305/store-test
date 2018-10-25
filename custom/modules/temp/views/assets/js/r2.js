$(function(){
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

    if(window.location.href.indexOf('?id') != -1){
        var area_id = getSearchAtrr('id');
        getTotalFee(area_id);
        getAreaSku(area_id);
        getAreaFeeList(area_id);
        setInterval(function(){
            $('#region-list').trigger('change');
        },30000);
    }else {
        var area_id = -1;
    }

    if(location.href.indexOf('test') != -1){
        var _host1 = 'http://test.api.9daye.com.cn/djy/top-area-fee-list';
    }else {
        var _host1 = 'http://api.9daye.com.cn/djy/top-area-fee-list';
    }

    //获取省份列表
    requestUrl(_host1,'GET',{},function(data){
        $('#region-list').html(juicer($('#region-list-item').html(),{ data : data , id : area_id }));
        // $('#region-list').prepend('<option disabled>请选择</option>');
        var name = $('.region-list-item:selected').text();
        $('#province').html(name);
        //更换所在区域
        $('#region-list').on('change',function(){
            var val = $(this).val();
            var name = $(this).find('[value='+ val +']').text();
            $('#province').html(name);
            getTotalFee(val);
            getAreaSku(val);
            $('#tab-tbody-1').html('');
            $('#tab-tbody-2').html('');
            $('#tab-tbody-3').html('');
            getAreaFeeList(val);
        });
    });

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
    function getTotalFee(id){
        if(location.href.indexOf('test') != -1){
            var _host2 = 'http://test.api.9daye.com.cn/djy/top-area-fee';
        }else {
            var _host2 = 'http://api.9daye.com.cn/djy/top-area-fee';
        }
        requestUrl(_host2,'GET',{ top_area_id : id },function(data){
            var result = Math.round(data.total_fee/10000* 100)/100;
            var _money = getTotal(result);
            $('#region-money').html(_money);
        });
    }
    
    //获取商品型号
    function getAreaSku(id){
        if(location.href.indexOf('test') != -1){
            var _host3 = 'http://test.api.9daye.com.cn/djy/top-area-sku';
        }else {
            var _host3 = 'http://api.9daye.com.cn/djy/top-area-sku';
        }
        requestUrl(_host3,'GET',{ top_area_id : id },function(data){
            $('#model-list').html(juicer($('#model-list-item').html(),data));
        });
    }

    //获取运营商数据
    function getAreaFeeList(id){
        if(location.href.indexOf('test') != -1){
            var _host4 = 'http://test.api.9daye.com.cn/djy/quaternary-area-fee-list';
        }else {
            var _host4 = 'http://api.9daye.com.cn/djy/quaternary-area-fee-list';
        }
        requestUrl(_host4,'GET',{ top_area_id : id },function(data){
            var html;
            if(data.length > 0){
                $('.no-data').addClass('hidden');
                $.each(data,function(idx,it){
                    var _index = +idx+1;
                    var result = Math.round(it.total_fee/10000* 100)/100;
                    var _money = getTotal(result);
                    switch(_index % 3){
                        case 0:
                            html = `<tr>
                                        <td>${_index}</td>
                                        <td class="td-alone"><a href="/temp/betabet/t?area_id=${id}&operator_id=${it.area_id}"  + class="td-link">${it.area_name}</a></td>
                                        <td>${_money}</td>
                                    </tr>`;
                            $('#tab-tbody-3').append(html);
                            break;
                        case 1:
                            html = `<tr>
                                        <td>${_index}</td>
                                        <td class="td-alone"><a href="/temp/betabet/t?area_id=${id}&operator_id=${it.area_id}"  + class="td-link">${it.area_name}</a></td>
                                        <td>${_money}</td>
                                    </tr>`;
                            $('#tab-tbody-1').append(html);
                            break;
                        case 2:
                            html = `<tr>
                                        <td>${_index}</td>
                                        <td class="td-alone"><a href="/temp/betabet/t?area_id=${id}&operator_id=${it.area_id}"  + class="td-link">${it.area_name}</a></td>
                                        <td>${_money}</td>
                                    </tr>`;
                            $('#tab-tbody-2').append(html);
                            break;
                    }
                });
            }else {
                $('.no-data').removeClass('hidden');
            }
        })
    }

});