/*! jCarousel - v0.3.4 - 2015-09-23
* http://sorgalla.com/jcarousel/
* Copyright (c) 2006-2015 Jan Sorgalla; Licensed MIT */
!function(a){"use strict";var b=a.jCarousel={};b.version="0.3.4";var c=/^([+\-]=)?(.+)$/;b.parseTarget=function(a){var b=!1,d="object"!=typeof a?c.exec(a):null;return d?(a=parseInt(d[2],10)||0,d[1]&&(b=!0,"-="===d[1]&&(a*=-1))):"object"!=typeof a&&(a=parseInt(a,10)||0),{target:a,relative:b}},b.detectCarousel=function(a){for(var b;a.length>0;){if(b=a.filter("[data-jcarousel]"),b.length>0)return b;if(b=a.find("[data-jcarousel]"),b.length>0)return b;a=a.parent()}return null},b.base=function(c){return{version:b.version,_options:{},_element:null,_carousel:null,_init:a.noop,_create:a.noop,_destroy:a.noop,_reload:a.noop,create:function(){return this._element.attr("data-"+c.toLowerCase(),!0).data(c,this),!1===this._trigger("create")?this:(this._create(),this._trigger("createend"),this)},destroy:function(){return!1===this._trigger("destroy")?this:(this._destroy(),this._trigger("destroyend"),this._element.removeData(c).removeAttr("data-"+c.toLowerCase()),this)},reload:function(a){return!1===this._trigger("reload")?this:(a&&this.options(a),this._reload(),this._trigger("reloadend"),this)},element:function(){return this._element},options:function(b,c){if(0===arguments.length)return a.extend({},this._options);if("string"==typeof b){if("undefined"==typeof c)return"undefined"==typeof this._options[b]?null:this._options[b];this._options[b]=c}else this._options=a.extend({},this._options,b);return this},carousel:function(){return this._carousel||(this._carousel=b.detectCarousel(this.options("carousel")||this._element),this._carousel||a.error('Could not detect carousel for plugin "'+c+'"')),this._carousel},_trigger:function(b,d,e){var f,g=!1;return e=[this].concat(e||[]),(d||this._element).each(function(){f=a.Event((c+":"+b).toLowerCase()),a(this).trigger(f,e),f.isDefaultPrevented()&&(g=!0)}),!g}}},b.plugin=function(c,d){var e=a[c]=function(b,c){this._element=a(b),this.options(c),this._init(),this.create()};return e.fn=e.prototype=a.extend({},b.base(c),d),a.fn[c]=function(b){var d=Array.prototype.slice.call(arguments,1),f=this;return this.each("string"==typeof b?function(){var e=a(this).data(c);if(!e)return a.error("Cannot call methods on "+c+' prior to initialization; attempted to call method "'+b+'"');if(!a.isFunction(e[b])||"_"===b.charAt(0))return a.error('No such method "'+b+'" for '+c+" instance");var g=e[b].apply(e,d);return g!==e&&"undefined"!=typeof g?(f=g,!1):void 0}:function(){var d=a(this).data(c);d instanceof e?d.reload(b):new e(this,b)}),f},e}}(jQuery),function(a,b){"use strict";var c=function(a){return parseFloat(a)||0};a.jCarousel.plugin("jcarousel",{animating:!1,tail:0,inTail:!1,resizeTimer:null,lt:null,vertical:!1,rtl:!1,circular:!1,underflow:!1,relative:!1,_options:{list:function(){return this.element().children().eq(0)},items:function(){return this.list().children()},animation:400,transitions:!1,wrap:null,vertical:null,rtl:null,center:!1},_list:null,_items:null,_target:a(),_first:a(),_last:a(),_visible:a(),_fullyvisible:a(),_init:function(){var a=this;return this.onWindowResize=function(){a.resizeTimer&&clearTimeout(a.resizeTimer),a.resizeTimer=setTimeout(function(){a.reload()},100)},this},_create:function(){this._reload(),a(b).on("resize.jcarousel",this.onWindowResize)},_destroy:function(){a(b).off("resize.jcarousel",this.onWindowResize)},_reload:function(){this.vertical=this.options("vertical"),null==this.vertical&&(this.vertical=this.list().height()>this.list().width()),this.rtl=this.options("rtl"),null==this.rtl&&(this.rtl=function(b){if("rtl"===(""+b.attr("dir")).toLowerCase())return!0;var c=!1;return b.parents("[dir]").each(function(){return/rtl/i.test(a(this).attr("dir"))?(c=!0,!1):void 0}),c}(this._element)),this.lt=this.vertical?"top":"left",this.relative="relative"===this.list().css("position"),this._list=null,this._items=null;var b=this.index(this._target)>=0?this._target:this.closest();this.circular="circular"===this.options("wrap"),this.underflow=!1;var c={left:0,top:0};return b.length>0&&(this._prepare(b),this.list().find("[data-jcarousel-clone]").remove(),this._items=null,this.underflow=this._fullyvisible.length>=this.items().length,this.circular=this.circular&&!this.underflow,c[this.lt]=this._position(b)+"px"),this.move(c),this},list:function(){if(null===this._list){var b=this.options("list");this._list=a.isFunction(b)?b.call(this):this._element.find(b)}return this._list},items:function(){if(null===this._items){var b=this.options("items");this._items=(a.isFunction(b)?b.call(this):this.list().find(b)).not("[data-jcarousel-clone]")}return this._items},index:function(a){return this.items().index(a)},closest:function(){var b,d=this,e=this.list().position()[this.lt],f=a(),g=!1,h=this.vertical?"bottom":this.rtl&&!this.relative?"left":"right";return this.rtl&&this.relative&&!this.vertical&&(e+=this.list().width()-this.clipping()),this.items().each(function(){if(f=a(this),g)return!1;var i=d.dimension(f);if(e+=i,e>=0){if(b=i-c(f.css("margin-"+h)),!(Math.abs(e)-i+b/2<=0))return!1;g=!0}}),f},target:function(){return this._target},first:function(){return this._first},last:function(){return this._last},visible:function(){return this._visible},fullyvisible:function(){return this._fullyvisible},hasNext:function(){if(!1===this._trigger("hasnext"))return!0;var a=this.options("wrap"),b=this.items().length-1,c=this.options("center")?this._target:this._last;return b>=0&&!this.underflow&&(a&&"first"!==a||this.index(c)<b||this.tail&&!this.inTail)?!0:!1},hasPrev:function(){if(!1===this._trigger("hasprev"))return!0;var a=this.options("wrap");return this.items().length>0&&!this.underflow&&(a&&"last"!==a||this.index(this._first)>0||this.tail&&this.inTail)?!0:!1},clipping:function(){return this._element["inner"+(this.vertical?"Height":"Width")]()},dimension:function(a){return a["outer"+(this.vertical?"Height":"Width")](!0)},scroll:function(b,c,d){if(this.animating)return this;if(!1===this._trigger("scroll",null,[b,c]))return this;a.isFunction(c)&&(d=c,c=!0);var e=a.jCarousel.parseTarget(b);if(e.relative){var f,g,h,i,j,k,l,m,n=this.items().length-1,o=Math.abs(e.target),p=this.options("wrap");if(e.target>0){var q=this.index(this._last);if(q>=n&&this.tail)this.inTail?"both"===p||"last"===p?this._scroll(0,c,d):a.isFunction(d)&&d.call(this,!1):this._scrollTail(c,d);else if(f=this.index(this._target),this.underflow&&f===n&&("circular"===p||"both"===p||"last"===p)||!this.underflow&&q===n&&("both"===p||"last"===p))this._scroll(0,c,d);else if(h=f+o,this.circular&&h>n){for(m=n,j=this.items().get(-1);m++<h;)j=this.items().eq(0),k=this._visible.index(j)>=0,k&&j.after(j.clone(!0).attr("data-jcarousel-clone",!0)),this.list().append(j),k||(l={},l[this.lt]=this.dimension(j),this.moveBy(l)),this._items=null;this._scroll(j,c,d)}else this._scroll(Math.min(h,n),c,d)}else if(this.inTail)this._scroll(Math.max(this.index(this._first)-o+1,0),c,d);else if(g=this.index(this._first),f=this.index(this._target),i=this.underflow?f:g,h=i-o,0>=i&&(this.underflow&&"circular"===p||"both"===p||"first"===p))this._scroll(n,c,d);else if(this.circular&&0>h){for(m=h,j=this.items().get(0);m++<0;){j=this.items().eq(-1),k=this._visible.index(j)>=0,k&&j.after(j.clone(!0).attr("data-jcarousel-clone",!0)),this.list().prepend(j),this._items=null;var r=this.dimension(j);l={},l[this.lt]=-r,this.moveBy(l)}this._scroll(j,c,d)}else this._scroll(Math.max(h,0),c,d)}else this._scroll(e.target,c,d);return this._trigger("scrollend"),this},moveBy:function(a,b){var d=this.list().position(),e=1,f=0;return this.rtl&&!this.vertical&&(e=-1,this.relative&&(f=this.list().width()-this.clipping())),a.left&&(a.left=d.left+f+c(a.left)*e+"px"),a.top&&(a.top=d.top+f+c(a.top)*e+"px"),this.move(a,b)},move:function(b,c){c=c||{};var d=this.options("transitions"),e=!!d,f=!!d.transforms,g=!!d.transforms3d,h=c.duration||0,i=this.list();if(!e&&h>0)return void i.animate(b,c);var j=c.complete||a.noop,k={};if(e){var l={transitionDuration:i.css("transitionDuration"),transitionTimingFunction:i.css("transitionTimingFunction"),transitionProperty:i.css("transitionProperty")},m=j;j=function(){a(this).css(l),m.call(this)},k={transitionDuration:(h>0?h/1e3:0)+"s",transitionTimingFunction:d.easing||c.easing,transitionProperty:h>0?function(){return f||g?"all":b.left?"left":"top"}():"none",transform:"none"}}g?k.transform="translate3d("+(b.left||0)+","+(b.top||0)+",0)":f?k.transform="translate("+(b.left||0)+","+(b.top||0)+")":a.extend(k,b),e&&h>0&&i.one("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd",j),i.css(k),0>=h&&i.each(function(){j.call(this)})},_scroll:function(b,c,d){if(this.animating)return a.isFunction(d)&&d.call(this,!1),this;if("object"!=typeof b?b=this.items().eq(b):"undefined"==typeof b.jquery&&(b=a(b)),0===b.length)return a.isFunction(d)&&d.call(this,!1),this;this.inTail=!1,this._prepare(b);var e=this._position(b),f=this.list().position()[this.lt];if(e===f)return a.isFunction(d)&&d.call(this,!1),this;var g={};return g[this.lt]=e+"px",this._animate(g,c,d),this},_scrollTail:function(b,c){if(this.animating||!this.tail)return a.isFunction(c)&&c.call(this,!1),this;var d=this.list().position()[this.lt];this.rtl&&this.relative&&!this.vertical&&(d+=this.list().width()-this.clipping()),this.rtl&&!this.vertical?d+=this.tail:d-=this.tail,this.inTail=!0;var e={};return e[this.lt]=d+"px",this._update({target:this._target.next(),fullyvisible:this._fullyvisible.slice(1).add(this._visible.last())}),this._animate(e,b,c),this},_animate:function(b,c,d){if(d=d||a.noop,!1===this._trigger("animate"))return d.call(this,!1),this;this.animating=!0;var e=this.options("animation"),f=a.proxy(function(){this.animating=!1;var a=this.list().find("[data-jcarousel-clone]");a.length>0&&(a.remove(),this._reload()),this._trigger("animateend"),d.call(this,!0)},this),g="object"==typeof e?a.extend({},e):{duration:e},h=g.complete||a.noop;return c===!1?g.duration=0:"undefined"!=typeof a.fx.speeds[g.duration]&&(g.duration=a.fx.speeds[g.duration]),g.complete=function(){f(),h.call(this)},this.move(b,g),this},_prepare:function(b){var d,e,f,g,h=this.index(b),i=h,j=this.dimension(b),k=this.clipping(),l=this.vertical?"bottom":this.rtl?"left":"right",m=this.options("center"),n={target:b,first:b,last:b,visible:b,fullyvisible:k>=j?b:a()};if(m&&(j/=2,k/=2),k>j)for(;;){if(d=this.items().eq(++i),0===d.length){if(!this.circular)break;if(d=this.items().eq(0),b.get(0)===d.get(0))break;if(e=this._visible.index(d)>=0,e&&d.after(d.clone(!0).attr("data-jcarousel-clone",!0)),this.list().append(d),!e){var o={};o[this.lt]=this.dimension(d),this.moveBy(o)}this._items=null}if(g=this.dimension(d),0===g)break;if(j+=g,n.last=d,n.visible=n.visible.add(d),f=c(d.css("margin-"+l)),k>=j-f&&(n.fullyvisible=n.fullyvisible.add(d)),j>=k)break}if(!this.circular&&!m&&k>j)for(i=h;;){if(--i<0)break;if(d=this.items().eq(i),0===d.length)break;if(g=this.dimension(d),0===g)break;if(j+=g,n.first=d,n.visible=n.visible.add(d),f=c(d.css("margin-"+l)),k>=j-f&&(n.fullyvisible=n.fullyvisible.add(d)),j>=k)break}return this._update(n),this.tail=0,m||"circular"===this.options("wrap")||"custom"===this.options("wrap")||this.index(n.last)!==this.items().length-1||(j-=c(n.last.css("margin-"+l)),j>k&&(this.tail=j-k)),this},_position:function(a){var b=this._first,c=b.position()[this.lt],d=this.options("center"),e=d?this.clipping()/2-this.dimension(b)/2:0;return this.rtl&&!this.vertical?(c-=this.relative?this.list().width()-this.dimension(b):this.clipping()-this.dimension(b),c+=e):c-=e,!d&&(this.index(a)>this.index(b)||this.inTail)&&this.tail?(c=this.rtl&&!this.vertical?c-this.tail:c+this.tail,this.inTail=!0):this.inTail=!1,-c},_update:function(b){var c,d=this,e={target:this._target,first:this._first,last:this._last,visible:this._visible,fullyvisible:this._fullyvisible},f=this.index(b.first||e.first)<this.index(e.first),g=function(c){var g=[],h=[];b[c].each(function(){e[c].index(this)<0&&g.push(this)}),e[c].each(function(){b[c].index(this)<0&&h.push(this)}),f?g=g.reverse():h=h.reverse(),d._trigger(c+"in",a(g)),d._trigger(c+"out",a(h)),d["_"+c]=b[c]};for(c in b)g(c);return this}})}(jQuery,window),function(a){"use strict";a.jcarousel.fn.scrollIntoView=function(b,c,d){var e,f=a.jCarousel.parseTarget(b),g=this.index(this._fullyvisible.first()),h=this.index(this._fullyvisible.last());if(e=f.relative?f.target<0?Math.max(0,g+f.target):h+f.target:"object"!=typeof f.target?f.target:this.index(f.target),g>e)return this.scroll(e,c,d);if(e>=g&&h>=e)return a.isFunction(d)&&d.call(this,!1),this;for(var i,j=this.items(),k=this.clipping(),l=this.vertical?"bottom":this.rtl?"left":"right",m=0;;){if(i=j.eq(e),0===i.length)break;if(m+=this.dimension(i),m>=k){var n=parseFloat(i.css("margin-"+l))||0;m-n!==k&&e++;break}if(0>=e)break;e--}return this.scroll(e,c,d)}}(jQuery),function(a){"use strict";a.jCarousel.plugin("jcarouselControl",{_options:{target:"+=1",event:"click",method:"scroll"},_active:null,_init:function(){this.onDestroy=a.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",a.proxy(this._create,this))},this),this.onReload=a.proxy(this._reload,this),this.onEvent=a.proxy(function(b){b.preventDefault();var c=this.options("method");a.isFunction(c)?c.call(this):this.carousel().jcarousel(this.options("method"),this.options("target"))},this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy).on("jcarousel:reloadend jcarousel:scrollend",this.onReload),this._element.on(this.options("event")+".jcarouselcontrol",this.onEvent),this._reload()},_destroy:function(){this._element.off(".jcarouselcontrol",this.onEvent),this.carousel().off("jcarousel:destroy",this.onDestroy).off("jcarousel:reloadend jcarousel:scrollend",this.onReload)},_reload:function(){var b,c=a.jCarousel.parseTarget(this.options("target")),d=this.carousel();if(c.relative)b=d.jcarousel(c.target>0?"hasNext":"hasPrev");else{var e="object"!=typeof c.target?d.jcarousel("items").eq(c.target):c.target;b=d.jcarousel("target").index(e)>=0}return this._active!==b&&(this._trigger(b?"active":"inactive"),this._active=b),this}})}(jQuery),function(a){"use strict";a.jCarousel.plugin("jcarouselPagination",{_options:{perPage:null,item:function(a){return'<a href="#'+a+'">'+a+"</a>"},event:"click",method:"scroll"},_carouselItems:null,_pages:{},_items:{},_currentPage:null,_init:function(){this.onDestroy=a.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",a.proxy(this._create,this))},this),this.onReload=a.proxy(this._reload,this),this.onScroll=a.proxy(this._update,this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy).on("jcarousel:reloadend",this.onReload).on("jcarousel:scrollend",this.onScroll),this._reload()},_destroy:function(){this._clear(),this.carousel().off("jcarousel:destroy",this.onDestroy).off("jcarousel:reloadend",this.onReload).off("jcarousel:scrollend",this.onScroll),this._carouselItems=null},_reload:function(){var b=this.options("perPage");if(this._pages={},this._items={},a.isFunction(b)&&(b=b.call(this)),null==b)this._pages=this._calculatePages();else for(var c,d=parseInt(b,10)||0,e=this._getCarouselItems(),f=1,g=0;;){if(c=e.eq(g++),0===c.length)break;this._pages[f]=this._pages[f]?this._pages[f].add(c):c,g%d===0&&f++}this._clear();var h=this,i=this.carousel().data("jcarousel"),j=this._element,k=this.options("item"),l=this._getCarouselItems().length;a.each(this._pages,function(b,c){var d=h._items[b]=a(k.call(h,b,c));d.on(h.options("event")+".jcarouselpagination",a.proxy(function(){var a=c.eq(0);if(i.circular){var d=i.index(i.target()),e=i.index(a);parseFloat(b)>parseFloat(h._currentPage)?d>e&&(a="+="+(l-d+e)):e>d&&(a="-="+(d+(l-e)))}i[this.options("method")](a)},h)),j.append(d)}),this._update()},_update:function(){var b,c=this.carousel().jcarousel("target");a.each(this._pages,function(a,d){return d.each(function(){return c.is(this)?(b=a,!1):void 0}),b?!1:void 0}),this._currentPage!==b&&(this._trigger("inactive",this._items[this._currentPage]),this._trigger("active",this._items[b])),this._currentPage=b},items:function(){return this._items},reloadCarouselItems:function(){return this._carouselItems=null,this},_clear:function(){this._element.empty(),this._currentPage=null},_calculatePages:function(){for(var a,b,c=this.carousel().data("jcarousel"),d=this._getCarouselItems(),e=c.clipping(),f=0,g=0,h=1,i={};;){if(a=d.eq(g++),0===a.length)break;b=c.dimension(a),f+b>e&&(h++,f=0),f+=b,i[h]=i[h]?i[h].add(a):a}return i},_getCarouselItems:function(){return this._carouselItems||(this._carouselItems=this.carousel().jcarousel("items")),this._carouselItems}})}(jQuery),function(a,b){"use strict";var c,d,e={hidden:"visibilitychange",mozHidden:"mozvisibilitychange",msHidden:"msvisibilitychange",webkitHidden:"webkitvisibilitychange"};a.each(e,function(a,e){return"undefined"!=typeof b[a]?(c=a,d=e,!1):void 0}),a.jCarousel.plugin("jcarouselAutoscroll",{_options:{target:"+=1",interval:3e3,autostart:!0},_timer:null,_started:!1,_init:function(){this.onDestroy=a.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",a.proxy(this._create,this))},this),this.onAnimateEnd=a.proxy(this._start,this),this.onVisibilityChange=a.proxy(function(){b[c]?this._stop():this._start()},this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy),a(b).on(d,this.onVisibilityChange),this.options("autostart")&&this.start()},_destroy:function(){this._stop(),this.carousel().off("jcarousel:destroy",this.onDestroy),a(b).off(d,this.onVisibilityChange)},_start:function(){return this._stop(),this._started?(this.carousel().one("jcarousel:animateend",this.onAnimateEnd),this._timer=setTimeout(a.proxy(function(){this.carousel().jcarousel("scroll",this.options("target"))},this),this.options("interval")),this):void 0},_stop:function(){return this._timer&&(this._timer=clearTimeout(this._timer)),this.carousel().off("jcarousel:animateend",this.onAnimateEnd),this},start:function(){return this._started=!0,this._start(),this},stop:function(){return this._started=!1,this._stop(),this}})}(jQuery,document);
function jCarouselConnector() {
    // This is the connector function.
    // It connects one item from the navigation carousel to one item from the
    // stage carousel.
    // The default behaviour is, to connect items with the same index from both
    // carousels. This might _not_ work with circular carousels!
    var connector = function(itemNavigation, carouselStage) {
        return carouselStage.jcarousel('items').eq(itemNavigation.index());
    };

	// Setup the carousels. Adjust the options for both carousels here.
	var carouselStage      = $('.carousel-stage').jcarousel();
	var carouselNavigation = $('.carousel-navigation').jcarousel();

	// We loop through the items of the navigation carousel and set it up
	// as a control for an item from the stage carousel.
	carouselNavigation.jcarousel('items').each(function() {
		var item = $(this);

		// This is where we actually connect to items.
		var target = connector(item, carouselStage);

		item
			.on('jcarouselcontrol:active', function() {
				carouselNavigation.jcarousel('scrollIntoView', this);
				item.addClass('active');
			})
			.on('jcarouselcontrol:inactive', function() {
				item.removeClass('active');
			})
			.jcarouselControl({
				target: target,
				carousel: carouselStage
			});
	});

	// Setup controls for the stage carousel
	$('.prev-stage')
		.on('jcarouselcontrol:inactive', function() {
			$(this).addClass('inactive');
		})
		.on('jcarouselcontrol:active', function() {
			$(this).removeClass('inactive');
		})
		.jcarouselControl({
			target: '-=1'
		});

	$('.next-stage')
		.on('jcarouselcontrol:inactive', function() {
			$(this).addClass('inactive');
		})
		.on('jcarouselcontrol:active', function() {
			$(this).removeClass('inactive');
		})
		.jcarouselControl({
			target: '+=1'
		});

	// Setup controls for the navigation carousel
	$('.prev-navigation')
		.on('jcarouselcontrol:inactive', function() {
			$(this).addClass('inactive');
		})
		.on('jcarouselcontrol:active', function() {
			$(this).removeClass('inactive');
		})
		.jcarouselControl({
			target: '-=1'
		});

	$('.next-navigation')
		.on('jcarouselcontrol:inactive', function() {
			$(this).addClass('inactive');
		})
		.on('jcarouselcontrol:active', function() {
			$(this).removeClass('inactive');
		})
		.jcarouselControl({
			target: '+=1'
		});
}
function carouselInit(){
		//item detail gallary config and lazy load
		$('.carousel-stage').jcarousel({
			// Configuration goes here
		}).on('jcarousel:targetin', 'li', function(event, carousel) {
			//Triggered when the item becomes the targeted item.
			// lazyload
			var ele = $(this).find("img");
			var src_cur = ele.attr("ssrc");
			ele.attr("src", src_cur);
		});
		// gallary photo override
		$("[gallary-override] > *").each(function() {
			var ele = $(this),
				target = $(".detail-gallary-override");
			var arg = ele.parent('[gallary-override]').attr("gallary-override"),//override the gallary photo or not
				src_400 = ele.find("img").attr("src");
			ele.on("click", function() {
				if (arg == "true") {
					target.find("img").attr("src",src_400.replace("40x40","400x400"));
					target.show();
				}
				else target.hide();
			});
		});
	}
// 以上为商品照片栏目jcarousel依赖插件与相关逻辑

$(function(){
    //获取图片
    function getBigImg(data) {
    	var img = '';
    	for (var i = 0; i < data.big_images.length; i++) {
    		img += ' <li><span></span><img src="' + data.big_images[i] + '?x-oss-process=image/resize,w_400,h_400,limit_1,m_lfit" class="img-responsive" alt=""></li>'
    	}
    	return img;
	}
	function getSmallImg(data) {
    	var img = '';
    	for (var i = 0; i < data.big_images.length; i++) {
    		img += ' <li><span></span><img src="' + data.big_images[i] + '?x-oss-process=image/resize,w_80,h_80,limit_1,m_lfit" class="img-responsive" alt=""></li>'
    	}
    	return img;
    }
   	//获取产品关键属性
   	function getDetailAttr(data) {
   		var detail = '';
   		for (var i = 0; i < data.SPU.length; i++) {
   			detail += '<div class="col-xs-4">' + data.SPU[i].name + '：' + data.SPU[i].selected_option.name + '</div>'
   		}
   		return detail;
   	}
   	//获取产品销售属性
   	function getSellAttr(data) {
   		var attr = [];
   		$.each(data.SKU.attributes, function(i, v) {
   			var option = [];
   			var name = '';
   			$.each(v, function(index, value) {
   				name = index;
   				$.each(value, function(inde, val) {
   					option.push({
   						id: inde,
   						name: val
   					})
   				})
   			})
   			attr.push({
   				id: i,
   				name: name,
   				options: option
   			})
   		})
   		var tpl_attr = $('#J_tpl_attr').html();
   		var sellAttr = juicer(tpl_attr, attr);
   		return sellAttr;
   	}

	var initPrice, initOPrice;
	var sku_keys = [], sku_data ={}, SKUResult = {};
   	//获取产品信息
    function getProductMsg() {
		var data = {id: url('?id')};
		function productCB(data) {
			group.sku = data.SKU.sku
			var customization = data.customization;
			if (customization == 1) {
				var html = '<a href="/guide/customization" target="_blank"><span>支持定制</span></a>'
				$('.J_customization_box').append(html);
			} else {
				var html = '<a href="/guide/customization" target="_blank"><span>非定制</span></a>'
				$('.J_customization_box').append(html);
			}
			var _limit = ['', '', '邀请门店', '体系内门店', '运营商']
			if (data.customer_limit > 2) {
				$('.J_customization_box').append(' <span>限' + _limit[data.customer_limit] + '购买</span>');
			}
			// 商品图片
			var img = getBigImg(data);
			var _img2 = getSmallImg(data);
			$('#J_product_detail .J_big_img ul').html(img);
			$('#J_product_detail .J_small_img ul').html(_img2);

			/*获取推荐商品*/
		   	getRecommendPro(data.supplier)

			
			// 绑定客服事件
			var _srp = document.createElement('script')
			_srp.type = 'text/javascript'
			_srp.sync = 'sync'
			_srp.src = JDY_SERVICE_LIST[data.supplier]
			if (!JDY_SERVICE_LIST[data.supplier]) {
				_srp.src = JDY_SERVICE_LIST['default']
			}
			$('body').append(_srp)

			// 标题，描述，价格，销量
			$('.J_product_title').html(data.title);
			$('.J_product_description').html(data.description);
			if (data.price.min == data.price.max) {
				$('.J_product_price').html('¥ ' + data.price.min.toFixed(2));
				initPrice = '¥ ' + data.price.min.toFixed(2);
			} else {
				$('.J_product_price').html('¥ ' + data.price.min.toFixed(2) + '-' + data.price.max.toFixed(2));
				initPrice = '¥ ' + data.price.min.toFixed(2) + '-' + data.price.max.toFixed(2);
			}
			if (data.original_price.min == data.original_price.max) {
				$('.J_origin_price').text('¥ ' + data.original_price.min.toFixed(2))
				initOPrice = data.original_price.min.toFixed(2);
			} else {
				$('.J_origin_price').text('¥ ' + data.original_price.min.toFixed(2) + '-' + data.original_price.max.toFixed(2));
				initOPrice =  data.original_price.min.toFixed(2) + '-' + data.original_price.max.toFixed(2);
			}
			if (data.original_price.max == 0) {
				$('.J_origin_price').parents('.origin-price').addClass('hidden')
			} else {
				$('.J_origin_price').parents('.origin-price').removeClass('hidden')
			}
			$('.J_product_sales').html(data.paid);
			// 库存
			var inventory = 0;
			$.each(data.SKU.sku, function(i, v) {
				inventory += v.stock
			})
			$('.J_product_inventory').html(inventory);
			// 商品详情 - 详细内容
			$('.J_product_details').html(data.detail);
			// 商品详情 - 属性列表
			var detail = getDetailAttr(data);
			$('#detail-attr').append(detail);

			// 初始化销量属性
			var x = getSellAttr(data);
			$('#J_sell_attr').append(x);

			// 初始化sku keys
			$('.J_attr_box').each(function(){
				var key = [];
				$(this).find('[attr_id]').each(function(){
					key.push($(this).attr('attr_id'));
				})
				sku_keys.push(key);
			})
			// 初始化有库存的sku_data
			for(var skuOrder in data.SKU.sku){
				var _arr1 = skuOrder.split(';');
				var result = [];
				_arr1.forEach(function(val){
					result.push(val.split(':')[1]);
				})
				if(data.SKU.sku[skuOrder].stock) sku_data[result.join(';')] = data.SKU.sku[skuOrder];
			}

			initSKU();

			//绑定选择属性事件
			$('.J_attr_box label[attr_id]').each(function () {
				var self = $(this);
				var attr_id = self.attr('attr_id');
				if (!SKUResult[attr_id]) {
					// self.attr('disabled', 'disabled');
					self.addClass('disabled')
				}
			}).click(function (e) {
				var self = $(this);

				if(self.hasClass('disabled')) return;

				//选中自己，兄弟节点取消选中
				if(self.hasClass('active')) {
					e.preventDefault();
					e.stopPropagation();
					self.removeClass('active');
					self.find('input')[0].checked = false;
				}
				else self.toggleClass('active').siblings().removeClass('active');

				//已经选择的节点
				var selectedObjs = $('label[attr_id].active');

				if (selectedObjs.length) {
					//获得组合key价格
					var selectedIds = [];
					var groupSku = [];
					selectedObjs.each(function () {
						selectedIds.push($(this).attr('attr_id'));
						groupSku.push($(this).parents('.J_attr_box').data('id') + ':' + $(this).attr('attr_id'))
					});
					selectedIds.sort(function (value1, value2) {
						return parseInt(value1) - parseInt(value2);
					});
					var len = selectedIds.length;
					var price = SKUResult[selectedIds.join(';')].price;
					var original_price = SKUResult[selectedIds.join(';')].original_price;
					var maxPrice = Math.max.apply(Math, price).toFixed(2);
					var minPrice = Math.min.apply(Math, price).toFixed(2);
					$('.J_product_price').text("¥ " + (maxPrice > minPrice ? minPrice + "-" + maxPrice : maxPrice));
					var _stock = SKUResult[selectedIds.join(';')].stock;
					$('.J_product_inventory').text(_stock);
					if (_stock < $('.J_only_int').val()) {
						$('.J_only_int').val(_stock)
					}
					if (original_price) {
						$('.J_origin_price').text('¥ ' + original_price.toFixed(2));
					}
					if (original_price == 0 || !original_price) {
						$('.J_origin_price').parents('.origin-price').addClass('hidden')
					} else {
						$('.J_origin_price').parents('.origin-price').removeClass('hidden')
					}

					// 判断拼团价格
					if (groupSku.length > 0 && Object.keys(group.gSku) > 0) {
						var _string = '';
						$.each(groupSku, function(i, val) {
							_string += val + ';'
						})
						if (group.sku[_string.slice(0, -1)]) {
							var groupId = group.sku[_string.slice(0, -1)].id;
							var groupPrice = group.gSku[groupId].price;
							if (groupPrice) {
								$('.J_group_pro_price').text(groupPrice.toFixed(2))
							}
						}
					}

					//用已选中的节点验证待测试节点 underTestObjs
					$('.J_attr_box label[attr_id]').not(selectedObjs).not(self).each(function () {
						var siblingsSelectedObj = $(this).siblings('.active');
						var testAttrIds = []; //从选中节点中去掉选中的兄弟节点
						if (siblingsSelectedObj.length) {
							var siblingsSelectedObjId = siblingsSelectedObj.attr('attr_id');
							for (var i = 0; i < len; i++) {
								(selectedIds[i] != siblingsSelectedObjId) && testAttrIds.push(selectedIds[i]);
							}
						} else {
							testAttrIds = selectedIds.concat();
						}
						testAttrIds = testAttrIds.concat($(this).attr('attr_id'));
						testAttrIds.sort(function (value1, value2) {
							return parseInt(value1) - parseInt(value2);
						});
						if (!SKUResult[testAttrIds.join(';')]) {
							$(this).addClass('disabled').removeClass('active');
						} else {
							$(this).removeClass('disabled');
						}
					});
				} else {
					//设置默认价格
					$('.J_product_price').text(initPrice);
					if (initOPrice > 0) {
						$('.J_origin_price').text('￥' + initOPrice).parents('.origin-price').removeClass('hidden');
					}
					if ($('.J_group_pro_price').data('price')) {
						$('.J_group_pro_price').text($('.J_group_pro_price').data('price'))	
					}
					//设置属性状态
					$('.J_attr_box label[attr_id]').each(function () {
						SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('disabled') : $(this).addClass('disabled').removeClass(
							'active');
					})
				}
			});
			//加入购物车
		    $('.J_btn_shopping').on('click', function(event) {
		    	if ($('.J_out_login').hasClass('hidden')) {
		    		var con = confirm('您还未登录，是否跳转到登录页？');
		    		if (con == true) {
		    			window.location.href = '/login'
		    		}
		    		return
		    	}
		    	var len = $('.J_attr_box').length;
				$('.J_attr_box').removeClass('has-error');
		    	for (var i = 0; i < len; i++) {
		    		var leng = $('.J_attr_box').eq(i).find('label').length;
		    		if (!$('.J_attr_box').eq(i).find('label').hasClass('active')) {
		    			$('.J_attr_box').eq(i).addClass('has-error')
		    		}
		    	}
		    	if ($('.J_attr_box').hasClass('has-error')) {
		    		return false
		    	}
		    	var cartId = '';
		    	$.each($('label[class*="active"]'), function(i, v) {
		    		cartId += $('label[class*="active"]').eq(i).parents('.J_attr_box').data('id') + ':' + $('label[class*="active"]').eq(i).attr('attr_id') + ';'
		    	})
		    	cartId = cartId.substring(0,cartId.length - 1);
		    	var sku_id = data['SKU']['sku'][cartId].id;
		    	var count = $('.J_only_int').val();
		    	if (count < 1) {
		    		alert('购买数量为0！')
		    		return false
		    	}
		    	var _data = {
		    		product_id: url('?id'),
	    			sku_id: sku_id,
	    			count: count
		    	}
		    	function addCB(data) {
					if ($('body').scrollTop() == 0) {
						$('html').animate({scrollTop: '0px'}, 500);
					} else {
						$('body').animate({scrollTop: '0px'}, 500);
					}
					var offset = $(".btn-cart").offset();
					var addcar = $('.J_btn_shopping');
					var img = $('.J_big_img img').eq(0).attr('src');
					var flyer = $('<img class="u-flyer" src="' + img + '">');
					
					flyer.fly({
						start: {
							left: event.clientX,
							top: event.clientY
						},
						end: {
							left: offset.left+120,
							top: offset.top+10,
							width: 0,
							height: 0
						},
						onEnd: function(){
							requestUrl('/cart/quantity', 'GET', '', function(data) {
								$('.J_cart_count').html(data.quantity);
								$(".J_cart_count").animate({fontSize: '16px'}, 200).animate({fontSize: '12px'}, 200);
							})
						}
					});
		    	}
		    	requestUrl('/product/cart', 'POST', _data, addCB, function(data) {
		    		if (data.status == '3031') {
	                    alert('商品已下架！')
	                } else {
	                    alert(data.data.errMsg)
	                }
		    	})
		    })

		    //立即购买
		    $('.J_buy_now').on('click', function() {
		    	if ($('.J_out_login').hasClass('hidden')) {
		    		var con = confirm('您还未登录，是否跳转到登录页？');
		    		if (con == true) {
		    			window.location.href = '/login'
		    		}
		    		return
		    	}
		    	var len = $('.J_attr_box').length;
				$('.J_attr_box').removeClass('has-error');
		    	for (var i = 0; i < len; i++) {
		    		var leng = $('.J_attr_box').eq(i).find('label').length;
		    		if (!$('.J_attr_box').eq(i).find('label').hasClass('active')) {
		    			$('.J_attr_box').eq(i).addClass('has-error')
		    		}
		    	}
		    	if ($('.J_attr_box').hasClass('has-error')) {
		    		return false
		    	}
		    	var cartId = '';
		    	$.each($('label[class*="active"]'), function(i, v) {
		    		cartId += $('label[class*="active"]').eq(i).parents('.J_attr_box').data('id') + ':' + $('label[class*="active"]').eq(i).attr('attr_id') + ';'
		    	})
		    	cartId = cartId.substring(0,cartId.length - 1);
		    	var sku_id = data['SKU']['sku'][cartId].id;
		    	var count = $('.J_only_int').val();
		    	if (count < 1) {
		    		alert('购买数量为0！')
		    		return false
		    	}
		    	var ndata = {
		    		product_id: url('?id'),
	    			sku_id: sku_id,
	    			count: count
		    	}
		    	function nowBuyCB(data) {
		    		window.location.href = data.url;
		    	}
		    	requestUrl('/product/order', 'POST', ndata, nowBuyCB, function(data) {
		    		if (data.status == '3031') {
	                    alert('商品已下架！')
	                } else {
	                    alert(data.data.errMsg)
	                }
		    	})
		    })

			// 初始化jcarousel
			jCarouselConnector();
			carouselInit();
		}
		requestUrl('/product/info', 'GET', data, productCB);
    }
    getProductMsg()

	//获得对象的key
	function getObjKeys(obj) {
		if (obj !== Object(obj)) throw new TypeError('Invalid object');
		var sku_keys = [];
		for (var key in obj)
			if (Object.prototype.hasOwnProperty.call(obj, key))
				sku_keys[sku_keys.length] = key;
		return sku_keys;
	}

	//把组合的key放入结果集SKUResult
	function add2SKUResult(combArrItem, sku) {
		var key = combArrItem.join(";");
		if (SKUResult[key]) { //SKU信息key属性·
			SKUResult[key].stock += sku.stock;
			SKUResult[key].price.push(sku.price);
		} else {
			SKUResult[key] = {
				stock: sku.stock,
				price: [sku.price],
				original_price: sku.original_price
			};
		}
	}

	//初始化得到结果集
	function initSKU() {
		var i, j, skuKeys = getObjKeys(sku_data);
		for (i = 0; i < skuKeys.length; i++) {
			var skuKey = skuKeys[i]; //一条SKU信息key
			var sku = sku_data[skuKey]; //一条SKU信息value
			var skuKeyAttrs = skuKey.split(";"); //SKU信息key属性值数组
			skuKeyAttrs.sort(function (value1, value2) {
				return parseInt(value1) - parseInt(value2);
			});

			//对每个SKU信息key属性值进行拆分组合
			var combArr = combInArray(skuKeyAttrs);
			for (j = 0; j < combArr.length; j++) {
				add2SKUResult(combArr[j], sku);
			}

			//结果集接放入SKUResult
			SKUResult[skuKeyAttrs.join(";")] = {
				stock: sku.stock,
				price: [sku.price],
				original_price: sku.original_price
			}
		}
	}

	/**
	 * 从数组中生成指定长度的组合
	 * 方法: 先生成[0,1...]形式的数组, 然后根据0,1从原数组取元素，得到组合数组
	 */
	function combInArray(aData) {
		if (!aData || !aData.length) {
			return [];
		}

		var len = aData.length;
		var aResult = [];

		for (var n = 1; n < len; n++) {
			var aaFlags = getCombFlags(len, n);
			while (aaFlags.length) {
				var aFlag = aaFlags.shift();
				var aComb = [];
				for (var i = 0; i < len; i++) {
					aFlag[i] && aComb.push(aData[i]);
				}
				aResult.push(aComb);
			}
		}

		return aResult;
	}


	/**
	 * 得到从 m 元素中取 n 元素的所有组合
	 * 结果为[0,1...]形式的数组, 1表示选中，0表示不选
	 */
	function getCombFlags(m, n) {
		if (!n || n < 1) {
			return [];
		}

		var aResult = [];
		var aFlag = [];
		var bNext = true;
		var i, j, iCnt1;

		for (i = 0; i < m; i++) {
			aFlag[i] = i < n ? 1 : 0;
		}

		aResult.push(aFlag.concat());

		while (bNext) {
			iCnt1 = 0;
			for (i = 0; i < m - 1; i++) {
				if (aFlag[i] == 1 && aFlag[i + 1] == 0) {
					for (j = 0; j < i; j++) {
						aFlag[j] = j < iCnt1 ? 1 : 0;
					}
					aFlag[i] = 0;
					aFlag[i + 1] = 1;
					var aTmp = aFlag.concat();
					aResult.push(aTmp);
					if (aTmp.slice(-n).join("").indexOf('0') == -1) {
						bNext = false;
					}
					break;
				}
				aFlag[i] == 1 && iCnt1++;
			}
		}
		return aResult;
	}

    //判断输入购买数量
    $('.J_only_int').on('keyup', function() {
    	var number = $(this).val().replace(/\D/g,'') - 0;
    	$(this).val(number);
    	var inventory = $('.J_product_inventory').html() - 0;
    	if (number > inventory) {
    		$(this).val(inventory)
    	}
    })

    //减号
    $('.J_input_minus').on('click', function() {
    	var nub = $('.J_only_int').val() - 0;
    	if (nub > 1) {
	    	$('.J_only_int').val(nub - 1)
    	}
    })
    //加号
    $('.J_input_add').on('click', function() {
    	var nub = $('.J_only_int').val() - 0;
    	if (nub < $('.J_product_inventory').html() - 0 && nub < 999) {
	    	$('.J_only_int').val(nub + 1)
    	}
    })


	 /*获取推荐商品*/
	var tpl_recommend = $('#J_tpl_recommend').html();
	function getRecommendPro(id) {
		requestUrl('/product-recommend/group-purchase-goods', 'GET', {id: id}, function(data) {
			if (data.length < 1) {return}
			/*渲染推荐商品*/
			var len = Math.ceil(data.length / 6);
			var items = [];
			for (var i = 0; i < len; i++) {
				items[i] = {
					items: data.slice(i * 6, (i * 6 + 6))
				}
			}
			$('#J_recommend_pro').html(juicer(tpl_recommend, items))
			$('.recommend-group').removeClass('hidden');
		})
	}

	// 获取新拼团信息
	var group = {
		gSku: {},
		getInfo: function() {
			requestUrl('/gpubs/api/product', 'GET', {product_id:url('?id')}, function(data) {
				if (window.CUSTOM_USER_LEVEL === 4) {
					if (data.expire_time > 0 && data.status == 1) {
						group.gSku = data.sku
						$('.group-pro-tip').removeClass('hidden')
						$('.group-end-time-box').removeClass('hidden')
						// 倒计时
						jdy.seckill.timerFun(data.expire_time, function(data) {
							$('.group-end-time-box .day-box').text(data.day)
							$('.group-end-time-box .hour-box').text(data.hour)
							$('.group-end-time-box .minu-box').text(data.minute)
							$('.group-end-time-box .sec-box').text(data.second)
						}, function() {
							location.reload()
						})

						if(data.gpubs_type == 1){
							$('.J_group_pro_num').text(data.min_quantity_per_group + '件起团')
							$('.group-pro-tip').html('自提拼购');
						}else if(data.gpubs_type == 2){
							$('.group-pro-tip').html('送货拼购');
							if(data.gpubs_rule_type == 1){
								$('.J_group_pro_num').text(data.min_member_per_group + '人起团')
							}else if(data.gpubs_rule_type == 2){
								$('.J_group_pro_num').text(data.min_quantity_per_group + '件起团')
							}else if(data.gpubs_rule_type == 3){
								$('.J_group_pro_num').text(data.min_member_per_group + '人，每人' + data.min_quanlity_per_member_of_group + '件起团')
							}
						}
						
						// 价格
						if (data.min_price === data.max_price) {
							$('.J_group_pro_price').text(data.min_price.toFixed(2)).data('price', data.min_price.toFixed(2))
						} else {
							$('.J_group_pro_price').text(data.min_price.toFixed(2) + '-' + data.max_price.toFixed(2)).data('price', data.min_price.toFixed(2) + '-' + data.max_price.toFixed(2))
						}

						// 按钮
						$('.pro-btn-container').addClass('hidden')
						$('.group-pro-btn-container').removeClass('hidden')
						var host = location.host;
						var wurl = 'http://m.9daye.com.cn/goods/detail?id=' + url('?id');
						if (host.search('test') != -1) {
							wurl = 'http://test.m.9daye.com.cn/goods/detail?id=' + url('?id')
						}
						var qrcode = new QRCode(document.getElementById("qr-code-container"), {
							text: wurl,
							width: 125,
							height: 125,
							colorDark : "#e11222",
							colorLight : "#ffffff",
							correctLevel : QRCode.CorrectLevel.H
						});
					}
				}
				if (window.CUSTOM_USER_LEVEL === 2 || window.CUSTOM_USER_LEVEL === 3) {
					if(data.gpubs_type == 2){
						if (data.expire_time > 0 && data.status == 1) {
							group.gSku = data.sku
							$('.group-pro-tip').removeClass('hidden')
							$('.group-end-time-box').removeClass('hidden')
							// 倒计时
							jdy.seckill.timerFun(data.expire_time, function(data) {
								$('.group-end-time-box .day-box').text(data.day)
								$('.group-end-time-box .hour-box').text(data.hour)
								$('.group-end-time-box .minu-box').text(data.minute)
								$('.group-end-time-box .sec-box').text(data.second)
							}, function() {
								location.reload()
							})

							if(data.gpubs_type == 1){
								$('.J_group_pro_num').text(data.min_quantity_per_group + '件起团')
								$('.group-pro-tip').html('自提拼购');
							}else if(data.gpubs_type == 2){
								$('.group-pro-tip').html('送货拼购');
								if(data.gpubs_rule_type == 1){
									$('.J_group_pro_num').text(data.min_member_per_group + '人起团')
								}else if(data.gpubs_rule_type == 2){
									$('.J_group_pro_num').text(data.min_quantity_per_group + '件起团')
								}else if(data.gpubs_rule_type == 3){
									$('.J_group_pro_num').text(data.min_member_per_group + '人，每人' + data.min_quanlity_per_member_of_group + '件起团')
								}
							}
							
							// 价格
							if (data.min_price === data.max_price) {
								$('.J_group_pro_price').text(data.min_price.toFixed(2)).data('price', data.min_price.toFixed(2))
							} else {
								$('.J_group_pro_price').text(data.min_price.toFixed(2) + '-' + data.max_price.toFixed(2)).data('price', data.min_price.toFixed(2) + '-' + data.max_price.toFixed(2))
							}

							// 按钮
							$('.pro-btn-container').addClass('hidden')
							$('.group-pro-btn-container').removeClass('hidden')
							var host = location.host;
							var wurl = 'http://m.9daye.com.cn/goods/detail?id=' + url('?id');
							if (host.search('test') != -1) {
								wurl = 'http://test.m.9daye.com.cn/goods/detail?id=' + url('?id')
							}
							var qrcode = new QRCode(document.getElementById("qr-code-container"), {
								text: wurl,
								width: 125,
								height: 125,
								colorDark : "#e11222",
								colorLight : "#ffffff",
								correctLevel : QRCode.CorrectLevel.H
							});
						}
					}
				}
			})
		},
		init: function() {
			if (window.CUSTOM_USER_LEVEL === 4) {
				this.getInfo({
					product_id: url('?id')
				})
			}
		}
	}
	group.getInfo()
	
});


/**
 * myProgress.js
 * Version: 1.0
 * Author: Mahuaide
 * Download:
 * You may use this script for free
 */
;
(function ($) {
    if (typeof($.fn.myProgress) != 'undefined') {
        return false;
    }
    $.fn.myProgress = function (options) {
        initOptions(options);
        $(this).each(function () {
            var this_ = $(this);
            var $percent = $(this).find("div.percent-show>span");
            var progress_in = $(this).find("div.progress-in");
            initCss(options, $(this));
            var t = setInterval(function () {
                $percent.html(parseInt(progress_in.width() / this_.width() * 100))
            }, options.speed / 100);
            progress_in.animate({
                width: options.percent + "%"
            }, options.speed, function () {
                clearInterval(t);
                t = null;
                $percent.html(options.percent);
                options.percent == 100 && progress_in.css("border-radius", 0);
            });
        });
        return $(this);
    }

    function initOptions(options) {
        (!options.hasOwnProperty("speed") || isNaN(options.speed)) && (options.speed = 1000);
        (!options.hasOwnProperty("percent") || isNaN(options.percent)) && (options.percent = 100);
        !options.hasOwnProperty("width") && (options.width = '180px');
        !options.hasOwnProperty("height") && (options.height = '20px');
        !options.hasOwnProperty("direction") && (options.direction = 'left');
        options.fontSize = Math.floor(parseInt(options.height) * 6 / 10) + "px";
        options.lineHeight = options.height;
    }

    function initCss(options, obj) {
        obj.css({
            "width": options.width,
            "height": options.height
        }).find("div.percent-show").css({
            "lineHeight": options.lineHeight,
            "fontSize": options.fontSize
        });
        if(options.direction =="right"){
            obj.find("div.progress-in").addClass("direction-right");
        }else{
            obj.find("div.progress-in").addClass("direction-left");
        }
    }
})(jQuery);

function invokeAddTeamBuying () {
    var links = window.location.href
    var INDEX = links.indexOf('?')
    var stead = links.substr(INDEX+1)
    var product_id = stead.slice(3)

    requestUrl('temp/groupbuy/is-groupbuy','GET',{
        product_id:product_id
    },function (data) {
        if(data.is_activity_product){
            requestUrl('temp/groupbuy/get-all-groupbuy-specific','GET',{
                groupbuy_id: data.groupbuy_specific[0].groupbuy.groupbuy_id
            },function (data_gbid) {
                $('#teamCount-data').html(data_gbid.groupbuy.sales)
                $('#currentBusiness-data').html(data_gbid.groupbuy.groupbuy_price)
            })


            $('.join-team-buying-list').find('ul').append(createListComponent(data.groupbuy_specific[0].groupbuy))
        } else {
            $('.apx-join-team-buying-box').hide()
        }
    })

    var _map = sessionStorage.getItem('_map')
    $('.teamFight-link a').attr('href','/temp/groupbuy#'+_map)
}

function createListComponent(data) {
    // sku 属性 id
    var skuids = []
    // sku 属性名称
    var skuNames = []
    // 表头字段
    var titles = [ '原价', '库存', '阶段一金额', '阶段二金额', '阶段三金额' ]
    var tpl = ''

    for (var i in data.attributes) {
        var item = data.attributes[i]
        skuids.push(i)

        for (var title in item) {
            skuNames.push(title)
        }
    }

    titles = skuNames.concat(titles)

    var colWidth = 100 / titles.length

    // tpl += '<div class="item-list-head"><span>阶段一件数：' + data.first_gradient_sales_goals + '</span><span>阶段二件数：' + data.second_gradient_sales_goals + '</span><span>阶段三件数：' + data.third_gradient_sales_goals + '</span></div>'

    tpl += '<li class="default-head">'
    for (var i = 0; i < titles.length; i += 1) {
        tpl += '<span style="width: ' + colWidth + '%">' + titles[i] + '</span>'
    }
    tpl += '</li>'


    for (var i in data.sku) {
        var skuItem = data.sku[i]
        var sku = i.split(';')

        tpl += '<li>'

        for (var j = 0; j < sku.length; j += 1) {
            var key = sku[j].split(':')[0]
            var value = sku[j].split(':')[1]
            var name = skuNames[skuids.indexOf(key)]

            tpl += '<span style="width: ' + colWidth + '%">' + data.attributes[key][name][value] + '</span>'
        }

        tpl += '<span class="color-primary" style="width: ' + colWidth + '%">￥' + skuItem.price + '</span>'
        tpl += '<span style="width: ' + colWidth + '%">' + skuItem.stock + '</span>'
        tpl += '<span style="width: ' + colWidth + '%">' + skuItem.first_gradient_price + '</span>'
        tpl += '<span style="width: ' + colWidth + '%">' + skuItem.second_gradient_price + '</span>'
        tpl += '<span style="width: ' + colWidth + '%">' + skuItem.third_gradient_price + '</span>'
        tpl += '</span>'

        tpl += '</li>'
    }


    return tpl
}

function touchRules (){
    $('.default-understand-more-rules').on('mouseover',function (e) {
        var width = $('body').width()
        if(width < 1700){
            $('.kui-chat-right-triangle').hide()
            $('.apx-join-team-buying .apx-join-team-buying-box .show-aside-rules').css({'marginTop':60,'marginLeft':967,'backgroundColor':'rgb(252, 241, 217)','borderColor':'rgb(221, 221, 221)'})
        } else {
            $('.apx-join-team-buying .apx-join-team-buying-box .show-aside-rules').css({'marginTop':0,'marginLeft':1210,'backgroundColor':'#fcf1d9','borderColor':'rgb(221, 221, 221)','borderColor':'#fcf1d9'})
            $('.kui-chat-right-triangle').show()
        }
        $('.show-aside-rules').show()
    }).on('mouseout',function (e) {
        e.preventDefault()
        $('.show-aside-rules').hide()
    })
}

function groupScale() {
    $('.apx-join-team-buying .default-teamFight span a').on('mouseover',function (e) {
        $(this).animate({'width':'158px'},300)
    })
    $('.apx-join-team-buying .default-teamFight span a').on('mouseout',function (e) {
        $(this).animate({'width':'148px'},300)
    })
}

function init() {
    // 了解规则
    touchRules('mousemove','mouseout')
    groupScale('mousemove','mouseout')

    var curDate = new Date()
    var startDate = new Date('2018/06/27 00:00:00')
    var endDate = new Date('2018/06/29 23:59:59')

    if (startDate <= curDate &&  curDate <= endDate) {
        // 百团大战列表
        invokeAddTeamBuying()
    } else {
        $('.apx-join-team-buying-box').hide()
    }
}

init()

