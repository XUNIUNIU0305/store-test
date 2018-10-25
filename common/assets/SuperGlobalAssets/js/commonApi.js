(function () {
    function CommonApi() {
        this.getHost = getHost
        this.getGroup = getGroup
        this.getShop = getShop
        this.getHotKeywords = getHotKeywords
    }

    function getGroup(callback) {
        if (sessionStorage.getItem('HOST9DAYE')) {
            getApi(sessionStorage.getItem('HOST9DAYE') + '/catelog/get-columns', callback)
        } else {
            getHost(function (host) {
                getApi(host + '/catelog/get-columns', callback)
            })
        }
    }

    function getShop(callback) {
        if (sessionStorage.getItem('HOST9DAYE')) {
            getApi(sessionStorage.getItem('HOST9DAYE') + '/floor/get-floors', callback)
        } else {
            getHost(function (host) {
                getApi(host + '/floor/get-floors', callback)
            })
        }
    }

    function getHotKeywords(callback) {
        if (sessionStorage.getItem('HOST9DAYE')) {
            getApi(sessionStorage.getItem('HOST9DAYE') + '/keyword/get-keywords', callback)
        } else {
            getHost(function (host) {
                getApi(host + '/keyword/get-keywords', callback)
            })
        }
    }

    function getApi(api, callback) {
        var xhr = new XMLHttpRequest()

        xhr.onreadystatechange = handler
        xhr.open('GET', api)
        xhr.send(null)

        function handler() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200 || xhr.status === 304) {
                    var JSONFormatedData = JSON.parse(xhr.responseText)
                    callback(JSONFormatedData.data)
                }
            }
        }
    }

    function getHost(callback) {
        if (sessionStorage.getItem('HOST9DAYE')) {
            callback(sessionStorage.getItem('HOST9DAYE'))
            return
        }

        var xhr = new XMLHttpRequest()

        xhr.onreadystatechange = handler
        xhr.open('GET', '/api-hostname')
        xhr.send(null)

        function handler() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200 || xhr.status === 304) {
                    var JSONFormatedData = JSON.parse(xhr.responseText)
                    callback(JSONFormatedData.data.hostname)
                }
            }
        }
    }

    window.apex = window.apex || {}
    if (!window.apex.commonApi) {
        window.apex.commonApi = new CommonApi()
    }
}())
