!function(e){function t(i){if(n[i])return n[i].exports;var r=n[i]={i:i,l:!1,exports:{}};return e[i].call(r.exports,r,r.exports,t),r.l=!0,r.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,i){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:i})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="/",t(t.s=23)}({0:function(e,t,n){"use strict";function i(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return t.url=e,t=Object.assign({method:"GET"},t),t=o(t),new Promise(function(e,n){var i=new XMLHttpRequest;i.open(t.method,t.url),i.addEventListener("load",function(t){var n=JSON.parse(i.response);200===n.status?e(n.data):r(n.data.errMsg)}),i.addEventListener("error",function(e){var n=e.target;r(n.statusText+"::"+n.status+"::"+t.url+"::"+n.readyState)}),i.send(t.body)})}function r(e){alert(e)}function o(e){if(void 0!==e.data){var t=e.data,n=new FormData;for(var i in t)n.append(i,t[i]);e.body=n,delete e.data}if(void 0!==e.params){var r=e.params,o="";for(var a in r)o+=a+"="+r[a]+"&";e.url+="?"+o.slice(0,-1),delete e.params}return e}Object.defineProperty(t,"__esModule",{value:!0}),t.default=i},23:function(e,t,n){"use strict";function i(){this.id=location.hash.substr(1),this.eleContainer=document.getElementById("container"),this.eleContent=document.getElementById("content"),this.elePrizeName=document.getElementById("prize-name"),this.elePrizeBox=document.getElementById("prize-box"),this.init(),this.eleNotify=document.getElementById("notify"),this.eleScrollBox=document.getElementById("scroll-box"),this.connect()}var r=n(0),o=function(e){return e&&e.__esModule?e:{default:e}}(r);n(24),i.prototype.connect=function(){var e=this;this.eleNotify.style.top=0,(0,o.default)("/lottery/gift/notify").then(function(t){e.maxIntval=t.length;var n="";t.forEach(function(e){n+="<li>\n                恭喜: "+e.account+" 获得 <span>"+e.name+"。</span>\n            </li>"}),e.eleNotify.innerHTML=n,e.runIntval()})},i.prototype.runIntval=function(){var e=this,t=setInterval(function(){var n=e.eleNotify.style.top;n=parseInt(n.slice(0,-2))||0,e.eleNotify.style.top=n-2+"em",2*(e.maxIntval-1)<=-n&&(clearInterval(t),e.connect())},3e3)},i.prototype.init=function(){var e=this;(0,o.default)("/lottery/gift/view",{params:{item_id:this.id}}).then(function(t){e.render(t)})},i.prototype.render=function(e){if(0==e.result)return this.eleContainer.classList.add("empty"),this.elePrizeName.innerHTML="很遗憾，您未抽到奖品！<br>不要灰心继续努力吧！你可以的噢～",this.eleContent.classList.add("gift-empty"),!1;this.eleContainer.classList.add("not-empty");var t=e.prize;this.elePrizeName.innerHTML=""+e.prize.name,10==t.type?(this.eleContent.classList.add("gift-normal"),this.elePrizeBox.innerHTML=""):(this.eleContent.classList.add("gift-cash"),this.elePrizeBox.innerHTML='<div class="bag"></div>\n        <div class="cash">\n            <i></i>\n            <i></i>\n            <i></i>\n            <i></i>\n            <i></i>\n            <i></i>\n        </div>')},new i},24:function(e,t){}});