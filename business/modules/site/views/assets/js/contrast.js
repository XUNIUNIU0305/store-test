$(function() {
    var nowDate = new Date(),
        _Month = (nowDate.getMonth() + 1);
    _day = nowDate.getDate();
    if (_Month < 10) {
        _Month = '0' + _Month
    }
    if (_day < 10) {
        _day = '0' + _day
    }
    var nowTime = nowDate.getFullYear() + '-' + _Month + '-' + _day;
    $('input.J_search_timeStart').val(nowTime);
    $('input.J_search_timeEnd').val(nowTime);
    // add locale
    $.fn.datepicker.dates["zh-CN"] = {
            days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
            daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
            daysMin: ["日", "一", "二", "三", "四", "五", "六"],
            months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            today: "今日",
            clear: "清除",
            format: "yyyy年mm月dd日",
            titleFormat: "yyyy年mm月",
            weekStart: 1
        }
        // init the datepicker
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        orientation: 'bottom'
    });
    $('.J_timeStart_show').on('click', function() {
        $('.J_search_timeStart').datepicker('show')
    })
    $('.J_timeEnd_show').on('click', function() {
        $('.J_search_timeEnd').datepicker('show')
    })

    //获取层级列表
    var levels = {};

    function getLevel() {
        requestUrl('/leader/area/level', 'GET', '', function(data) {
            levels = data;
        }, function(data) {
            showAlert(data.data.errMsg)
        }, false)
    }
    getLevel();
    //获取区域列表
    function getArea(level, id) {
        requestUrl('/leader/area/list', 'GET', { parent_id: id }, function(data) {
            var html = '<div class="col-xs-2">\
                            <select class="selectpicker J_area_box btn-group-xs" data-width="110%" data-haschild="' + data.has_child + '" data-level="' + level + '">\
                                <option value="-1">请选择' + levels[level] + '</option>';
            for (var i = 0; i < data.list.length; i++) {
                html += '<option value="' + data.list[i].id + '">' + data.list[i].name + '</option>'
            }
            html += '</select>\
                </div>'
            $('#J_select_box').append(html);
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        })
    }
    getArea(1, 0);
    //区域联动
    $('#J_select_box').on('change', 'select.J_area_box', function() {
            if (!$(this).data('haschild')) {
                return };
            var level = $(this).data('level') - 0;
            var val = $(this).val() - 0;
            if (val === -1) {
                return };
            $('select.J_area_box:gt(' + (level - 1) + ')').parents('.col-xs-2').remove();
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
            getArea(level + 1, val);
        })
        //获取数据
    function getChart(ids, start, end, type) {
        var data = {
            area_ids: ids,
            date_from: start,
            date_to: end,
            date_type: type
        }
        requestUrl('/site/contrast/chart', 'GET', data, function(data) {
            if (data.length < 1) {return};
            var _id = {};
            $.each(data, function(i, val) {
                if (!_id[val.id]) {
                    _id[val.id] = {};
                    _id[val.id]['date'] = [];
                    _id[val.id]['date'].push(val.date);
                    _id[val.id]['id'] = val.id;
                    _id[val.id]['name'] = val.name;
                    _id[val.id][val.date] = val.achievement;
                } else {
                    _id[val.id][val.date] = val.achievement;
                    _id[val.id]['date'].push(val.date);
                }
            })
            $.each(data, function(i, val) {
                _id[val.id]['money'] = [];
                $.each(_id[val.id].date, function(index, value) {
                    var _index = _id[val.id].date.indexOf(value);
                    _id[val.id]['money'][_index] = _id[val.id][value];
                })
            })
            var _data = [];
            $.each(_id, function(i, val) {
                _data.push(val)
            })
            var html = '<thead>\
                            <tr>\
                                <th>日期</th>';
            for (var i = 0; i < _data.length; i++) {
                html += '<th>' + _data[i].name + '</th>'
            }
            html += '</tr>\
                </thead>\
                <tbody>';
            for (var i = 0; i < _data[0].date.length; i++) {
                html += '<tr>\
                            <td>' + _data[0].date[i] + '</td>'
                for (var j = 0; j < _data.length; j++) {
                    html += '<td>' + _data[j].money[i] + '</td>'
                }
                html += '</tr>'
            }
            html += '</tbody>'
            $('#J_table_data').html(html);
            //折线图
            console.log(_data)
            var _areaName = [],
                _series = [],
                _obj = {},
                _dateArr = [];
            for (var i = 0; i < _data.length; i++) {
                _areaName.push(_data[i].name);
            }
            for (var i = 0; i < _data.length; i++) {
                _obj[i] = {};
                _obj[i].name = _data[i].name;
                _obj[i].type = 'line';
                _obj[i].data = _data[i].money;
            }
            $.each(_obj, function(i,val) {
                _series.push(val)
            })
            _dateArr = _data[0].date;
            var barChart = echarts.init(document.getElementById('J_chart_box'));
            var chartOption = {
                title: {
                    text: '各区域同一时间销售曲线图'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: _areaName,
                    top: 30
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: _dateArr
                },
                yAxis: {
                    type: 'value'
                },
                series: _series
            }
            barChart.setOption(chartOption)
        })
    }
    //日期类型切换
    $('#date_tab span').on('click', function() {
            $(this).siblings('span').removeClass('active');
            $(this).addClass('active');
        })
        //添加日期选择
    var _dates = {};
    $('#J_add_supplier').on('click', function() {
        var len = $('#J_select_box select.J_area_box').length;
        var val = $('select.J_area_box').eq(len - 1).val();
        var level = $('select.J_area_box').eq(len - 1).data('level');
        if (val == -1) {
            val = $('select.J_area_box').eq(len - 2).val();
            level = $('select.J_area_box').eq(len - 2).data('level');
        }
        if (val == -1) return;
        var name = $('select.J_area_box[data-level="' + level + '"] option:selected').html();
        var html = '<div class="btn-group supplier">\
                        <label class="btn btn-default">' + name + '</label><span data-id="' + val + '" class="btn btn-danger J_dele_one"><i class="glyphicon glyphicon-remove"></i></span>\
                    </div>';
        if (_dates[val]) {
            return
        }
        _dates[val] = val;
        $('#J_search_box').append(html);
    })
    $('#J_search_box').on('click', '.J_dele_one', function() {
        var id = $(this).data('id');
        delete _dates[id];
        $(this).parents('.supplier').remove();
    })
    //搜索
    $('#J_search_data_btn').on('click', function() {
        var start = $('.J_search_timeStart').val();
        var end = $('.J_search_timeEnd').val();
        var type = $('#date_tab span[class*="active"]').data('type');
        var ids = [];
        $.each(_dates, function(i, val) {
            ids.push(val)
        })
        if (ids.length < 1) {
            return };
        getChart(ids, start, end, type)
    })
})
