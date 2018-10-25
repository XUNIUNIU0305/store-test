import gFetch from './modules/fetch';

import '../styles/gift.scss';


function Run(){
    this.id = location.hash.substr(1);
    this.eleContainer = document.getElementById('container')
    this.eleContent = document.getElementById('content')
    this.elePrizeName = document.getElementById('prize-name')
    this.elePrizeBox = document.getElementById('prize-box')
    this.init()
    this.eleNotify = document.getElementById('notify')
    this.eleScrollBox = document.getElementById('scroll-box')
    this.connect()
}

Run.prototype.connect = function(){
    this.eleNotify.style.top = 0;
    gFetch('/lottery/gift/notify').then(res=>{
        this.maxIntval = res.length;
        let html = '';
        res.forEach(item=>{
            html += `<li>
                恭喜: ${item.account} 获得 <span>${item.name}。</span>
            </li>`
        })
        this.eleNotify.innerHTML = html;
        this.runIntval()
    })
}

Run.prototype.runIntval = function(){
    let _this =  this;
    let timer = setInterval(function(){
        let top = _this.eleNotify.style.top
        top = parseInt(top.slice(0, -2)) || 0
        _this.eleNotify.style.top = top - 2 + 'em';
        if((_this.maxIntval - 1) * 2 <= -top){
            clearInterval(timer);
            _this.connect()
        }
    },3000)
}

Run.prototype.init = function(){
    gFetch('/lottery/gift/view', {
        params: { item_id: this.id }
    }).then(res=>{
        this.render(res)
    })
}

Run.prototype.render = function(res){
    if(res.result == 0){
        this.eleContainer.classList.add('empty')
        this.elePrizeName.innerHTML = '很遗憾，您未抽到奖品！<br>不要灰心继续努力吧！你可以的噢～'
        this.eleContent.classList.add('gift-empty')
        return false;
    }
    this.eleContainer.classList.add('not-empty')
    let prize = res.prize;

    this.elePrizeName.innerHTML = `${res.prize.name}`
    if(prize.type == 10){
        this.eleContent.classList.add('gift-normal')
        this.elePrizeBox.innerHTML = ''
    } else {
        this.eleContent.classList.add('gift-cash')
        this.elePrizeBox.innerHTML = `<div class="bag"></div>
        <div class="cash">
            <i></i>
            <i></i>
            <i></i>
            <i></i>
            <i></i>
            <i></i>
        </div>`;
    }
}

new Run