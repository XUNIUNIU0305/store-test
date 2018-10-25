$(function () {
    Date.prototype.Format = function (formatStr) {
        var str = formatStr;
        var Week = ['日', '一', '二', '三', '四', '五', '六'];

        str = str.replace(/yyyy|YYYY/, this.getFullYear());
        str = str.replace(/yy|YY/, (this.getYear() % 100) > 9 ? (this.getYear() % 100).toString() : '0' + (this.getYear() % 100));

        str = str.replace(/MM/, this.getMonth() > 9 ? this.getMonth().toString() : '0' + this.getMonth());
        str = str.replace(/M/g, this.getMonth());

        str = str.replace(/w|W/g, Week[this.getDay()]);

        str = str.replace(/dd|DD/, this.getDate() > 9 ? this.getDate().toString() : '0' + this.getDate());
        str = str.replace(/d|D/g, this.getDate());

        str = str.replace(/hh|HH/, this.getHours() > 9 ? this.getHours().toString() : '0' + this.getHours());
        str = str.replace(/h|H/g, this.getHours());
        str = str.replace(/mm/, this.getMinutes() > 9 ? this.getMinutes().toString() : '0' + this.getMinutes());
        str = str.replace(/m/g, this.getMinutes());

        str = str.replace(/ss|SS/, this.getSeconds() > 9 ? this.getSeconds().toString() : '0' + this.getSeconds());
        str = str.replace(/s|S/g, this.getSeconds());

        return str;
    }
    var scroller = new IScroll($('.ranking-list')[0], {
        mouseWheel: true,
        scrollbars: true,
        scrollbars: 'custom',
        preventDefault: false
    })
    function refreshScroll() {
        setTimeout(function () {
            scroller.refresh();
        }, 300)
    }
    function ju_price(data) {
        return parseFloat(data).toFixed(2)
    }
    juicer.register('ju_price', ju_price);
    var ranking = {
        data: [],
        tpl: $('#J_tpl_list').html(),
        getList: function () {
            var _this = this;
            requestUrl('/temp/rank/full-rank', 'GET', '', function(data) {
                if (!data.data) {
                    return
                }
                _this.data = data.data;
                $('#J_list_box').html(juicer(_this.tpl, data.data));
                $('#J_refresh_time').html(data.datetime);
                _this.getUerRanking();
                refreshScroll();
            })
        },
        getUerRanking: function() {
            requestUrl('/temp/rank/user-rank', 'GET', '', function(data) {
                if (data.rank !== 0) {
                    var _html = `<td class="me">` + data.rank + `</td>
                        <td>` + data.account + `</td>
                        <td>` + data.mobile + `</td>
                        <td>` + data.nickname + `</td>
                        <td>` + data.shopname + `</td>
                        <td>￥` + parseFloat(data.consumption).toFixed(2) + `</td>`;
                    $('#J_list_box .J_my_rank').html(_html);
                }
            })  
        },
        init: function() {
            var _this = this;
            this.getList()
            setInterval(function() {
                _this.getList();
            }, 60000)
        }
    }
    ranking.init()
})