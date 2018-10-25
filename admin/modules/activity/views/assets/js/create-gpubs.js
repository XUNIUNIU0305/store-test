$(function () {

    var _product_id = '';
    var errMSG;
    // 分开自提拼购和送货拼购提示 目前字段一致
    if($('#gp-type').val() == 1){
        errMSG = {
            max_launch_per_user: '账户开团上限',
            min_quantity_per_group: '成团规则',
            lifecycle_per_group: '拼团时间',
            start_datetime: '活动开始时间',
            end_datetime: '活动结束时间',
            description: '微信服务说明',
            share_title: '微信转发主标题',
            share_subtitle: '微信转发副标题',
            filename: '微信转发头图'
        }
    }else if($('#gp-type').val() == 2){
        errMSG = {
            max_launch_per_user: '账户开团上限',
            min_quantity_per_group: '成团规则',
            lifecycle_per_group: '拼团时间',
            start_datetime: '活动开始时间',
            end_datetime: '活动结束时间',
            description: '微信服务说明',
            share_title: '微信转发主标题',
            share_subtitle: '微信转发副标题',
            filename: '微信转发头图'
        }
    }
    
    var nowDate = new Date();
    var nowTime = nowDate.getFullYear() + '-' + (nowDate.getMonth() + 1) + '-' + nowDate.getDate() + '' + nowDate.getHours() + ':' + nowDate.getMinutes() + ':' + nowDate.getSeconds();
    $('input.J_start_time').val(nowTime);
    $('input.J_end_time').val(nowTime);
    $('#to_list').on('click', function () {
        location.href = '/activity/gpubs/index'
    })

    $('.date-picker').datetimepicker({
        locale: 'zh-cn',
        format: "YYYY-MM-DD HH:mm:ss",
        defaultDate: new Date()
    });
    // show date
    $('.date-show').on('click', function() {
        $(this).siblings('input').focus()
    })

    //查询
    $('#search-pro').on('click', function () {
        file_name = '';
        $('#imgShow').attr('src','');
        $('#infoShow').fadeIn(0);
        $('#imgShow').fadeOut(0);
        $('.pro-info-container').find('input').val('')
        $('input.J_search_timeStart').val(nowTime);
        $('input.J_search_timeEnd').val(nowTime);
        var product_id = $('#search_ipt').val()
        getProInfo(product_id)
    })

    $('#search_ipt').on('keydown', function (e) {
        var product_id = $('#search_ipt').val()
        if (e.keyCode == '13') {
            getProInfo(product_id)
        }
    })
    //拉去数据
    function getProInfo (product_id) {
        if ((/^\s*$/).test(product_id)) {
            $('.error-info').removeClass('hidden')
            return
        }
        var pro_info;
        function sucCB (res) {
            pro_info = res
            _product_id = product_id
            var pro_sort =[]
            for (let i = 0; i< res.full_category.length; i++) {
                pro_sort.push(res.full_category[i].name)
            }
            $('.pro-sort').text(pro_sort.join('>'))
            $('.pro-info-container').removeClass('hidden');
            $('.pro-title').text(res.product.title)
            $('.pro-img').attr('src',res.product.mainImage)
            $('#J_table_box').on('blur', '#total-price, .J_price', function () {
                $(this).val($(this).val().replace(/[^0-9.]/g, ''))
                var x = $(this).val().match(/\./g)
                if (x == null) {
                    $(this).val($(this).val().replace(/^0/, ''))
                } else {
                    if (x.length > 1) {
                        alert('数字不合法！')
                        $(this).val('')
                        return
                    } else {
                        var i = $(this).val().indexOf('.')
                        if (i == 0) {
                            str = ('0' + $(this).val().toString())-0
                            $(this).val(str)
                        }
                        if (i != 1) {
                            $(this).val($(this).val().replace(/^0/, ''))
                        }
                        var len = $(this).val().substring(i)
                        if (len.length > 3) {
                            alert('最多输入两位小数！')
                            $(this).val('')
                            return
                        }
                    }
                }
            })
        }
        requestUrl(
            '/activity/gpubs/search',
            'GET',
            { product_id: product_id },
            sucCB,
            '',
            false
        )

        initPro(pro_info)

        return pro_info
    }
    $('#search_ipt').focus(function () {
        $('.error-info').addClass('hidden')
    })

    //新增
    function valTest() {
        // 自提验证
        if($('#gp-type').val() == 1){
            var max_launch_per_user = $('#max_launch_per_user').val(),
                min_quantity_per_group = $('#min_quantity_per_group').val(),
                lifecycle_per_group = $('#lifecycle_per_group').val(),
                start_datetime = $('#J_start_time').val(),
                end_datetime = $('#J_end_time').val(),
                description = $('#description').val(),
                share_title = $('#share_title').val(),
                share_subtitle = $('#share_subtitle').val(),
                filename = file_name;
            var data = {
                max_launch_per_user: max_launch_per_user,
                min_quantity_per_group: min_quantity_per_group,
                lifecycle_per_group: lifecycle_per_group,
                start_datetime: start_datetime,
                end_datetime: end_datetime,
                description: description,
                share_title: share_title,
                share_subtitle: share_subtitle,
                filename: filename
            }
        // 送货验证
        }else if($('#gp-type').val() == 2){
            var min_quantity_per_group = '';
            if($('#delivery').find('input[type="radio"]:checked').attr('title') == 3){
                $.each($('#delivery').find('li:last').find('input[type="text"]'),function(index,item){
                    var _this = $(this);
                    if(_this.val() != '' && _this.val() != undefined && _this.val() != null){
                        min_quantity_per_group = 'main'
                    }else{
                        min_quantity_per_group = ''
                        return false
                    }
                })
            }else if($('#delivery').find('input[type="radio"]:checked').attr('title') == 1 || $('#delivery').find('input[type="radio"]:checked').attr('title') == 2){
                min_quantity_per_group = $('#delivery').find('input[type="radio"]:checked').parent('li').children('input[type="text"]').val();
            }
            var max_launch_per_user = $('#max_launch_per_user').val(),
                lifecycle_per_group = $('#lifecycle_per_group').val(),
                start_datetime = $('#J_start_time').val(),
                end_datetime = $('#J_end_time').val(),
                description = $('#description').val(),
                share_title = $('#share_title').val(),
                share_subtitle = $('#share_subtitle').val(),
                filename = file_name;
            var data = {
                max_launch_per_user: max_launch_per_user,
                min_quantity_per_group: min_quantity_per_group,
                lifecycle_per_group: lifecycle_per_group,
                start_datetime: start_datetime,
                end_datetime: end_datetime,
                description: description,
                share_title: share_title,
                share_subtitle: share_subtitle,
                filename: filename
            }
        }
        
        for (let key in data) {
            if (isEmpty(data[key])) {
                return key
            }
        }
    }


    $('#set_total_btn').on('click', function () {
        $('.J_price').val($('#total-price').val())
        $('.J_count').val($('#total-counts').val())
    })
    //生成已存在商品数据
    function getOldData(proData) {
        var col = Object.keys(proData.attributes).length;
        var row = Object.keys(proData.sku).length;
        var allData = [],
        colSum = [],
        attrName = [];
        var idArray = Object.keys(proData.attributes),
        attrArray = [],
        sortArray = [];
        for (var i = 0; i < col; i++) {
            attrArray[i] = Object.keys(proData.attributes[idArray[i]]);
            sortArray[i] = Object.keys(proData.attributes[idArray[i]][attrArray[i][0]])
            attrName[i] = attrArray[i][0];
            colSum[i] = sortArray[i].length;
            allData[i] = {};
        }
        var rowspan = row;
        for (var i = 0; i < col; i++) {
            rowspan = rowspan / colSum[i];
            for (var j = 0; j < colSum[i]; j++) {
                var z = j * rowspan;
                allData[i][z] = proData.attributes[idArray[i]][attrArray[i][0]][sortArray[i][j]];
            }
        }
        var allDataBase = $.extend(true, [], allData)
        allDataBase.forEach(function (v, i) {
            for (var x in v) {
                rowspan = row;
                for (var j = 0; j < (i + 1); j++) {
                    rowspan = rowspan / colSum[j];
                }
                var n = rowspan * colSum[i];
                var t = 1;
                for (var y = 0; y < i; y++) {
                    t = t * colSum[y]
                }
                for (var z = 1; z < t; z++) {
                    var o = x - 0 + z * n;
                    allData[i][o] = v[x];
                }
            }
        })
        var all = [allData, col, row, colSum, attrName]
        return all;
    }

    //生成表格
    function getTable(allAttr, col, row, colSum, attrName) {
        var data = {
            allAttr: allAttr,
            col: col,
            row: row,
            colSum: colSum,
            attrName: attrName
        }
        $('#J_table_box').children('table').remove();
        var rowspan = function(data) {
            var arr = [];
            $.each(data[0], function(i, n) {
                arr.push(i)
            })
            if (arr.length == 1) {
                return data[1]
            }
            return arr[1]
        }
        var tpl = $('#J_tpl_table').html();
        juicer.register('rowspan_build', rowspan);
        var table = juicer(tpl, data);
        return table;
    }
    // 价格填充
    function setContent(row, proData) {
        var content = [];
        $.each(proData.sku, function (i, v) {
            content.push(v)
        })
        for (var i = 0; i < row; i++) {
            $('.old-price').eq(i).html(content[i].price + ' 元').attr('data-id', content[i].price)
        }
    }

    var proData,all,table
    function initPro(res) {

        proData = res
        all = getOldData(proData);
        table = getTable(all[0], all[1], all[2], all[3], all[4]);
        $('#J_table_box').append(table);
        setContent(all[2], proData);
    }


    //笛卡尔积
    function Cartesian(a, b, c) {
        var ret = [];
        for (var i = 0; i < a.length; i++) {
            for (var j = 0; j < b.length; j++) {
                ret.push(ft(a[i], b[j], c));
            }
        }
        return ret;
    }

    function ft(a, b, c) {
        return a + c + b;
    }
    //多个一起做笛卡尔积
    function multiCartesian(data, sym) {
        var len = data.length;
        if (len == 0)
            return [];
        else if (len == 1)
            return data[0];
        else {
            var r = data[0];
            for (var i = 1; i < len; i++) {
                r = Cartesian(r, data[i], sym);
            }
            return r;
        }
    }

    //获取拼购价格
    function getOldprice() {
        var barcode = [];
        var len = $('#J_table_box table tr').length;
        for (var i = 0; i < len - 1; i++) {
            barcode[i] = $('#J_table_box table tr').eq(i + 1).find('.old-price').attr('data-id')
        }
        return barcode;
    }

    //获取拼购价格
    function getJprice() {
        var barcode = [];
        var len = $('#J_table_box table tr').length;
        for (var i = 0; i < len - 1; i++) {
            barcode[i] = $('#J_table_box table tr').eq(i + 1).find('.J_price').val()
        }
        return barcode;
    }

    //获取拼购总量
    function getJcounts() {
        var barcode = [];
        var len = $('#J_table_box table tr').length;
        for (var i = 0; i < len - 1; i++) {
            barcode[i] = $('#J_table_box table tr').eq(i + 1).find('.J_count').val()
        }
        return barcode;
    }

    //新增
    $('#J_table_box').data({
        'attrkey': ':',
        'attrs': ';'
    })

    $('#add_gpubs').on('click', function () {

        var index = valTest()
        if (index) {
            $('#apxModalAdminAlert').modal('show')
            $('#J_alert_content').text(errMSG[index] + '不能为空！')
            return
        }

        // if ($('#J_table_box tr:eq(0) td').length < 5) {
        //  alert('新增内容不能为空！')
        //  return false;
        // }
        var len = $('.J_price').length;
        for (var i = 0; i < len; i++) {
            var number = $('.J_price').eq(i).val() - 0;
            var cost_price = $('.J_count').eq(i).val() - 0;
            if (number === '' || cost_price === '') {
                alert('所有拼购价格和拼购数量必须填写完整！')
                return false;
            }
            if($('#gp-type').val() == 2){
                if($('#delivery').find('input[type="radio"]:checked').attr('title') == 3){
                    if (cost_price < $('#min_quanlity_per_member_of_group').val() && cost_price != 0){
                        alert('拼购商品数量必须大于每人最少购买数量！')
                        return false;
                    }
                }
            }
            if (number <= 0) {
                alert('拼购价格不能为0！')
                return false;
            }
        }

        var skuData = {};
        var proJprice = getJprice();
        var proJcounts = getJcounts();
        var proOldprice = getOldprice();
        var skuId = []
        $.each(proData.sku, function (i, v) {
            skuId.push(proData.sku[i].id)
        })
        for (var i = 0; i < Object.keys(proData.sku).length; i++) {
            skuData[skuId[i]] = {
                original_price: proOldprice[i],
                price: proJprice[i],
                stock: proJcounts[i]
            }
        }

        // 自提拼货提交数据
        if($('#gp-type').val() == 1){
            var max_launch_per_user = $('#max_launch_per_user').val(),
                min_quantity_per_group = $('#min_quantity_per_group').val(),
                lifecycle_per_group = $('#lifecycle_per_group').val(),
                start_datetime = $('#J_start_time').val(),
                end_datetime = $('#J_end_time').val(),
                description = $('#description').val(),
                share_title = $('#share_title').val(),
                share_subtitle = $('#share_subtitle').val(),
                filename = file_name;

            var data = {
                product_id: _product_id,
                max_launch_per_user: max_launch_per_user,
                min_quantity_per_group: min_quantity_per_group,
                lifecycle_per_group: lifecycle_per_group,
                start_datetime: start_datetime,
                end_datetime: end_datetime,
                sku: skuData,
                description: description,
                share_title: share_title,
                share_subtitle: share_subtitle,
                filename: filename
            }
            setAddMsg(data)
        // 送货拼购提交数据
        }else if($('#gp-type').val() == 2){
            var min_member_per_group = '';
            if($('#delivery').find('input[type="radio"]:checked').attr('title') == 1){
                min_member_per_group = $('#min_member_per_group').val();
            }else if($('#delivery').find('input[type="radio"]:checked').attr('title') == 3){
                min_member_per_group = $('#min_member_per_group2').val();
            }
            var max_launch_per_user = $('#max_launch_per_user').val(),
                lifecycle_per_group = $('#lifecycle_per_group').val(),
                start_datetime = $('#J_start_time').val(),
                end_datetime = $('#J_end_time').val(),
                description = $('#description').val(),
                share_title = $('#share_title').val(),
                share_subtitle = $('#share_subtitle').val(),
                filename = file_name,
                gpubs_type = $('#gp-type').val(),
                gpubs_rule_type = $('#delivery').find('input[type="radio"]:checked').attr('title'),
                min_quanlity_per_member_of_group = $('#min_quanlity_per_member_of_group').val(),
                min_quantity_per_group = $('#min_quantity_per_group2').val();

            var data = {
                product_id: _product_id,
                max_launch_per_user: max_launch_per_user,
                lifecycle_per_group: lifecycle_per_group,
                start_datetime: start_datetime,
                end_datetime: end_datetime,
                sku: skuData,
                description: description,
                share_title: share_title,
                share_subtitle: share_subtitle,
                filename: filename,
                gpubs_type: gpubs_type,
                gpubs_rule_type: gpubs_rule_type,
                min_member_per_group: min_member_per_group,
                min_quanlity_per_member_of_group: min_quanlity_per_member_of_group,
                min_quantity_per_group: min_quantity_per_group
            }
            setAddMsg2(data)
        }
        
    })

    // 新建自提
    function setAddMsg(data) {
        requestUrl(
            '/activity/gpubs/create',
            'POST',
            data,
            function (res) {
                location.href = '/activity/gpubs/index'
            }
        )
    }

    // 新建送货
    function setAddMsg2(data) {
        requestUrl(
            '/activity/gpubs/create-deliver',
            'POST',
            data,
            function (res) {
                location.href = '/activity/gpubs/index'
            }
        )
    }


    //格式控制
    function isEmpty(val)  {
        return (/^\s*$/).test(val)
    }

    $('#search_ipt, #max_launch_per_user, #min_quantity_per_group, .J_count, #lifecycle_per_group, #total-price, #total-counts').blur(function () {
        $(this).val($(this).val().replace(/[^0-9.]/g, ''))
    })

    $('#search_ipt, #max_launch_per_user, #min_quantity_per_group').blur(function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ''))
    })

    $('#total-price, .J_price').on('blur', function () {
        var x = $(this).val().match(/\./g)
        if (x == null) {
            $(this).val($(this).val().replace(/^0/, ''))
        } else {
            if (x.length > 1) {
                alert('数字不合法！')
                $(this).val('')
                return
            } else {
                var i = $(this).val().indexOf('.')
                if (i == 0) {
                    str = ('0' + $(this).val().toString())-0
                    $(this).val(str)
                }
                if (i != 1) {
                    $(this).val($(this).val().replace(/^0/, ''))
                }
                var len = $(this).val().substring(i)
                if (len.length > 3) {
                    alert('最多输入两位小数！')
                    $(this).val('')
                    return
                }
            }
        }
    })

    // 新增送货拼购新成团规则
    $('#gp-type').change(function(){
        if($(this).val() == 1){
            $('#self_lifting').fadeIn(0);
            $('#delivery').fadeOut(0);
        }else if($(this).val() == 2){
            $('#self_lifting').fadeOut(0);
            $('#delivery').fadeIn(0);
        }
    })
    $('#delivery').find('input[type="radio"]').click(function(){
        $('#delivery').find('input[type="text"]').attr('disabled',true).val('')
        $(this).parent('li').find('input[type="text"]').attr('disabled',false)
    })

    // 获取上传文件后缀
    function getSuffix(filename) {
        var pos = filename.lastIndexOf('.');
        var suffix = '';
        if (pos != -1) {
            suffix = filename.substring(pos + 1)
        }
        return suffix;
    }

    // 图片上传
    var file,imgUrl;
    var file_name = '';
    $('#filename').change(function(){
        var suffix = getSuffix($(this).val()).toLowerCase();
        if(suffix!="jpg"&&suffix!="gif"&&suffix!="png"){
            if(suffix == '' || suffix == null || suffix == undefined){
                return false;
            }else{
                $('#apxModalAdminAlert').modal('show')
                $('#J_alert_content').text('图片格式错误,请选择JPG,PNG,GIF图片上传')
                return false;
            }
        }else{
            requestUrl(
                '/site/carousel/get-oss-permission', 
                'GET', 
                {file_suffix: suffix},
                function(data){
                    file_name = data.key[0]
                    file = document.getElementById("filename");
                    imgUrl = window.URL.createObjectURL(file.files[0]);
                    $('#imgShow').attr('src',imgUrl);
                    $('#infoShow').fadeOut(0);
                    $('#imgShow').fadeIn(0);
                    var formFile = new FormData();
                    formFile.append("OSSAccessKeyId",data.OSSAccessKeyId)
                    formFile.append("policy",data.policy)
                    formFile.append("Signature",data.signature)
                    formFile.append("key",file_name)
                    formFile.append("callback",data.callback)
                    formFile.append("file",document.getElementById("filename").files[0])
                    $.ajax({
                        url:data.host,
                        method:'POST',
                        data:formFile,
                        processData: false,
                        contentType: false,
                        success:function(data){
                            $('#apxModalAdminAlert').modal('show')
                            $('#J_alert_content').text('上传成功')
                        },
                        error:function(){
                            $('#apxModalAdminAlert').modal('show')
                            $('#J_alert_content').text('上传失败')
                        }
                    })
                }
            )
        }
    })
})
