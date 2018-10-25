/**
 * ajax
 */


function Fetch(url, options = {}) {
    options.url = url;
    options = Object.assign({
        method: 'GET'
    }, options)
    options = parseOptions(options)
        
    return new Promise((resolve, reject) => {
        let xhr = new XMLHttpRequest;
        xhr.open(options.method, options.url)
        xhr.addEventListener('load', function(e){
            let res = JSON.parse(xhr.response);
            if(res.status === 200){
                resolve(res.data)
            } else {
                handleError(res.data.errMsg)                
            }
        })

        xhr.addEventListener('error', function(e){
            let xhr = e.target
            handleError(xhr.statusText + '::' + xhr.status + '::' + options.url + '::' + xhr.readyState)
        })

        xhr.send(options.body)
    })
}


function handleError(err){
    alert(err)
}


function parseOptions(options){
    if(typeof options.data !== 'undefined'){
        let data = options.data;
        let form = new FormData;
        for(let key in data){
            form.append(key, data[key])
        }
        options.body = form;
        delete options.data;
    }

    if(typeof options.params !== 'undefined'){
        let params = options.params;
        let query = '';
        for(let key in params){
            query += key + '=' + params[key] + '&'
        }
        options.url += '?' + query.slice(0, -1)
        delete options.params
    }
    return options
}

export default Fetch