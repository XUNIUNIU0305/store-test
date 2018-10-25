import gFetch from './modules/fetch'
import relation from './modules/imageRelation'

import '../styles/record.scss'

function Run(){
    this.eleRecord = document.getElementById('records')
    this.fetchRecord();
    this.eleTool = document.getElementById('tool-box')
    this.fetchArms();
    this.registerEvent()
}

Run.prototype.registerEvent = function(){
    // mask evt binding
    document.addEventListener('touchend', e => {
        const tar = e.target;

        // toggle tabs
        if (tar.hasAttribute('data-tab')) {
            e.preventDefault();
            e.stopPropagation();

            // remove & add active class
            [].forEach.call(
                tar.parentElement.querySelectorAll('li'),
                (li) => {
                    li.classList.remove('active');
                }
            );
            tar.classList.add('active');

            // locate to the correct content
            const cntId = tar.getAttribute('data-tab');
            [].forEach.call(
                document.querySelectorAll('.record-content > .content'),
                (cnt) => {
                    cnt.classList.add('hidden');
                }
            );
            document.querySelector(cntId).classList.remove('hidden');
        }
    }, false)
}

Run.prototype.fetchRecord = function(){
    gFetch('/lottery/record/winning').then(res => {
        if(res.length === 0) return;
        let html = `<div class="col-3 header">
            <div class="time">中奖时间</div>
            <div class="img">抽奖工具</div>
            <div class="desctiption">中奖奖品</div>
        </div><div class="scroll">`;
        res.forEach(item => {
            let src = relation[item.plan_id]['arm']
            html += `<div class="col-3">
                <div class="time">
                    ${item.open_date}
                </div>
                <div class="img">
                    <img src="${src}"/>
                </div>
                <div class="desctiption">
                    ${item.name}
                </div>
            </div>`
        })
        html += '</div>';
        this.eleRecord.innerHTML = html
    })
}

Run.prototype.fetchArms = function(){
    gFetch('/lottery/record/arms').then(res=>{
        if(res.length === 0) return;
        let html = '';
        res.forEach(item=>{
            let src = relation[item.plan_id]['arm']
            html += `<div class="col-3">
                <div class="time">
                    ${item.created}
                </div>
                <div class="desctiption">
                    ${item.name}
                </div>
                <div class="img">
                    <img src="${src}"/>
                    <span class="badge">×${item.chance}</span>
                </div>
            </div>`
        })
        this.eleTool.innerHTML = html
    })
}

new Run