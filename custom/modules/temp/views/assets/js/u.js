$(function() {
    // 加载优酷视频
    var url = {
        1: 'XMzYzNjM3NTg0MA==',
        3: 'XMzYzNjgyODgzNg==',
        5: 'XMzYzNjQ1NDIyMA==',
        8: 'XMzYzNjQwMzM1Mg==',
        14: 'XMzYzNjQxMDg0OA==',
        15: 'XMzYzNjQxMTI0NA==',
    }
    $.each(url, function(i ,val) {
        new YKU.Player('youkuplayer-' + i,{
            styleid: '0',
            client_id: '73c46fdbebb30e76',
            vid: val,
            newPlayer: true,
            autoplay: false
        });
    })
})