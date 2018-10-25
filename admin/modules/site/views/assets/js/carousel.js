(function () {
    apex.addScripts([
        'https://cdn.bootcss.com/purl/2.3.1/purl.min.js'
    ], function () {
        var urlParam = purl(location.href).param()
        $('.nav-tabs li:nth-of-type(' + (urlParam.tab || 1) + ') a').tab('show')
    })
}());
