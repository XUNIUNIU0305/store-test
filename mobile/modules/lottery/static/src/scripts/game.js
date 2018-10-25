import '../styles/game.scss';
import gFetch from './modules/fetch';
import relation from './modules/imageRelation';
import openedLoading from '../images/game/opened-loading.gif';

function Run(){
    //获取工具
    gFetch('/lottery/game/arms').then(res => {
        this.queryElement();
        this.registerEvents();
        this.render(this.data = res);
    })
}

Run.prototype.render = function(data){
    let html = '';
    data.forEach((item, index) => {
        let img = relation[item.id]['arm'];
        html += `<li data-tool="${item.id}">
            <img src="${img}">
            <span class="badge">${item.chance}</span>
        </li>`;
    })
    this.toolNav.innerHTML = html;
    this.toolItems = this.toolNav.querySelectorAll('li')
    this.autoActive()
}

Run.prototype.queryElement = function(){
    this.toolNav = document.getElementById('tool-nav');
    this.manBefore = document.querySelector('.figure.before');
    this.manAfter = document.querySelector('.figure.after');
    this.smoke = document.querySelector('.ani-smoke');
    this.tool = document.querySelector('.ani-tool');
    this.maskBg = document.querySelector('.mask-bg');
    this.eleMan = document.getElementById('man-before');
    this.eleManAfter = document.getElementById('man-after');
    this.eleOpenBtn = document.getElementById('btn-open')
}

Run.prototype.autoActive = function(){
    let has = false;
    for(let i=0;i<this.data.length; i++){
        let item = this.data[i];
        if(item.chance > 0 && !has){
            if(typeof this.id === 'undefined'){
                this.activeLi(this.toolItems[i]);             
            }
            has = true;
        }
        if(item.id == this.id){
            this.activeLi(this.toolItems[i]);
        }
    }

    if(!has){
        alert('暂未获得抽奖机会');
        this.stop = true;
    }
}

Run.prototype.activeLi = function(li){
    this.toolItems.forEach(ele => {
        ele.className = ''
    })
    this.id = li.getAttribute('data-tool')

    li.className = 'active';
    let image = relation[this.id]
    //切换武器
    this.tool.src = image['arm']
    //切换人物
    this.eleMan.src = image['before'];
    this.eleManAfter.src = image['after']
}

Run.prototype.validateChance = function(){
    let res = false;
    this.data.forEach(item=>{
        if(item.id == this.id){
            res = item.chance >= 1
        }
    })
    return res
}

Run.prototype.deleteChance = function(){
    for(let i=0; i<this.data.length; i++){
        if(this.data[i].id == this.id){
            this.data[i].chance -= 1
        }
    }
    this.render(this.data)
}

Run.prototype.registerEvents = function(){

    //拆礼包
    this.eleOpenBtn.addEventListener('touchend', e=>{
        if(!this.openUrl) return false;
        //显示loading图片
        this.eleManAfter.src = openedLoading
        //跳转
        let _this = this;
        clearTimeout(this.timer);
        this.timer = setTimeout(function(){
            location.href = _this.openUrl
        }, 800)
    })

    if(this.stop) return true;
    let _this = this;
    document.addEventListener('touchend', e => {
        
        let tar = e.target;
        // change tool
        if (tar.hasAttribute('data-tool') || (tar.parentElement.hasAttribute('data-tool') && (tar = tar.parentElement))) {
            e.preventDefault();
            e.stopPropagation();
            if(this.played) return false;
            const ammount = parseInt(tar.querySelector('.badge').textContent);
            if (!isFinite(ammount) || ammount === 0) return;
            this.activeLi(tar);
        }

        // game play
        if (tar.hasAttribute('data-play')) {
            e.preventDefault();
            e.stopPropagation();
            if(!this.validateChance()){
                alert('抽奖机会已用尽!');
                return false;
            }
            if(this.played){
                return false;
            }
            this.played = true;
            this.smoke.classList.add('ani-start');
            this.tool.classList.add('ani-start');
            setTimeout(function () {
                _this.manBefore.classList.add('hidden');
                _this.manAfter.classList.remove('hidden');
            }, 400);
            // popup the mask
            setTimeout(function () {
                gFetch('/lottery/game/open', {
                    method: 'post',
                    data: { plan_id: _this.id }
                }).then(res => {
                    _this.maskBg.classList.remove('hidden');
                    _this.deleteChance()
                    let timeout = 2
                    let timer = setInterval(function(){
                        timeout -= 0.5;
                        if(timeout <= 0){
                            clearInterval(timer);
                            _this.maskBg.classList.add('hidden')
                            tar.classList.add('hidden')
                            _this.eleOpenBtn.classList.remove('hidden')
                            _this.openUrl = res.url
                        }
                    }, 500)
                })
            }, 1000);
        }
    }, false)
}

new Run