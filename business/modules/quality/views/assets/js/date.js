/*!
 * Datepicker for Bootstrap v1.6.4 (https://github.com/eternicode/bootstrap-datepicker)
 *
 * Copyright 2012 Stefan Petre
 * Improvements by Andrew Rowls
 * Licensed under the Apache License v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a,b){function c(){return new Date(Date.UTC.apply(Date,arguments))}function d(){var a=new Date;return c(a.getFullYear(),a.getMonth(),a.getDate())}function e(a,b){return a.getUTCFullYear()===b.getUTCFullYear()&&a.getUTCMonth()===b.getUTCMonth()&&a.getUTCDate()===b.getUTCDate()}function f(a){return function(){return this[a].apply(this,arguments)}}function g(a){return a&&!isNaN(a.getTime())}function h(b,c){function d(a,b){return b.toLowerCase()}var e,f=a(b).data(),g={},h=new RegExp("^"+c.toLowerCase()+"([A-Z])");c=new RegExp("^"+c.toLowerCase());for(var i in f)c.test(i)&&(e=i.replace(h,d),g[e]=f[i]);return g}function i(b){var c={};if(q[b]||(b=b.split("-")[0],q[b])){var d=q[b];return a.each(p,function(a,b){b in d&&(c[b]=d[b])}),c}}var j=function(){var b={get:function(a){return this.slice(a)[0]},contains:function(a){for(var b=a&&a.valueOf(),c=0,d=this.length;d>c;c++)if(this[c].valueOf()===b)return c;return-1},remove:function(a){this.splice(a,1)},replace:function(b){b&&(a.isArray(b)||(b=[b]),this.clear(),this.push.apply(this,b))},clear:function(){this.length=0},copy:function(){var a=new j;return a.replace(this),a}};return function(){var c=[];return c.push.apply(c,arguments),a.extend(c,b),c}}(),k=function(b,c){a(b).data("datepicker",this),this._process_options(c),this.dates=new j,this.viewDate=this.o.defaultViewDate,this.focusDate=null,this.element=a(b),this.isInput=this.element.is("input"),this.inputField=this.isInput?this.element:this.element.find("input"),this.component=this.element.hasClass("date")?this.element.find(".add-on, .input-group-addon, .btn"):!1,this.hasInput=this.component&&this.inputField.length,this.component&&0===this.component.length&&(this.component=!1),this.isInline=!this.component&&this.element.is("div"),this.picker=a(r.template),this._check_template(this.o.templates.leftArrow)&&this.picker.find(".prev").html(this.o.templates.leftArrow),this._check_template(this.o.templates.rightArrow)&&this.picker.find(".next").html(this.o.templates.rightArrow),this._buildEvents(),this._attachEvents(),this.isInline?this.picker.addClass("datepicker-inline").appendTo(this.element):this.picker.addClass("datepicker-dropdown dropdown-menu"),this.o.rtl&&this.picker.addClass("datepicker-rtl"),this.viewMode=this.o.startView,this.o.calendarWeeks&&this.picker.find("thead .datepicker-title, tfoot .today, tfoot .clear").attr("colspan",function(a,b){return parseInt(b)+1}),this._allow_update=!1,this.setStartDate(this._o.startDate),this.setEndDate(this._o.endDate),this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled),this.setDaysOfWeekHighlighted(this.o.daysOfWeekHighlighted),this.setDatesDisabled(this.o.datesDisabled),this.fillDow(),this.fillMonths(),this._allow_update=!0,this.update(),this.showMode(),this.isInline&&this.show()};k.prototype={constructor:k,_resolveViewName:function(a,c){return 0===a||"days"===a||"month"===a?0:1===a||"months"===a||"year"===a?1:2===a||"years"===a||"decade"===a?2:3===a||"decades"===a||"century"===a?3:4===a||"centuries"===a||"millennium"===a?4:c===b?!1:c},_check_template:function(c){try{if(c===b||""===c)return!1;if((c.match(/[<>]/g)||[]).length<=0)return!0;var d=a(c);return d.length>0}catch(e){return!1}},_process_options:function(b){this._o=a.extend({},this._o,b);var e=this.o=a.extend({},this._o),f=e.language;q[f]||(f=f.split("-")[0],q[f]||(f=o.language)),e.language=f,e.startView=this._resolveViewName(e.startView,0),e.minViewMode=this._resolveViewName(e.minViewMode,0),e.maxViewMode=this._resolveViewName(e.maxViewMode,4),e.startView=Math.min(e.startView,e.maxViewMode),e.startView=Math.max(e.startView,e.minViewMode),e.multidate!==!0&&(e.multidate=Number(e.multidate)||!1,e.multidate!==!1&&(e.multidate=Math.max(0,e.multidate))),e.multidateSeparator=String(e.multidateSeparator),e.weekStart%=7,e.weekEnd=(e.weekStart+6)%7;var g=r.parseFormat(e.format);e.startDate!==-(1/0)&&(e.startDate?e.startDate instanceof Date?e.startDate=this._local_to_utc(this._zero_time(e.startDate)):e.startDate=r.parseDate(e.startDate,g,e.language,e.assumeNearbyYear):e.startDate=-(1/0)),e.endDate!==1/0&&(e.endDate?e.endDate instanceof Date?e.endDate=this._local_to_utc(this._zero_time(e.endDate)):e.endDate=r.parseDate(e.endDate,g,e.language,e.assumeNearbyYear):e.endDate=1/0),e.daysOfWeekDisabled=e.daysOfWeekDisabled||[],a.isArray(e.daysOfWeekDisabled)||(e.daysOfWeekDisabled=e.daysOfWeekDisabled.split(/[,\s]*/)),e.daysOfWeekDisabled=a.map(e.daysOfWeekDisabled,function(a){return parseInt(a,10)}),e.daysOfWeekHighlighted=e.daysOfWeekHighlighted||[],a.isArray(e.daysOfWeekHighlighted)||(e.daysOfWeekHighlighted=e.daysOfWeekHighlighted.split(/[,\s]*/)),e.daysOfWeekHighlighted=a.map(e.daysOfWeekHighlighted,function(a){return parseInt(a,10)}),e.datesDisabled=e.datesDisabled||[],a.isArray(e.datesDisabled)||(e.datesDisabled=[e.datesDisabled]),e.datesDisabled=a.map(e.datesDisabled,function(a){return r.parseDate(a,g,e.language,e.assumeNearbyYear)});var h=String(e.orientation).toLowerCase().split(/\s+/g),i=e.orientation.toLowerCase();if(h=a.grep(h,function(a){return/^auto|left|right|top|bottom$/.test(a)}),e.orientation={x:"auto",y:"auto"},i&&"auto"!==i)if(1===h.length)switch(h[0]){case"top":case"bottom":e.orientation.y=h[0];break;case"left":case"right":e.orientation.x=h[0]}else i=a.grep(h,function(a){return/^left|right$/.test(a)}),e.orientation.x=i[0]||"auto",i=a.grep(h,function(a){return/^top|bottom$/.test(a)}),e.orientation.y=i[0]||"auto";else;if(e.defaultViewDate){var j=e.defaultViewDate.year||(new Date).getFullYear(),k=e.defaultViewDate.month||0,l=e.defaultViewDate.day||1;e.defaultViewDate=c(j,k,l)}else e.defaultViewDate=d()},_events:[],_secondaryEvents:[],_applyEvents:function(a){for(var c,d,e,f=0;f<a.length;f++)c=a[f][0],2===a[f].length?(d=b,e=a[f][1]):3===a[f].length&&(d=a[f][1],e=a[f][2]),c.on(e,d)},_unapplyEvents:function(a){for(var c,d,e,f=0;f<a.length;f++)c=a[f][0],2===a[f].length?(e=b,d=a[f][1]):3===a[f].length&&(e=a[f][1],d=a[f][2]),c.off(d,e)},_buildEvents:function(){var b={keyup:a.proxy(function(b){-1===a.inArray(b.keyCode,[27,37,39,38,40,32,13,9])&&this.update()},this),keydown:a.proxy(this.keydown,this),paste:a.proxy(this.paste,this)};this.o.showOnFocus===!0&&(b.focus=a.proxy(this.show,this)),this.isInput?this._events=[[this.element,b]]:this.component&&this.hasInput?this._events=[[this.inputField,b],[this.component,{click:a.proxy(this.show,this)}]]:this._events=[[this.element,{click:a.proxy(this.show,this),keydown:a.proxy(this.keydown,this)}]],this._events.push([this.element,"*",{blur:a.proxy(function(a){this._focused_from=a.target},this)}],[this.element,{blur:a.proxy(function(a){this._focused_from=a.target},this)}]),this.o.immediateUpdates&&this._events.push([this.element,{"changeYear changeMonth":a.proxy(function(a){this.update(a.date)},this)}]),this._secondaryEvents=[[this.picker,{click:a.proxy(this.click,this)}],[a(window),{resize:a.proxy(this.place,this)}],[a(document),{mousedown:a.proxy(function(a){this.element.is(a.target)||this.element.find(a.target).length||this.picker.is(a.target)||this.picker.find(a.target).length||this.isInline||this.hide()},this)}]]},_attachEvents:function(){this._detachEvents(),this._applyEvents(this._events)},_detachEvents:function(){this._unapplyEvents(this._events)},_attachSecondaryEvents:function(){this._detachSecondaryEvents(),this._applyEvents(this._secondaryEvents)},_detachSecondaryEvents:function(){this._unapplyEvents(this._secondaryEvents)},_trigger:function(b,c){var d=c||this.dates.get(-1),e=this._utc_to_local(d);this.element.trigger({type:b,date:e,dates:a.map(this.dates,this._utc_to_local),format:a.proxy(function(a,b){0===arguments.length?(a=this.dates.length-1,b=this.o.format):"string"==typeof a&&(b=a,a=this.dates.length-1),b=b||this.o.format;var c=this.dates.get(a);return r.formatDate(c,b,this.o.language)},this)})},show:function(){return this.inputField.prop("disabled")||this.inputField.prop("readonly")&&this.o.enableOnReadonly===!1?void 0:(this.isInline||this.picker.appendTo(this.o.container),this.place(),this.picker.show(),this._attachSecondaryEvents(),this._trigger("show"),(window.navigator.msMaxTouchPoints||"ontouchstart"in document)&&this.o.disableTouchKeyboard&&a(this.element).blur(),this)},hide:function(){return this.isInline||!this.picker.is(":visible")?this:(this.focusDate=null,this.picker.hide().detach(),this._detachSecondaryEvents(),this.viewMode=this.o.startView,this.showMode(),this.o.forceParse&&this.inputField.val()&&this.setValue(),this._trigger("hide"),this)},destroy:function(){return this.hide(),this._detachEvents(),this._detachSecondaryEvents(),this.picker.remove(),delete this.element.data().datepicker,this.isInput||delete this.element.data().date,this},paste:function(b){var c;if(b.originalEvent.clipboardData&&b.originalEvent.clipboardData.types&&-1!==a.inArray("text/plain",b.originalEvent.clipboardData.types))c=b.originalEvent.clipboardData.getData("text/plain");else{if(!window.clipboardData)return;c=window.clipboardData.getData("Text")}this.setDate(c),this.update(),b.preventDefault()},_utc_to_local:function(a){return a&&new Date(a.getTime()+6e4*a.getTimezoneOffset())},_local_to_utc:function(a){return a&&new Date(a.getTime()-6e4*a.getTimezoneOffset())},_zero_time:function(a){return a&&new Date(a.getFullYear(),a.getMonth(),a.getDate())},_zero_utc_time:function(a){return a&&new Date(Date.UTC(a.getUTCFullYear(),a.getUTCMonth(),a.getUTCDate()))},getDates:function(){return a.map(this.dates,this._utc_to_local)},getUTCDates:function(){return a.map(this.dates,function(a){return new Date(a)})},getDate:function(){return this._utc_to_local(this.getUTCDate())},getUTCDate:function(){var a=this.dates.get(-1);return"undefined"!=typeof a?new Date(a):null},clearDates:function(){this.inputField&&this.inputField.val(""),this.update(),this._trigger("changeDate"),this.o.autoclose&&this.hide()},setDates:function(){var b=a.isArray(arguments[0])?arguments[0]:arguments;return this.update.apply(this,b),this._trigger("changeDate"),this.setValue(),this},setUTCDates:function(){var b=a.isArray(arguments[0])?arguments[0]:arguments;return this.update.apply(this,a.map(b,this._utc_to_local)),this._trigger("changeDate"),this.setValue(),this},setDate:f("setDates"),setUTCDate:f("setUTCDates"),remove:f("destroy"),setValue:function(){var a=this.getFormattedDate();return this.inputField.val(a),this},getFormattedDate:function(c){c===b&&(c=this.o.format);var d=this.o.language;return a.map(this.dates,function(a){return r.formatDate(a,c,d)}).join(this.o.multidateSeparator)},getStartDate:function(){return this.o.startDate},setStartDate:function(a){return this._process_options({startDate:a}),this.update(),this.updateNavArrows(),this},getEndDate:function(){return this.o.endDate},setEndDate:function(a){return this._process_options({endDate:a}),this.update(),this.updateNavArrows(),this},setDaysOfWeekDisabled:function(a){return this._process_options({daysOfWeekDisabled:a}),this.update(),this.updateNavArrows(),this},setDaysOfWeekHighlighted:function(a){return this._process_options({daysOfWeekHighlighted:a}),this.update(),this},setDatesDisabled:function(a){this._process_options({datesDisabled:a}),this.update(),this.updateNavArrows()},place:function(){if(this.isInline)return this;var b=this.picker.outerWidth(),c=this.picker.outerHeight(),d=10,e=a(this.o.container),f=e.width(),g="body"===this.o.container?a(document).scrollTop():e.scrollTop(),h=e.offset(),i=[];this.element.parents().each(function(){var b=a(this).css("z-index");"auto"!==b&&0!==b&&i.push(parseInt(b))});var j=Math.max.apply(Math,i)+this.o.zIndexOffset,k=this.component?this.component.parent().offset():this.element.offset(),l=this.component?this.component.outerHeight(!0):this.element.outerHeight(!1),m=this.component?this.component.outerWidth(!0):this.element.outerWidth(!1),n=k.left-h.left,o=k.top-h.top;"body"!==this.o.container&&(o+=g),this.picker.removeClass("datepicker-orient-top datepicker-orient-bottom datepicker-orient-right datepicker-orient-left"),"auto"!==this.o.orientation.x?(this.picker.addClass("datepicker-orient-"+this.o.orientation.x),"right"===this.o.orientation.x&&(n-=b-m)):k.left<0?(this.picker.addClass("datepicker-orient-left"),n-=k.left-d):n+b>f?(this.picker.addClass("datepicker-orient-right"),n+=m-b):this.picker.addClass("datepicker-orient-left");var p,q=this.o.orientation.y;if("auto"===q&&(p=-g+o-c,q=0>p?"bottom":"top"),this.picker.addClass("datepicker-orient-"+q),"top"===q?o-=c+parseInt(this.picker.css("padding-top")):o+=l,this.o.rtl){var r=f-(n+m);this.picker.css({top:o,right:r,zIndex:j})}else this.picker.css({top:o,left:n,zIndex:j});return this},_allow_update:!0,update:function(){if(!this._allow_update)return this;var b=this.dates.copy(),c=[],d=!1;return arguments.length?(a.each(arguments,a.proxy(function(a,b){b instanceof Date&&(b=this._local_to_utc(b)),c.push(b)},this)),d=!0):(c=this.isInput?this.element.val():this.element.data("date")||this.inputField.val(),c=c&&this.o.multidate?c.split(this.o.multidateSeparator):[c],delete this.element.data().date),c=a.map(c,a.proxy(function(a){return r.parseDate(a,this.o.format,this.o.language,this.o.assumeNearbyYear)},this)),c=a.grep(c,a.proxy(function(a){return!this.dateWithinRange(a)||!a},this),!0),this.dates.replace(c),this.dates.length?this.viewDate=new Date(this.dates.get(-1)):this.viewDate<this.o.startDate?this.viewDate=new Date(this.o.startDate):this.viewDate>this.o.endDate?this.viewDate=new Date(this.o.endDate):this.viewDate=this.o.defaultViewDate,d?this.setValue():c.length&&String(b)!==String(this.dates)&&this._trigger("changeDate"),!this.dates.length&&b.length&&this._trigger("clearDate"),this.fill(),this.element.change(),this},fillDow:function(){var b=this.o.weekStart,c="<tr>";for(this.o.calendarWeeks&&(this.picker.find(".datepicker-days .datepicker-switch").attr("colspan",function(a,b){return parseInt(b)+1}),c+='<th class="cw">&#160;</th>');b<this.o.weekStart+7;)c+='<th class="dow',a.inArray(b,this.o.daysOfWeekDisabled)>-1&&(c+=" disabled"),c+='">'+q[this.o.language].daysMin[b++%7]+"</th>";c+="</tr>",this.picker.find(".datepicker-days thead").append(c)},fillMonths:function(){for(var a=this._utc_to_local(this.viewDate),b="",c=0;12>c;){var d=a&&a.getMonth()===c?" focused":"";b+='<span class="month'+d+'">'+q[this.o.language].monthsShort[c++]+"</span>"}this.picker.find(".datepicker-months td").html(b)},setRange:function(b){b&&b.length?this.range=a.map(b,function(a){return a.valueOf()}):delete this.range,this.fill()},getClassNames:function(b){var c=[],d=this.viewDate.getUTCFullYear(),e=this.viewDate.getUTCMonth(),f=new Date;return b.getUTCFullYear()<d||b.getUTCFullYear()===d&&b.getUTCMonth()<e?c.push("old"):(b.getUTCFullYear()>d||b.getUTCFullYear()===d&&b.getUTCMonth()>e)&&c.push("new"),this.focusDate&&b.valueOf()===this.focusDate.valueOf()&&c.push("focused"),this.o.todayHighlight&&b.getUTCFullYear()===f.getFullYear()&&b.getUTCMonth()===f.getMonth()&&b.getUTCDate()===f.getDate()&&c.push("today"),-1!==this.dates.contains(b)&&c.push("active"),this.dateWithinRange(b)||c.push("disabled"),this.dateIsDisabled(b)&&c.push("disabled","disabled-date"),-1!==a.inArray(b.getUTCDay(),this.o.daysOfWeekHighlighted)&&c.push("highlighted"),this.range&&(b>this.range[0]&&b<this.range[this.range.length-1]&&c.push("range"),-1!==a.inArray(b.valueOf(),this.range)&&c.push("selected"),b.valueOf()===this.range[0]&&c.push("range-start"),b.valueOf()===this.range[this.range.length-1]&&c.push("range-end")),c},_fill_yearsView:function(c,d,e,f,g,h,i,j){var k,l,m,n,o,p,q,r,s,t,u;for(k="",l=this.picker.find(c),m=parseInt(g/e,10)*e,o=parseInt(h/f,10)*f,p=parseInt(i/f,10)*f,n=a.map(this.dates,function(a){return parseInt(a.getUTCFullYear()/f,10)*f}),l.find(".datepicker-switch").text(m+"-"+(m+9*f)),q=m-f,r=-1;11>r;r+=1)s=[d],t=null,-1===r?s.push("old"):10===r&&s.push("new"),-1!==a.inArray(q,n)&&s.push("active"),(o>q||q>p)&&s.push("disabled"),q===this.viewDate.getFullYear()&&s.push("focused"),j!==a.noop&&(u=j(new Date(q,0,1)),u===b?u={}:"boolean"==typeof u?u={enabled:u}:"string"==typeof u&&(u={classes:u}),u.enabled===!1&&s.push("disabled"),u.classes&&(s=s.concat(u.classes.split(/\s+/))),u.tooltip&&(t=u.tooltip)),k+='<span class="'+s.join(" ")+'"'+(t?' title="'+t+'"':"")+">"+q+"</span>",q+=f;l.find("td").html(k)},fill:function(){var d,e,f=new Date(this.viewDate),g=f.getUTCFullYear(),h=f.getUTCMonth(),i=this.o.startDate!==-(1/0)?this.o.startDate.getUTCFullYear():-(1/0),j=this.o.startDate!==-(1/0)?this.o.startDate.getUTCMonth():-(1/0),k=this.o.endDate!==1/0?this.o.endDate.getUTCFullYear():1/0,l=this.o.endDate!==1/0?this.o.endDate.getUTCMonth():1/0,m=q[this.o.language].today||q.en.today||"",n=q[this.o.language].clear||q.en.clear||"",o=q[this.o.language].titleFormat||q.en.titleFormat;if(!isNaN(g)&&!isNaN(h)){this.picker.find(".datepicker-days .datepicker-switch").text(r.formatDate(f,o,this.o.language)),this.picker.find("tfoot .today").text(m).toggle(this.o.todayBtn!==!1),this.picker.find("tfoot .clear").text(n).toggle(this.o.clearBtn!==!1),this.picker.find("thead .datepicker-title").text(this.o.title).toggle(""!==this.o.title),this.updateNavArrows(),this.fillMonths();var p=c(g,h-1,28),s=r.getDaysInMonth(p.getUTCFullYear(),p.getUTCMonth());p.setUTCDate(s),p.setUTCDate(s-(p.getUTCDay()-this.o.weekStart+7)%7);var t=new Date(p);p.getUTCFullYear()<100&&t.setUTCFullYear(p.getUTCFullYear()),t.setUTCDate(t.getUTCDate()+42),t=t.valueOf();for(var u,v=[];p.valueOf()<t;){if(p.getUTCDay()===this.o.weekStart&&(v.push("<tr>"),this.o.calendarWeeks)){var w=new Date(+p+(this.o.weekStart-p.getUTCDay()-7)%7*864e5),x=new Date(Number(w)+(11-w.getUTCDay())%7*864e5),y=new Date(Number(y=c(x.getUTCFullYear(),0,1))+(11-y.getUTCDay())%7*864e5),z=(x-y)/864e5/7+1;v.push('<td class="cw">'+z+"</td>")}u=this.getClassNames(p),u.push("day"),this.o.beforeShowDay!==a.noop&&(e=this.o.beforeShowDay(this._utc_to_local(p)),e===b?e={}:"boolean"==typeof e?e={enabled:e}:"string"==typeof e&&(e={classes:e}),e.enabled===!1&&u.push("disabled"),e.classes&&(u=u.concat(e.classes.split(/\s+/))),e.tooltip&&(d=e.tooltip)),u=a.isFunction(a.uniqueSort)?a.uniqueSort(u):a.unique(u),v.push('<td class="'+u.join(" ")+'"'+(d?' title="'+d+'"':"")+">"+p.getUTCDate()+"</td>"),d=null,p.getUTCDay()===this.o.weekEnd&&v.push("</tr>"),p.setUTCDate(p.getUTCDate()+1)}this.picker.find(".datepicker-days tbody").empty().append(v.join(""));var A=q[this.o.language].monthsTitle||q.en.monthsTitle||"Months",B=this.picker.find(".datepicker-months").find(".datepicker-switch").text(this.o.maxViewMode<2?A:g).end().find("span").removeClass("active");if(a.each(this.dates,function(a,b){b.getUTCFullYear()===g&&B.eq(b.getUTCMonth()).addClass("active")}),(i>g||g>k)&&B.addClass("disabled"),g===i&&B.slice(0,j).addClass("disabled"),g===k&&B.slice(l+1).addClass("disabled"),this.o.beforeShowMonth!==a.noop){var C=this;a.each(B,function(c,d){var e=new Date(g,c,1),f=C.o.beforeShowMonth(e);f===b?f={}:"boolean"==typeof f?f={enabled:f}:"string"==typeof f&&(f={classes:f}),f.enabled!==!1||a(d).hasClass("disabled")||a(d).addClass("disabled"),f.classes&&a(d).addClass(f.classes),f.tooltip&&a(d).prop("title",f.tooltip)})}this._fill_yearsView(".datepicker-years","year",10,1,g,i,k,this.o.beforeShowYear),this._fill_yearsView(".datepicker-decades","decade",100,10,g,i,k,this.o.beforeShowDecade),this._fill_yearsView(".datepicker-centuries","century",1e3,100,g,i,k,this.o.beforeShowCentury)}},updateNavArrows:function(){if(this._allow_update){var a=new Date(this.viewDate),b=a.getUTCFullYear(),c=a.getUTCMonth();switch(this.viewMode){case 0:this.o.startDate!==-(1/0)&&b<=this.o.startDate.getUTCFullYear()&&c<=this.o.startDate.getUTCMonth()?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),this.o.endDate!==1/0&&b>=this.o.endDate.getUTCFullYear()&&c>=this.o.endDate.getUTCMonth()?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"});break;case 1:case 2:case 3:case 4:this.o.startDate!==-(1/0)&&b<=this.o.startDate.getUTCFullYear()||this.o.maxViewMode<2?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),this.o.endDate!==1/0&&b>=this.o.endDate.getUTCFullYear()||this.o.maxViewMode<2?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"})}}},click:function(b){b.preventDefault(),b.stopPropagation();var e,f,g,h,i,j,k;e=a(b.target),e.hasClass("datepicker-switch")&&this.showMode(1);var l=e.closest(".prev, .next");l.length>0&&(f=r.modes[this.viewMode].navStep*(l.hasClass("prev")?-1:1),0===this.viewMode?(this.viewDate=this.moveMonth(this.viewDate,f),this._trigger("changeMonth",this.viewDate)):(this.viewDate=this.moveYear(this.viewDate,f),1===this.viewMode&&this._trigger("changeYear",this.viewDate)),this.fill()),e.hasClass("today")&&!e.hasClass("day")&&(this.showMode(-2),this._setDate(d(),"linked"===this.o.todayBtn?null:"view")),e.hasClass("clear")&&this.clearDates(),e.hasClass("disabled")||(e.hasClass("day")&&(g=parseInt(e.text(),10)||1,h=this.viewDate.getUTCFullYear(),i=this.viewDate.getUTCMonth(),e.hasClass("old")&&(0===i?(i=11,h-=1,j=!0,k=!0):(i-=1,j=!0)),e.hasClass("new")&&(11===i?(i=0,h+=1,j=!0,k=!0):(i+=1,j=!0)),this._setDate(c(h,i,g)),k&&this._trigger("changeYear",this.viewDate),j&&this._trigger("changeMonth",this.viewDate)),e.hasClass("month")&&(this.viewDate.setUTCDate(1),g=1,i=e.parent().find("span").index(e),h=this.viewDate.getUTCFullYear(),this.viewDate.setUTCMonth(i),this._trigger("changeMonth",this.viewDate),1===this.o.minViewMode?(this._setDate(c(h,i,g)),this.showMode()):this.showMode(-1),this.fill()),(e.hasClass("year")||e.hasClass("decade")||e.hasClass("century"))&&(this.viewDate.setUTCDate(1),g=1,i=0,h=parseInt(e.text(),10)||0,this.viewDate.setUTCFullYear(h),e.hasClass("year")&&(this._trigger("changeYear",this.viewDate),2===this.o.minViewMode&&this._setDate(c(h,i,g))),e.hasClass("decade")&&(this._trigger("changeDecade",this.viewDate),3===this.o.minViewMode&&this._setDate(c(h,i,g))),e.hasClass("century")&&(this._trigger("changeCentury",this.viewDate),4===this.o.minViewMode&&this._setDate(c(h,i,g))),this.showMode(-1),this.fill())),this.picker.is(":visible")&&this._focused_from&&a(this._focused_from).focus(),delete this._focused_from},_toggle_multidate:function(a){var b=this.dates.contains(a);if(a||this.dates.clear(),-1!==b?(this.o.multidate===!0||this.o.multidate>1||this.o.toggleActive)&&this.dates.remove(b):this.o.multidate===!1?(this.dates.clear(),this.dates.push(a)):this.dates.push(a),"number"==typeof this.o.multidate)for(;this.dates.length>this.o.multidate;)this.dates.remove(0)},_setDate:function(a,b){b&&"date"!==b||this._toggle_multidate(a&&new Date(a)),b&&"view"!==b||(this.viewDate=a&&new Date(a)),this.fill(),this.setValue(),b&&"view"===b||this._trigger("changeDate"),this.inputField&&this.inputField.change(),!this.o.autoclose||b&&"date"!==b||this.hide()},moveDay:function(a,b){var c=new Date(a);return c.setUTCDate(a.getUTCDate()+b),c},moveWeek:function(a,b){return this.moveDay(a,7*b)},moveMonth:function(a,b){if(!g(a))return this.o.defaultViewDate;if(!b)return a;var c,d,e=new Date(a.valueOf()),f=e.getUTCDate(),h=e.getUTCMonth(),i=Math.abs(b);if(b=b>0?1:-1,1===i)d=-1===b?function(){return e.getUTCMonth()===h}:function(){return e.getUTCMonth()!==c},c=h+b,e.setUTCMonth(c),(0>c||c>11)&&(c=(c+12)%12);else{for(var j=0;i>j;j++)e=this.moveMonth(e,b);c=e.getUTCMonth(),e.setUTCDate(f),d=function(){return c!==e.getUTCMonth()}}for(;d();)e.setUTCDate(--f),e.setUTCMonth(c);return e},moveYear:function(a,b){return this.moveMonth(a,12*b)},moveAvailableDate:function(a,b,c){do{if(a=this[c](a,b),!this.dateWithinRange(a))return!1;c="moveDay"}while(this.dateIsDisabled(a));return a},weekOfDateIsDisabled:function(b){return-1!==a.inArray(b.getUTCDay(),this.o.daysOfWeekDisabled)},dateIsDisabled:function(b){return this.weekOfDateIsDisabled(b)||a.grep(this.o.datesDisabled,function(a){return e(b,a)}).length>0},dateWithinRange:function(a){return a>=this.o.startDate&&a<=this.o.endDate},keydown:function(a){if(!this.picker.is(":visible"))return void((40===a.keyCode||27===a.keyCode)&&(this.show(),a.stopPropagation()));var b,c,d=!1,e=this.focusDate||this.viewDate;switch(a.keyCode){case 27:this.focusDate?(this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill()):this.hide(),a.preventDefault(),a.stopPropagation();break;case 37:case 38:case 39:case 40:if(!this.o.keyboardNavigation||7===this.o.daysOfWeekDisabled.length)break;b=37===a.keyCode||38===a.keyCode?-1:1,0===this.viewMode?a.ctrlKey?(c=this.moveAvailableDate(e,b,"moveYear"),c&&this._trigger("changeYear",this.viewDate)):a.shiftKey?(c=this.moveAvailableDate(e,b,"moveMonth"),c&&this._trigger("changeMonth",this.viewDate)):37===a.keyCode||39===a.keyCode?c=this.moveAvailableDate(e,b,"moveDay"):this.weekOfDateIsDisabled(e)||(c=this.moveAvailableDate(e,b,"moveWeek")):1===this.viewMode?((38===a.keyCode||40===a.keyCode)&&(b=4*b),c=this.moveAvailableDate(e,b,"moveMonth")):2===this.viewMode&&((38===a.keyCode||40===a.keyCode)&&(b=4*b),c=this.moveAvailableDate(e,b,"moveYear")),c&&(this.focusDate=this.viewDate=c,this.setValue(),this.fill(),a.preventDefault());break;case 13:if(!this.o.forceParse)break;e=this.focusDate||this.dates.get(-1)||this.viewDate,this.o.keyboardNavigation&&(this._toggle_multidate(e),d=!0),this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.setValue(),this.fill(),this.picker.is(":visible")&&(a.preventDefault(),a.stopPropagation(),this.o.autoclose&&this.hide());break;case 9:this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill(),this.hide()}d&&(this.dates.length?this._trigger("changeDate"):this._trigger("clearDate"),this.inputField&&this.inputField.change())},showMode:function(a){a&&(this.viewMode=Math.max(this.o.minViewMode,Math.min(this.o.maxViewMode,this.viewMode+a))),this.picker.children("div").hide().filter(".datepicker-"+r.modes[this.viewMode].clsName).show(),this.updateNavArrows()}};var l=function(b,c){a(b).data("datepicker",this),this.element=a(b),this.inputs=a.map(c.inputs,function(a){return a.jquery?a[0]:a}),delete c.inputs,n.call(a(this.inputs),c).on("changeDate",a.proxy(this.dateUpdated,this)),this.pickers=a.map(this.inputs,function(b){return a(b).data("datepicker")}),this.updateDates()};l.prototype={updateDates:function(){this.dates=a.map(this.pickers,function(a){return a.getUTCDate()}),this.updateRanges()},updateRanges:function(){var b=a.map(this.dates,function(a){return a.valueOf()});a.each(this.pickers,function(a,c){c.setRange(b)})},dateUpdated:function(b){if(!this.updating){this.updating=!0;var c=a(b.target).data("datepicker");if("undefined"!=typeof c){var d=c.getUTCDate(),e=a.inArray(b.target,this.inputs),f=e-1,g=e+1,h=this.inputs.length;if(-1!==e){if(a.each(this.pickers,function(a,b){b.getUTCDate()||b.setUTCDate(d)}),d<this.dates[f])for(;f>=0&&d<this.dates[f];)this.pickers[f--].setUTCDate(d);else if(d>this.dates[g])for(;h>g&&d>this.dates[g];)this.pickers[g++].setUTCDate(d);this.updateDates(),delete this.updating}}}},remove:function(){a.map(this.pickers,function(a){a.remove()}),delete this.element.data().datepicker}};var m=a.fn.datepicker,n=function(c){var d=Array.apply(null,arguments);d.shift();var e;if(this.each(function(){var b=a(this),f=b.data("datepicker"),g="object"==typeof c&&c;if(!f){var j=h(this,"date"),m=a.extend({},o,j,g),n=i(m.language),p=a.extend({},o,n,j,g);b.hasClass("input-daterange")||p.inputs?(a.extend(p,{inputs:p.inputs||b.find("input").toArray()}),f=new l(this,p)):f=new k(this,p),b.data("datepicker",f)}"string"==typeof c&&"function"==typeof f[c]&&(e=f[c].apply(f,d))}),e===b||e instanceof k||e instanceof l)return this;if(this.length>1)throw new Error("Using only allowed for the collection of a single element ("+c+" function)");return e};a.fn.datepicker=n;var o=a.fn.datepicker.defaults={assumeNearbyYear:!1,autoclose:!1,beforeShowDay:a.noop,beforeShowMonth:a.noop,beforeShowYear:a.noop,beforeShowDecade:a.noop,beforeShowCentury:a.noop,calendarWeeks:!1,clearBtn:!1,toggleActive:!1,daysOfWeekDisabled:[],daysOfWeekHighlighted:[],datesDisabled:[],endDate:1/0,forceParse:!0,format:"mm/dd/yyyy",keyboardNavigation:!0,language:"en",minViewMode:0,maxViewMode:4,multidate:!1,multidateSeparator:",",orientation:"auto",rtl:!1,startDate:-(1/0),startView:0,todayBtn:!1,todayHighlight:!1,weekStart:0,disableTouchKeyboard:!1,enableOnReadonly:!0,showOnFocus:!0,zIndexOffset:10,container:"body",immediateUpdates:!1,title:"",templates:{leftArrow:"&laquo;",rightArrow:"&raquo;"}},p=a.fn.datepicker.locale_opts=["format","rtl","weekStart"];a.fn.datepicker.Constructor=k;var q=a.fn.datepicker.dates={en:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],daysMin:["Su","Mo","Tu","We","Th","Fr","Sa"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],today:"Today",clear:"Clear",titleFormat:"MM yyyy"}},r={modes:[{clsName:"days",navFnc:"Month",navStep:1},{clsName:"months",navFnc:"FullYear",navStep:1},{clsName:"years",navFnc:"FullYear",navStep:10},{clsName:"decades",navFnc:"FullDecade",navStep:100},{clsName:"centuries",navFnc:"FullCentury",navStep:1e3}],isLeapYear:function(a){return a%4===0&&a%100!==0||a%400===0},getDaysInMonth:function(a,b){return[31,r.isLeapYear(a)?29:28,31,30,31,30,31,31,30,31,30,31][b]},validParts:/dd?|DD?|mm?|MM?|yy(?:yy)?/g,nonpunctuation:/[^ -\/:-@\u5e74\u6708\u65e5\[-`{-~\t\n\r]+/g,parseFormat:function(a){if("function"==typeof a.toValue&&"function"==typeof a.toDisplay)return a;var b=a.replace(this.validParts,"\x00").split("\x00"),c=a.match(this.validParts);if(!b||!b.length||!c||0===c.length)throw new Error("Invalid date format.");return{separators:b,parts:c}},parseDate:function(e,f,g,h){function i(a,b){return b===!0&&(b=10),100>a&&(a+=2e3,a>(new Date).getFullYear()+b&&(a-=100)),a}function j(){var a=this.slice(0,s[n].length),b=s[n].slice(0,a.length);return a.toLowerCase()===b.toLowerCase()}if(!e)return b;if(e instanceof Date)return e;if("string"==typeof f&&(f=r.parseFormat(f)),f.toValue)return f.toValue(e,f,g);var l,m,n,o,p=/([\-+]\d+)([dmwy])/,s=e.match(/([\-+]\d+)([dmwy])/g),t={d:"moveDay",m:"moveMonth",w:"moveWeek",y:"moveYear"},u={yesterday:"-1d",today:"+0d",tomorrow:"+1d"};if(/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(e)){for(e=new Date,n=0;n<s.length;n++)l=p.exec(s[n]),m=parseInt(l[1]),o=t[l[2]],e=k.prototype[o](e,m);return c(e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate())}if("undefined"!=typeof u[e]&&(e=u[e],s=e.match(/([\-+]\d+)([dmwy])/g),/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(e))){for(e=new Date,n=0;n<s.length;n++)l=p.exec(s[n]),m=parseInt(l[1]),o=t[l[2]],e=k.prototype[o](e,m);return c(e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate())}s=e&&e.match(this.nonpunctuation)||[],e=new Date;var v,w,x={},y=["yyyy","yy","M","MM","m","mm","d","dd"],z={yyyy:function(a,b){return a.setUTCFullYear(h?i(b,h):b)},yy:function(a,b){return a.setUTCFullYear(h?i(b,h):b)},m:function(a,b){if(isNaN(a))return a;for(b-=1;0>b;)b+=12;for(b%=12,a.setUTCMonth(b);a.getUTCMonth()!==b;)a.setUTCDate(a.getUTCDate()-1);return a},d:function(a,b){return a.setUTCDate(b)}};z.M=z.MM=z.mm=z.m,z.dd=z.d,e=d();var A=f.parts.slice();if(s.length!==A.length&&(A=a(A).filter(function(b,c){return-1!==a.inArray(c,y)}).toArray()),s.length===A.length){var B;for(n=0,B=A.length;B>n;n++){if(v=parseInt(s[n],10),l=A[n],isNaN(v))switch(l){case"MM":w=a(q[g].months).filter(j),v=a.inArray(w[0],q[g].months)+1;break;case"M":w=a(q[g].monthsShort).filter(j),v=a.inArray(w[0],q[g].monthsShort)+1}x[l]=v}var C,D;for(n=0;n<y.length;n++)D=y[n],D in x&&!isNaN(x[D])&&(C=new Date(e),z[D](C,x[D]),isNaN(C)||(e=C))}return e},formatDate:function(b,c,d){if(!b)return"";if("string"==typeof c&&(c=r.parseFormat(c)),
        c.toDisplay)return c.toDisplay(b,c,d);var e={d:b.getUTCDate(),D:q[d].daysShort[b.getUTCDay()],DD:q[d].days[b.getUTCDay()],m:b.getUTCMonth()+1,M:q[d].monthsShort[b.getUTCMonth()],MM:q[d].months[b.getUTCMonth()],yy:b.getUTCFullYear().toString().substring(2),yyyy:b.getUTCFullYear()};e.dd=(e.d<10?"0":"")+e.d,e.mm=(e.m<10?"0":"")+e.m,b=[];for(var f=a.extend([],c.separators),g=0,h=c.parts.length;h>=g;g++)f.length&&b.push(f.shift()),b.push(e[c.parts[g]]);return b.join("")},headTemplate:'<thead><tr><th colspan="7" class="datepicker-title"></th></tr><tr><th class="prev">&laquo;</th><th colspan="5" class="datepicker-switch"></th><th class="next">&raquo;</th></tr></thead>',contTemplate:'<tbody><tr><td colspan="7"></td></tr></tbody>',footTemplate:'<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'};r.template='<div class="datepicker"><div class="datepicker-days"><table class="table-condensed">'+r.headTemplate+"<tbody></tbody>"+r.footTemplate+'</table></div><div class="datepicker-months"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-years"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-decades"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-centuries"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+"</table></div></div>",a.fn.datepicker.DPGlobal=r,a.fn.datepicker.noConflict=function(){return a.fn.datepicker=m,this},a.fn.datepicker.version="1.6.4",a(document).on("focus.datepicker.data-api click.datepicker.data-api",'[data-provide="datepicker"]',function(b){var c=a(this);c.data("datepicker")||(b.preventDefault(),n.call(c,"show"))}),a(function(){n.call(a('[data-provide="datepicker-inline"]'))})});
//以上是时间选择插件

//以下是滚动插件
/*! iScroll v5.2.0-snapshot ~ (c) 2008-2017 Matteo Spinelli ~ http://cubiq.org/license */
(function (window, document, Math) {
    var rAF = window.requestAnimationFrame  ||
        window.webkitRequestAnimationFrame  ||
        window.mozRequestAnimationFrame     ||
        window.oRequestAnimationFrame       ||
        window.msRequestAnimationFrame      ||
        function (callback) { window.setTimeout(callback, 1000 / 60); };

    var utils = (function () {
        var me = {};

        var _elementStyle = document.createElement('div').style;
        var _vendor = (function () {
            var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
                transform,
                i = 0,
                l = vendors.length;

            for ( ; i < l; i++ ) {
                transform = vendors[i] + 'ransform';
                if ( transform in _elementStyle ) return vendors[i].substr(0, vendors[i].length-1);
            }

            return false;
        })();

        function _prefixStyle (style) {
            if ( _vendor === false ) return false;
            if ( _vendor === '' ) return style;
            return _vendor + style.charAt(0).toUpperCase() + style.substr(1);
        }

        me.getTime = Date.now || function getTime () { return new Date().getTime(); };

        me.extend = function (target, obj) {
            for ( var i in obj ) {
                target[i] = obj[i];
            }
        };

        me.addEvent = function (el, type, fn, capture) {
            el.addEventListener(type, fn, !!capture);
        };

        me.removeEvent = function (el, type, fn, capture) {
            el.removeEventListener(type, fn, !!capture);
        };

        me.prefixPointerEvent = function (pointerEvent) {
            return window.MSPointerEvent ?
                'MSPointer' + pointerEvent.charAt(7).toUpperCase() + pointerEvent.substr(8):
                pointerEvent;
        };

        me.momentum = function (current, start, time, lowerMargin, wrapperSize, deceleration) {
            var distance = current - start,
                speed = Math.abs(distance) / time,
                destination,
                duration;

            deceleration = deceleration === undefined ? 0.0006 : deceleration;

            destination = current + ( speed * speed ) / ( 2 * deceleration ) * ( distance < 0 ? -1 : 1 );
            duration = speed / deceleration;

            if ( destination < lowerMargin ) {
                destination = wrapperSize ? lowerMargin - ( wrapperSize / 2.5 * ( speed / 8 ) ) : lowerMargin;
                distance = Math.abs(destination - current);
                duration = distance / speed;
            } else if ( destination > 0 ) {
                destination = wrapperSize ? wrapperSize / 2.5 * ( speed / 8 ) : 0;
                distance = Math.abs(current) + destination;
                duration = distance / speed;
            }

            return {
                destination: Math.round(destination),
                duration: duration
            };
        };

        var _transform = _prefixStyle('transform');

        me.extend(me, {
            hasTransform: _transform !== false,
            hasPerspective: _prefixStyle('perspective') in _elementStyle,
            hasTouch: 'ontouchstart' in window,
            hasPointer: !!(window.PointerEvent || window.MSPointerEvent), // IE10 is prefixed
            hasTransition: _prefixStyle('transition') in _elementStyle
        });

        /*
         This should find all Android browsers lower than build 535.19 (both stock browser and webview)
         - galaxy S2 is ok
         - 2.3.6 : `AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1`
         - 4.0.4 : `AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30`
         - galaxy S3 is badAndroid (stock brower, webview)
         `AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30`
         - galaxy S4 is badAndroid (stock brower, webview)
         `AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30`
         - galaxy S5 is OK
         `AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36 (Chrome/)`
         - galaxy S6 is OK
         `AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36 (Chrome/)`
         */
        me.isBadAndroid = (function() {
            var appVersion = window.navigator.appVersion;
            // Android browser is not a chrome browser.
            if (/Android/.test(appVersion) && !(/Chrome\/\d/.test(appVersion))) {
                var safariVersion = appVersion.match(/Safari\/(\d+.\d)/);
                if(safariVersion && typeof safariVersion === "object" && safariVersion.length >= 2) {
                    return parseFloat(safariVersion[1]) < 535.19;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        })();

        me.extend(me.style = {}, {
            transform: _transform,
            transitionTimingFunction: _prefixStyle('transitionTimingFunction'),
            transitionDuration: _prefixStyle('transitionDuration'),
            transitionDelay: _prefixStyle('transitionDelay'),
            transformOrigin: _prefixStyle('transformOrigin'),
            touchAction: _prefixStyle('touchAction')
        });

        me.hasClass = function (e, c) {
            var re = new RegExp("(^|\\s)" + c + "(\\s|$)");
            return re.test(e.className);
        };

        me.addClass = function (e, c) {
            if ( me.hasClass(e, c) ) {
                return;
            }

            var newclass = e.className.split(' ');
            newclass.push(c);
            e.className = newclass.join(' ');
        };

        me.removeClass = function (e, c) {
            if ( !me.hasClass(e, c) ) {
                return;
            }

            var re = new RegExp("(^|\\s)" + c + "(\\s|$)", 'g');
            e.className = e.className.replace(re, ' ');
        };

        me.offset = function (el) {
            var left = -el.offsetLeft,
                top = -el.offsetTop;

            // jshint -W084
            while (el = el.offsetParent) {
                left -= el.offsetLeft;
                top -= el.offsetTop;
            }
            // jshint +W084

            return {
                left: left,
                top: top
            };
        };

        me.preventDefaultException = function (el, exceptions) {
            for ( var i in exceptions ) {
                if ( exceptions[i].test(el[i]) ) {
                    return true;
                }
            }

            return false;
        };

        me.extend(me.eventType = {}, {
            touchstart: 1,
            touchmove: 1,
            touchend: 1,

            mousedown: 2,
            mousemove: 2,
            mouseup: 2,

            pointerdown: 3,
            pointermove: 3,
            pointerup: 3,

            MSPointerDown: 3,
            MSPointerMove: 3,
            MSPointerUp: 3
        });

        me.extend(me.ease = {}, {
            quadratic: {
                style: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                fn: function (k) {
                    return k * ( 2 - k );
                }
            },
            circular: {
                style: 'cubic-bezier(0.1, 0.57, 0.1, 1)',   // Not properly "circular" but this looks better, it should be (0.075, 0.82, 0.165, 1)
                fn: function (k) {
                    return Math.sqrt( 1 - ( --k * k ) );
                }
            },
            back: {
                style: 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                fn: function (k) {
                    var b = 4;
                    return ( k = k - 1 ) * k * ( ( b + 1 ) * k + b ) + 1;
                }
            },
            bounce: {
                style: '',
                fn: function (k) {
                    if ( ( k /= 1 ) < ( 1 / 2.75 ) ) {
                        return 7.5625 * k * k;
                    } else if ( k < ( 2 / 2.75 ) ) {
                        return 7.5625 * ( k -= ( 1.5 / 2.75 ) ) * k + 0.75;
                    } else if ( k < ( 2.5 / 2.75 ) ) {
                        return 7.5625 * ( k -= ( 2.25 / 2.75 ) ) * k + 0.9375;
                    } else {
                        return 7.5625 * ( k -= ( 2.625 / 2.75 ) ) * k + 0.984375;
                    }
                }
            },
            elastic: {
                style: '',
                fn: function (k) {
                    var f = 0.22,
                        e = 0.4;

                    if ( k === 0 ) { return 0; }
                    if ( k == 1 ) { return 1; }

                    return ( e * Math.pow( 2, - 10 * k ) * Math.sin( ( k - f / 4 ) * ( 2 * Math.PI ) / f ) + 1 );
                }
            }
        });

        me.tap = function (e, eventName) {
            var ev = document.createEvent('Event');
            ev.initEvent(eventName, true, true);
            ev.pageX = e.pageX;
            ev.pageY = e.pageY;
            e.target.dispatchEvent(ev);
        };

        me.click = function (e) {
            var target = e.target,
                ev;

            if ( !(/(SELECT|INPUT|TEXTAREA)/i).test(target.tagName) ) {
                // https://developer.mozilla.org/en-US/docs/Web/API/MouseEvent/initMouseEvent
                // initMouseEvent is deprecated.
                ev = document.createEvent(window.MouseEvent ? 'MouseEvents' : 'Event');
                ev.initEvent('click', true, true);
                ev.view = e.view || window;
                ev.detail = 1;
                ev.screenX = target.screenX || 0;
                ev.screenY = target.screenY || 0;
                ev.clientX = target.clientX || 0;
                ev.clientY = target.clientY || 0;
                ev.ctrlKey = !!e.ctrlKey;
                ev.altKey = !!e.altKey;
                ev.shiftKey = !!e.shiftKey;
                ev.metaKey = !!e.metaKey;
                ev.button = 0;
                ev.relatedTarget = null;
                ev._constructed = true;
                target.dispatchEvent(ev);
            }
        };

        me.getTouchAction = function(eventPassthrough, addPinch) {
            var touchAction = 'none';
            if ( eventPassthrough === 'vertical' ) {
                touchAction = 'pan-y';
            } else if (eventPassthrough === 'horizontal' ) {
                touchAction = 'pan-x';
            }
            if (addPinch && touchAction != 'none') {
                // add pinch-zoom support if the browser supports it, but if not (eg. Chrome <55) do nothing
                touchAction += ' pinch-zoom';
            }
            return touchAction;
        };

        me.getRect = function(el) {
            if (el instanceof SVGElement) {
                var rect = el.getBoundingClientRect();
                return {
                    top : rect.top,
                    left : rect.left,
                    width : rect.width,
                    height : rect.height
                };
            } else {
                return {
                    top : el.offsetTop,
                    left : el.offsetLeft,
                    width : el.offsetWidth,
                    height : el.offsetHeight
                };
            }
        };

        return me;
    })();
    function IScroll (el, options) {
        this.wrapper = typeof el == 'string' ? document.querySelector(el) : el;
        this.scroller = this.wrapper.children[0];
        this.scrollerStyle = this.scroller.style;       // cache style for better performance

        this.options = {

            resizeScrollbars: true,

            mouseWheelSpeed: 20,

            snapThreshold: 0.334,

// INSERT POINT: OPTIONS
            disablePointer : !utils.hasPointer,
            disableTouch : utils.hasPointer || !utils.hasTouch,
            disableMouse : utils.hasPointer || utils.hasTouch,
            startX: 0,
            startY: 0,
            scrollY: true,
            directionLockThreshold: 5,
            momentum: true,

            bounce: true,
            bounceTime: 600,
            bounceEasing: '',

            preventDefault: true,
            preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT)$/ },

            HWCompositing: true,
            useTransition: true,
            useTransform: true,
            bindToWrapper: typeof window.onmousedown === "undefined"
        };

        for ( var i in options ) {
            this.options[i] = options[i];
        }

        // Normalize options
        this.translateZ = this.options.HWCompositing && utils.hasPerspective ? ' translateZ(0)' : '';

        this.options.useTransition = utils.hasTransition && this.options.useTransition;
        this.options.useTransform = utils.hasTransform && this.options.useTransform;

        this.options.eventPassthrough = this.options.eventPassthrough === true ? 'vertical' : this.options.eventPassthrough;
        this.options.preventDefault = !this.options.eventPassthrough && this.options.preventDefault;

        // If you want eventPassthrough I have to lock one of the axes
        this.options.scrollY = this.options.eventPassthrough == 'vertical' ? false : this.options.scrollY;
        this.options.scrollX = this.options.eventPassthrough == 'horizontal' ? false : this.options.scrollX;

        // With eventPassthrough we also need lockDirection mechanism
        this.options.freeScroll = this.options.freeScroll && !this.options.eventPassthrough;
        this.options.directionLockThreshold = this.options.eventPassthrough ? 0 : this.options.directionLockThreshold;

        this.options.bounceEasing = typeof this.options.bounceEasing == 'string' ? utils.ease[this.options.bounceEasing] || utils.ease.circular : this.options.bounceEasing;

        this.options.resizePolling = this.options.resizePolling === undefined ? 60 : this.options.resizePolling;

        if ( this.options.tap === true ) {
            this.options.tap = 'tap';
        }

        // https://github.com/cubiq/iscroll/issues/1029
        if (!this.options.useTransition && !this.options.useTransform) {
            if(!(/relative|absolute/i).test(this.scrollerStyle.position)) {
                this.scrollerStyle.position = "relative";
            }
        }

        if ( this.options.shrinkScrollbars == 'scale' ) {
            this.options.useTransition = false;
        }

        this.options.invertWheelDirection = this.options.invertWheelDirection ? -1 : 1;

// INSERT POINT: NORMALIZATION

        // Some defaults
        this.x = 0;
        this.y = 0;
        this.directionX = 0;
        this.directionY = 0;
        this._events = {};

// INSERT POINT: DEFAULTS

        this._init();
        this.refresh();

        this.scrollTo(this.options.startX, this.options.startY);
        this.enable();
    }

    IScroll.prototype = {
        version: '5.2.0-snapshot',

        _init: function () {
            this._initEvents();

            if ( this.options.scrollbars || this.options.indicators ) {
                this._initIndicators();
            }

            if ( this.options.mouseWheel ) {
                this._initWheel();
            }

            if ( this.options.snap ) {
                this._initSnap();
            }

            if ( this.options.keyBindings ) {
                this._initKeys();
            }

// INSERT POINT: _init

        },

        destroy: function () {
            this._initEvents(true);
            clearTimeout(this.resizeTimeout);
            this.resizeTimeout = null;
            this._execEvent('destroy');
        },

        _transitionEnd: function (e) {
            if ( e.target != this.scroller || !this.isInTransition ) {
                return;
            }

            this._transitionTime();
            if ( !this.resetPosition(this.options.bounceTime) ) {
                this.isInTransition = false;
                this._execEvent('scrollEnd');
            }
        },

        _start: function (e) {
            // React to left mouse button only
            if ( utils.eventType[e.type] != 1 ) {
                // for button property
                // http://unixpapa.com/js/mouse.html
                var button;
                if (!e.which) {
                    /* IE case */
                    button = (e.button < 2) ? 0 :
                        ((e.button == 4) ? 1 : 2);
                } else {
                    /* All others */
                    button = e.button;
                }
                if ( button !== 0 ) {
                    return;
                }
            }

            if ( !this.enabled || (this.initiated && utils.eventType[e.type] !== this.initiated) ) {
                return;
            }

            if ( this.options.preventDefault && !utils.isBadAndroid && !utils.preventDefaultException(e.target, this.options.preventDefaultException) ) {
                e.preventDefault();
            }

            var point = e.touches ? e.touches[0] : e,
                pos;

            this.initiated  = utils.eventType[e.type];
            this.moved      = false;
            this.distX      = 0;
            this.distY      = 0;
            this.directionX = 0;
            this.directionY = 0;
            this.directionLocked = 0;

            this.startTime = utils.getTime();

            if ( this.options.useTransition && this.isInTransition ) {
                this._transitionTime();
                this.isInTransition = false;
                pos = this.getComputedPosition();
                this._translate(Math.round(pos.x), Math.round(pos.y));
                this._execEvent('scrollEnd');
            } else if ( !this.options.useTransition && this.isAnimating ) {
                this.isAnimating = false;
                this._execEvent('scrollEnd');
            }

            this.startX    = this.x;
            this.startY    = this.y;
            this.absStartX = this.x;
            this.absStartY = this.y;
            this.pointX    = point.pageX;
            this.pointY    = point.pageY;

            this._execEvent('beforeScrollStart');
        },

        _move: function (e) {
            if ( !this.enabled || utils.eventType[e.type] !== this.initiated ) {
                return;
            }

            if ( this.options.preventDefault ) {    // increases performance on Android? TODO: check!
                e.preventDefault();
            }

            var point       = e.touches ? e.touches[0] : e,
                deltaX      = point.pageX - this.pointX,
                deltaY      = point.pageY - this.pointY,
                timestamp   = utils.getTime(),
                newX, newY,
                absDistX, absDistY;

            this.pointX     = point.pageX;
            this.pointY     = point.pageY;

            this.distX      += deltaX;
            this.distY      += deltaY;
            absDistX        = Math.abs(this.distX);
            absDistY        = Math.abs(this.distY);

            // We need to move at least 10 pixels for the scrolling to initiate
            if ( timestamp - this.endTime > 300 && (absDistX < 10 && absDistY < 10) ) {
                return;
            }

            // If you are scrolling in one direction lock the other
            if ( !this.directionLocked && !this.options.freeScroll ) {
                if ( absDistX > absDistY + this.options.directionLockThreshold ) {
                    this.directionLocked = 'h';     // lock horizontally
                } else if ( absDistY >= absDistX + this.options.directionLockThreshold ) {
                    this.directionLocked = 'v';     // lock vertically
                } else {
                    this.directionLocked = 'n';     // no lock
                }
            }

            if ( this.directionLocked == 'h' ) {
                if ( this.options.eventPassthrough == 'vertical' ) {
                    e.preventDefault();
                } else if ( this.options.eventPassthrough == 'horizontal' ) {
                    this.initiated = false;
                    return;
                }

                deltaY = 0;
            } else if ( this.directionLocked == 'v' ) {
                if ( this.options.eventPassthrough == 'horizontal' ) {
                    e.preventDefault();
                } else if ( this.options.eventPassthrough == 'vertical' ) {
                    this.initiated = false;
                    return;
                }

                deltaX = 0;
            }

            deltaX = this.hasHorizontalScroll ? deltaX : 0;
            deltaY = this.hasVerticalScroll ? deltaY : 0;

            newX = this.x + deltaX;
            newY = this.y + deltaY;

            // Slow down if outside of the boundaries
            if ( newX > 0 || newX < this.maxScrollX ) {
                newX = this.options.bounce ? this.x + deltaX / 3 : newX > 0 ? 0 : this.maxScrollX;
            }
            if ( newY > 0 || newY < this.maxScrollY ) {
                newY = this.options.bounce ? this.y + deltaY / 3 : newY > 0 ? 0 : this.maxScrollY;
            }

            this.directionX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0;
            this.directionY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0;

            if ( !this.moved ) {
                this._execEvent('scrollStart');
            }

            this.moved = true;

            this._translate(newX, newY);

            /* REPLACE START: _move */

            if ( timestamp - this.startTime > 300 ) {
                this.startTime = timestamp;
                this.startX = this.x;
                this.startY = this.y;
            }

            /* REPLACE END: _move */

        },

        _end: function (e) {
            if ( !this.enabled || utils.eventType[e.type] !== this.initiated ) {
                return;
            }

            if ( this.options.preventDefault && !utils.preventDefaultException(e.target, this.options.preventDefaultException) ) {
                e.preventDefault();
            }

            var point = e.changedTouches ? e.changedTouches[0] : e,
                momentumX,
                momentumY,
                duration = utils.getTime() - this.startTime,
                newX = Math.round(this.x),
                newY = Math.round(this.y),
                distanceX = Math.abs(newX - this.startX),
                distanceY = Math.abs(newY - this.startY),
                time = 0,
                easing = '';

            this.isInTransition = 0;
            this.initiated = 0;
            this.endTime = utils.getTime();

            // reset if we are outside of the boundaries
            if ( this.resetPosition(this.options.bounceTime) ) {
                return;
            }

            this.scrollTo(newX, newY);  // ensures that the last position is rounded

            // we scrolled less than 10 pixels
            if ( !this.moved ) {
                if ( this.options.tap ) {
                    utils.tap(e, this.options.tap);
                }

                if ( this.options.click ) {
                    utils.click(e);
                }

                this._execEvent('scrollCancel');
                return;
            }

            if ( this._events.flick && duration < 200 && distanceX < 100 && distanceY < 100 ) {
                this._execEvent('flick');
                return;
            }

            // start momentum animation if needed
            if ( this.options.momentum && duration < 300 ) {
                momentumX = this.hasHorizontalScroll ? utils.momentum(this.x, this.startX, duration, this.maxScrollX, this.options.bounce ? this.wrapperWidth : 0, this.options.deceleration) : { destination: newX, duration: 0 };
                momentumY = this.hasVerticalScroll ? utils.momentum(this.y, this.startY, duration, this.maxScrollY, this.options.bounce ? this.wrapperHeight : 0, this.options.deceleration) : { destination: newY, duration: 0 };
                newX = momentumX.destination;
                newY = momentumY.destination;
                time = Math.max(momentumX.duration, momentumY.duration);
                this.isInTransition = 1;
            }


            if ( this.options.snap ) {
                var snap = this._nearestSnap(newX, newY);
                this.currentPage = snap;
                time = this.options.snapSpeed || Math.max(
                        Math.max(
                            Math.min(Math.abs(newX - snap.x), 1000),
                            Math.min(Math.abs(newY - snap.y), 1000)
                        ), 300);
                newX = snap.x;
                newY = snap.y;

                this.directionX = 0;
                this.directionY = 0;
                easing = this.options.bounceEasing;
            }

// INSERT POINT: _end

            if ( newX != this.x || newY != this.y ) {
                // change easing function when scroller goes out of the boundaries
                if ( newX > 0 || newX < this.maxScrollX || newY > 0 || newY < this.maxScrollY ) {
                    easing = utils.ease.quadratic;
                }

                this.scrollTo(newX, newY, time, easing);
                return;
            }

            this._execEvent('scrollEnd');
        },

        _resize: function () {
            var that = this;

            clearTimeout(this.resizeTimeout);

            this.resizeTimeout = setTimeout(function () {
                that.refresh();
            }, this.options.resizePolling);
        },

        resetPosition: function (time) {
            var x = this.x,
                y = this.y;

            time = time || 0;

            if ( !this.hasHorizontalScroll || this.x > 0 ) {
                x = 0;
            } else if ( this.x < this.maxScrollX ) {
                x = this.maxScrollX;
            }

            if ( !this.hasVerticalScroll || this.y > 0 ) {
                y = 0;
            } else if ( this.y < this.maxScrollY ) {
                y = this.maxScrollY;
            }

            if ( x == this.x && y == this.y ) {
                return false;
            }

            this.scrollTo(x, y, time, this.options.bounceEasing);

            return true;
        },

        disable: function () {
            this.enabled = false;
        },

        enable: function () {
            this.enabled = true;
        },

        refresh: function () {
            utils.getRect(this.wrapper);        // Force reflow

            this.wrapperWidth   = this.wrapper.clientWidth;
            this.wrapperHeight  = this.wrapper.clientHeight;

            var rect = utils.getRect(this.scroller);
            /* REPLACE START: refresh */

            this.scrollerWidth  = rect.width;
            this.scrollerHeight = rect.height;

            this.maxScrollX     = this.wrapperWidth - this.scrollerWidth;
            this.maxScrollY     = this.wrapperHeight - this.scrollerHeight;

            /* REPLACE END: refresh */

            this.hasHorizontalScroll    = this.options.scrollX && this.maxScrollX < 0;
            this.hasVerticalScroll      = this.options.scrollY && this.maxScrollY < 0;

            if ( !this.hasHorizontalScroll ) {
                this.maxScrollX = 0;
                this.scrollerWidth = this.wrapperWidth;
            }

            if ( !this.hasVerticalScroll ) {
                this.maxScrollY = 0;
                this.scrollerHeight = this.wrapperHeight;
            }

            this.endTime = 0;
            this.directionX = 0;
            this.directionY = 0;

            if(utils.hasPointer && !this.options.disablePointer) {
                // The wrapper should have `touchAction` property for using pointerEvent.
                this.wrapper.style[utils.style.touchAction] = utils.getTouchAction(this.options.eventPassthrough, true);

                // case. not support 'pinch-zoom'
                // https://github.com/cubiq/iscroll/issues/1118#issuecomment-270057583
                if (!this.wrapper.style[utils.style.touchAction]) {
                    this.wrapper.style[utils.style.touchAction] = utils.getTouchAction(this.options.eventPassthrough, false);
                }
            }
            this.wrapperOffset = utils.offset(this.wrapper);

            this._execEvent('refresh');

            this.resetPosition();

// INSERT POINT: _refresh

        },

        on: function (type, fn) {
            if ( !this._events[type] ) {
                this._events[type] = [];
            }

            this._events[type].push(fn);
        },

        off: function (type, fn) {
            if ( !this._events[type] ) {
                return;
            }

            var index = this._events[type].indexOf(fn);

            if ( index > -1 ) {
                this._events[type].splice(index, 1);
            }
        },

        _execEvent: function (type) {
            if ( !this._events[type] ) {
                return;
            }

            var i = 0,
                l = this._events[type].length;

            if ( !l ) {
                return;
            }

            for ( ; i < l; i++ ) {
                this._events[type][i].apply(this, [].slice.call(arguments, 1));
            }
        },

        scrollBy: function (x, y, time, easing) {
            x = this.x + x;
            y = this.y + y;
            time = time || 0;

            this.scrollTo(x, y, time, easing);
        },

        scrollTo: function (x, y, time, easing) {
            easing = easing || utils.ease.circular;

            this.isInTransition = this.options.useTransition && time > 0;
            var transitionType = this.options.useTransition && easing.style;
            if ( !time || transitionType ) {
                if(transitionType) {
                    this._transitionTimingFunction(easing.style);
                    this._transitionTime(time);
                }
                this._translate(x, y);
            } else {
                this._animate(x, y, time, easing.fn);
            }
        },

        scrollToElement: function (el, time, offsetX, offsetY, easing) {
            el = el.nodeType ? el : this.scroller.querySelector(el);

            if ( !el ) {
                return;
            }

            var pos = utils.offset(el);

            pos.left -= this.wrapperOffset.left;
            pos.top  -= this.wrapperOffset.top;

            // if offsetX/Y are true we center the element to the screen
            var elRect = utils.getRect(el);
            var wrapperRect = utils.getRect(this.wrapper);
            if ( offsetX === true ) {
                offsetX = Math.round(elRect.width / 2 - wrapperRect.width / 2);
            }
            if ( offsetY === true ) {
                offsetY = Math.round(elRect.height / 2 - wrapperRect.height / 2);
            }

            pos.left -= offsetX || 0;
            pos.top  -= offsetY || 0;

            pos.left = pos.left > 0 ? 0 : pos.left < this.maxScrollX ? this.maxScrollX : pos.left;
            pos.top  = pos.top  > 0 ? 0 : pos.top  < this.maxScrollY ? this.maxScrollY : pos.top;

            time = time === undefined || time === null || time === 'auto' ? Math.max(Math.abs(this.x-pos.left), Math.abs(this.y-pos.top)) : time;

            this.scrollTo(pos.left, pos.top, time, easing);
        },

        _transitionTime: function (time) {
            if (!this.options.useTransition) {
                return;
            }
            time = time || 0;
            var durationProp = utils.style.transitionDuration;
            if(!durationProp) {
                return;
            }

            this.scrollerStyle[durationProp] = time + 'ms';

            if ( !time && utils.isBadAndroid ) {
                this.scrollerStyle[durationProp] = '0.0001ms';
                // remove 0.0001ms
                var self = this;
                rAF(function() {
                    if(self.scrollerStyle[durationProp] === '0.0001ms') {
                        self.scrollerStyle[durationProp] = '0s';
                    }
                });
            }


            if ( this.indicators ) {
                for ( var i = this.indicators.length; i--; ) {
                    this.indicators[i].transitionTime(time);
                }
            }


// INSERT POINT: _transitionTime

        },

        _transitionTimingFunction: function (easing) {
            this.scrollerStyle[utils.style.transitionTimingFunction] = easing;


            if ( this.indicators ) {
                for ( var i = this.indicators.length; i--; ) {
                    this.indicators[i].transitionTimingFunction(easing);
                }
            }


// INSERT POINT: _transitionTimingFunction

        },

        _translate: function (x, y) {
            if ( this.options.useTransform ) {

                /* REPLACE START: _translate */

                this.scrollerStyle[utils.style.transform] = 'translate(' + x + 'px,' + y + 'px)' + this.translateZ;

                /* REPLACE END: _translate */

            } else {
                x = Math.round(x);
                y = Math.round(y);
                this.scrollerStyle.left = x + 'px';
                this.scrollerStyle.top = y + 'px';
            }

            this.x = x;
            this.y = y;


            if ( this.indicators ) {
                for ( var i = this.indicators.length; i--; ) {
                    this.indicators[i].updatePosition();
                }
            }


// INSERT POINT: _translate

        },

        _initEvents: function (remove) {
            var eventType = remove ? utils.removeEvent : utils.addEvent,
                target = this.options.bindToWrapper ? this.wrapper : window;

            eventType(window, 'orientationchange', this);
            eventType(window, 'resize', this);

            if ( this.options.click ) {
                eventType(this.wrapper, 'click', this, true);
            }

            if ( !this.options.disableMouse ) {
                eventType(this.wrapper, 'mousedown', this);
                eventType(target, 'mousemove', this);
                eventType(target, 'mousecancel', this);
                eventType(target, 'mouseup', this);
            }

            if ( utils.hasPointer && !this.options.disablePointer ) {
                eventType(this.wrapper, utils.prefixPointerEvent('pointerdown'), this);
                eventType(target, utils.prefixPointerEvent('pointermove'), this);
                eventType(target, utils.prefixPointerEvent('pointercancel'), this);
                eventType(target, utils.prefixPointerEvent('pointerup'), this);
            }

            if ( utils.hasTouch && !this.options.disableTouch ) {
                eventType(this.wrapper, 'touchstart', this);
                eventType(target, 'touchmove', this);
                eventType(target, 'touchcancel', this);
                eventType(target, 'touchend', this);
            }

            eventType(this.scroller, 'transitionend', this);
            eventType(this.scroller, 'webkitTransitionEnd', this);
            eventType(this.scroller, 'oTransitionEnd', this);
            eventType(this.scroller, 'MSTransitionEnd', this);
        },

        getComputedPosition: function () {
            var matrix = window.getComputedStyle(this.scroller, null),
                x, y;

            if ( this.options.useTransform ) {
                matrix = matrix[utils.style.transform].split(')')[0].split(', ');
                x = +(matrix[12] || matrix[4]);
                y = +(matrix[13] || matrix[5]);
            } else {
                x = +matrix.left.replace(/[^-\d.]/g, '');
                y = +matrix.top.replace(/[^-\d.]/g, '');
            }

            return { x: x, y: y };
        },
        _initIndicators: function () {
            var interactive = this.options.interactiveScrollbars,
                customStyle = typeof this.options.scrollbars != 'string',
                indicators = [],
                indicator;

            var that = this;

            this.indicators = [];

            if ( this.options.scrollbars ) {
                // Vertical scrollbar
                if ( this.options.scrollY ) {
                    indicator = {
                        el: createDefaultScrollbar('v', interactive, this.options.scrollbars),
                        interactive: interactive,
                        defaultScrollbars: true,
                        customStyle: customStyle,
                        resize: this.options.resizeScrollbars,
                        shrink: this.options.shrinkScrollbars,
                        fade: this.options.fadeScrollbars,
                        listenX: false
                    };

                    this.wrapper.appendChild(indicator.el);
                    indicators.push(indicator);
                }

                // Horizontal scrollbar
                if ( this.options.scrollX ) {
                    indicator = {
                        el: createDefaultScrollbar('h', interactive, this.options.scrollbars),
                        interactive: interactive,
                        defaultScrollbars: true,
                        customStyle: customStyle,
                        resize: this.options.resizeScrollbars,
                        shrink: this.options.shrinkScrollbars,
                        fade: this.options.fadeScrollbars,
                        listenY: false
                    };

                    this.wrapper.appendChild(indicator.el);
                    indicators.push(indicator);
                }
            }

            if ( this.options.indicators ) {
                // TODO: check concat compatibility
                indicators = indicators.concat(this.options.indicators);
            }

            for ( var i = indicators.length; i--; ) {
                this.indicators.push( new Indicator(this, indicators[i]) );
            }

            // TODO: check if we can use array.map (wide compatibility and performance issues)
            function _indicatorsMap (fn) {
                if (that.indicators) {
                    for ( var i = that.indicators.length; i--; ) {
                        fn.call(that.indicators[i]);
                    }
                }
            }

            if ( this.options.fadeScrollbars ) {
                this.on('scrollEnd', function () {
                    _indicatorsMap(function () {
                        this.fade();
                    });
                });

                this.on('scrollCancel', function () {
                    _indicatorsMap(function () {
                        this.fade();
                    });
                });

                this.on('scrollStart', function () {
                    _indicatorsMap(function () {
                        this.fade(1);
                    });
                });

                this.on('beforeScrollStart', function () {
                    _indicatorsMap(function () {
                        this.fade(1, true);
                    });
                });
            }


            this.on('refresh', function () {
                _indicatorsMap(function () {
                    this.refresh();
                });
            });

            this.on('destroy', function () {
                _indicatorsMap(function () {
                    this.destroy();
                });

                delete this.indicators;
            });
        },

        _initWheel: function () {
            utils.addEvent(this.wrapper, 'wheel', this);
            utils.addEvent(this.wrapper, 'mousewheel', this);
            utils.addEvent(this.wrapper, 'DOMMouseScroll', this);

            this.on('destroy', function () {
                clearTimeout(this.wheelTimeout);
                this.wheelTimeout = null;
                utils.removeEvent(this.wrapper, 'wheel', this);
                utils.removeEvent(this.wrapper, 'mousewheel', this);
                utils.removeEvent(this.wrapper, 'DOMMouseScroll', this);
            });
        },

        _wheel: function (e) {
            if ( !this.enabled ) {
                return;
            }

            e.preventDefault();

            var wheelDeltaX, wheelDeltaY,
                newX, newY,
                that = this;

            if ( this.wheelTimeout === undefined ) {
                that._execEvent('scrollStart');
            }

            // Execute the scrollEnd event after 400ms the wheel stopped scrolling
            clearTimeout(this.wheelTimeout);
            this.wheelTimeout = setTimeout(function () {
                if(!that.options.snap) {
                    that._execEvent('scrollEnd');
                }
                that.wheelTimeout = undefined;
            }, 400);

            if ( 'deltaX' in e ) {
                if (e.deltaMode === 1) {
                    wheelDeltaX = -e.deltaX * this.options.mouseWheelSpeed;
                    wheelDeltaY = -e.deltaY * this.options.mouseWheelSpeed;
                } else {
                    wheelDeltaX = -e.deltaX;
                    wheelDeltaY = -e.deltaY;
                }
            } else if ( 'wheelDeltaX' in e ) {
                wheelDeltaX = e.wheelDeltaX / 120 * this.options.mouseWheelSpeed;
                wheelDeltaY = e.wheelDeltaY / 120 * this.options.mouseWheelSpeed;
            } else if ( 'wheelDelta' in e ) {
                wheelDeltaX = wheelDeltaY = e.wheelDelta / 120 * this.options.mouseWheelSpeed;
            } else if ( 'detail' in e ) {
                wheelDeltaX = wheelDeltaY = -e.detail / 3 * this.options.mouseWheelSpeed;
            } else {
                return;
            }

            wheelDeltaX *= this.options.invertWheelDirection;
            wheelDeltaY *= this.options.invertWheelDirection;

            if ( !this.hasVerticalScroll ) {
                wheelDeltaX = wheelDeltaY;
                wheelDeltaY = 0;
            }

            if ( this.options.snap ) {
                newX = this.currentPage.pageX;
                newY = this.currentPage.pageY;

                if ( wheelDeltaX > 0 ) {
                    newX--;
                } else if ( wheelDeltaX < 0 ) {
                    newX++;
                }

                if ( wheelDeltaY > 0 ) {
                    newY--;
                } else if ( wheelDeltaY < 0 ) {
                    newY++;
                }

                this.goToPage(newX, newY);

                return;
            }

            newX = this.x + Math.round(this.hasHorizontalScroll ? wheelDeltaX : 0);
            newY = this.y + Math.round(this.hasVerticalScroll ? wheelDeltaY : 0);

            this.directionX = wheelDeltaX > 0 ? -1 : wheelDeltaX < 0 ? 1 : 0;
            this.directionY = wheelDeltaY > 0 ? -1 : wheelDeltaY < 0 ? 1 : 0;

            if ( newX > 0 ) {
                newX = 0;
            } else if ( newX < this.maxScrollX ) {
                newX = this.maxScrollX;
            }

            if ( newY > 0 ) {
                newY = 0;
            } else if ( newY < this.maxScrollY ) {
                newY = this.maxScrollY;
            }

            this.scrollTo(newX, newY, 0);

// INSERT POINT: _wheel
        },

        _initSnap: function () {
            this.currentPage = {};

            if ( typeof this.options.snap == 'string' ) {
                this.options.snap = this.scroller.querySelectorAll(this.options.snap);
            }

            this.on('refresh', function () {
                var i = 0, l,
                    m = 0, n,
                    cx, cy,
                    x = 0, y,
                    stepX = this.options.snapStepX || this.wrapperWidth,
                    stepY = this.options.snapStepY || this.wrapperHeight,
                    el,
                    rect;

                this.pages = [];

                if ( !this.wrapperWidth || !this.wrapperHeight || !this.scrollerWidth || !this.scrollerHeight ) {
                    return;
                }

                if ( this.options.snap === true ) {
                    cx = Math.round( stepX / 2 );
                    cy = Math.round( stepY / 2 );

                    while ( x > -this.scrollerWidth ) {
                        this.pages[i] = [];
                        l = 0;
                        y = 0;

                        while ( y > -this.scrollerHeight ) {
                            this.pages[i][l] = {
                                x: Math.max(x, this.maxScrollX),
                                y: Math.max(y, this.maxScrollY),
                                width: stepX,
                                height: stepY,
                                cx: x - cx,
                                cy: y - cy
                            };

                            y -= stepY;
                            l++;
                        }

                        x -= stepX;
                        i++;
                    }
                } else {
                    el = this.options.snap;
                    l = el.length;
                    n = -1;

                    for ( ; i < l; i++ ) {
                        rect = utils.getRect(el[i]);
                        if ( i === 0 || rect.left <= utils.getRect(el[i-1]).left ) {
                            m = 0;
                            n++;
                        }

                        if ( !this.pages[m] ) {
                            this.pages[m] = [];
                        }

                        x = Math.max(-rect.left, this.maxScrollX);
                        y = Math.max(-rect.top, this.maxScrollY);
                        cx = x - Math.round(rect.width / 2);
                        cy = y - Math.round(rect.height / 2);

                        this.pages[m][n] = {
                            x: x,
                            y: y,
                            width: rect.width,
                            height: rect.height,
                            cx: cx,
                            cy: cy
                        };

                        if ( x > this.maxScrollX ) {
                            m++;
                        }
                    }
                }

                this.goToPage(this.currentPage.pageX || 0, this.currentPage.pageY || 0, 0);

                // Update snap threshold if needed
                if ( this.options.snapThreshold % 1 === 0 ) {
                    this.snapThresholdX = this.options.snapThreshold;
                    this.snapThresholdY = this.options.snapThreshold;
                } else {
                    this.snapThresholdX = Math.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].width * this.options.snapThreshold);
                    this.snapThresholdY = Math.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].height * this.options.snapThreshold);
                }
            });

            this.on('flick', function () {
                var time = this.options.snapSpeed || Math.max(
                        Math.max(
                            Math.min(Math.abs(this.x - this.startX), 1000),
                            Math.min(Math.abs(this.y - this.startY), 1000)
                        ), 300);

                this.goToPage(
                    this.currentPage.pageX + this.directionX,
                    this.currentPage.pageY + this.directionY,
                    time
                );
            });
        },

        _nearestSnap: function (x, y) {
            if ( !this.pages.length ) {
                return { x: 0, y: 0, pageX: 0, pageY: 0 };
            }

            var i = 0,
                l = this.pages.length,
                m = 0;

            // Check if we exceeded the snap threshold
            if ( Math.abs(x - this.absStartX) < this.snapThresholdX &&
                Math.abs(y - this.absStartY) < this.snapThresholdY ) {
                return this.currentPage;
            }

            if ( x > 0 ) {
                x = 0;
            } else if ( x < this.maxScrollX ) {
                x = this.maxScrollX;
            }

            if ( y > 0 ) {
                y = 0;
            } else if ( y < this.maxScrollY ) {
                y = this.maxScrollY;
            }

            for ( ; i < l; i++ ) {
                if ( x >= this.pages[i][0].cx ) {
                    x = this.pages[i][0].x;
                    break;
                }
            }

            l = this.pages[i].length;

            for ( ; m < l; m++ ) {
                if ( y >= this.pages[0][m].cy ) {
                    y = this.pages[0][m].y;
                    break;
                }
            }

            if ( i == this.currentPage.pageX ) {
                i += this.directionX;

                if ( i < 0 ) {
                    i = 0;
                } else if ( i >= this.pages.length ) {
                    i = this.pages.length - 1;
                }

                x = this.pages[i][0].x;
            }

            if ( m == this.currentPage.pageY ) {
                m += this.directionY;

                if ( m < 0 ) {
                    m = 0;
                } else if ( m >= this.pages[0].length ) {
                    m = this.pages[0].length - 1;
                }

                y = this.pages[0][m].y;
            }

            return {
                x: x,
                y: y,
                pageX: i,
                pageY: m
            };
        },

        goToPage: function (x, y, time, easing) {
            easing = easing || this.options.bounceEasing;

            if ( x >= this.pages.length ) {
                x = this.pages.length - 1;
            } else if ( x < 0 ) {
                x = 0;
            }

            if ( y >= this.pages[x].length ) {
                y = this.pages[x].length - 1;
            } else if ( y < 0 ) {
                y = 0;
            }

            var posX = this.pages[x][y].x,
                posY = this.pages[x][y].y;

            time = time === undefined ? this.options.snapSpeed || Math.max(
                    Math.max(
                        Math.min(Math.abs(posX - this.x), 1000),
                        Math.min(Math.abs(posY - this.y), 1000)
                    ), 300) : time;

            this.currentPage = {
                x: posX,
                y: posY,
                pageX: x,
                pageY: y
            };

            this.scrollTo(posX, posY, time, easing);
        },

        next: function (time, easing) {
            var x = this.currentPage.pageX,
                y = this.currentPage.pageY;

            x++;

            if ( x >= this.pages.length && this.hasVerticalScroll ) {
                x = 0;
                y++;
            }

            this.goToPage(x, y, time, easing);
        },

        prev: function (time, easing) {
            var x = this.currentPage.pageX,
                y = this.currentPage.pageY;

            x--;

            if ( x < 0 && this.hasVerticalScroll ) {
                x = 0;
                y--;
            }

            this.goToPage(x, y, time, easing);
        },

        _initKeys: function (e) {
            // default key bindings
            var keys = {
                pageUp: 33,
                pageDown: 34,
                end: 35,
                home: 36,
                left: 37,
                up: 38,
                right: 39,
                down: 40
            };
            var i;

            // if you give me characters I give you keycode
            if ( typeof this.options.keyBindings == 'object' ) {
                for ( i in this.options.keyBindings ) {
                    if ( typeof this.options.keyBindings[i] == 'string' ) {
                        this.options.keyBindings[i] = this.options.keyBindings[i].toUpperCase().charCodeAt(0);
                    }
                }
            } else {
                this.options.keyBindings = {};
            }

            for ( i in keys ) {
                this.options.keyBindings[i] = this.options.keyBindings[i] || keys[i];
            }

            utils.addEvent(window, 'keydown', this);

            this.on('destroy', function () {
                utils.removeEvent(window, 'keydown', this);
            });
        },

        _key: function (e) {
            if ( !this.enabled ) {
                return;
            }

            var snap = this.options.snap,   // we are using this alot, better to cache it
                newX = snap ? this.currentPage.pageX : this.x,
                newY = snap ? this.currentPage.pageY : this.y,
                now = utils.getTime(),
                prevTime = this.keyTime || 0,
                acceleration = 0.250,
                pos;

            if ( this.options.useTransition && this.isInTransition ) {
                pos = this.getComputedPosition();

                this._translate(Math.round(pos.x), Math.round(pos.y));
                this.isInTransition = false;
            }

            this.keyAcceleration = now - prevTime < 200 ? Math.min(this.keyAcceleration + acceleration, 50) : 0;

            switch ( e.keyCode ) {
                case this.options.keyBindings.pageUp:
                    if ( this.hasHorizontalScroll && !this.hasVerticalScroll ) {
                        newX += snap ? 1 : this.wrapperWidth;
                    } else {
                        newY += snap ? 1 : this.wrapperHeight;
                    }
                    break;
                case this.options.keyBindings.pageDown:
                    if ( this.hasHorizontalScroll && !this.hasVerticalScroll ) {
                        newX -= snap ? 1 : this.wrapperWidth;
                    } else {
                        newY -= snap ? 1 : this.wrapperHeight;
                    }
                    break;
                case this.options.keyBindings.end:
                    newX = snap ? this.pages.length-1 : this.maxScrollX;
                    newY = snap ? this.pages[0].length-1 : this.maxScrollY;
                    break;
                case this.options.keyBindings.home:
                    newX = 0;
                    newY = 0;
                    break;
                case this.options.keyBindings.left:
                    newX += snap ? -1 : 5 + this.keyAcceleration>>0;
                    break;
                case this.options.keyBindings.up:
                    newY += snap ? 1 : 5 + this.keyAcceleration>>0;
                    break;
                case this.options.keyBindings.right:
                    newX -= snap ? -1 : 5 + this.keyAcceleration>>0;
                    break;
                case this.options.keyBindings.down:
                    newY -= snap ? 1 : 5 + this.keyAcceleration>>0;
                    break;
                default:
                    return;
            }

            if ( snap ) {
                this.goToPage(newX, newY);
                return;
            }

            if ( newX > 0 ) {
                newX = 0;
                this.keyAcceleration = 0;
            } else if ( newX < this.maxScrollX ) {
                newX = this.maxScrollX;
                this.keyAcceleration = 0;
            }

            if ( newY > 0 ) {
                newY = 0;
                this.keyAcceleration = 0;
            } else if ( newY < this.maxScrollY ) {
                newY = this.maxScrollY;
                this.keyAcceleration = 0;
            }

            this.scrollTo(newX, newY, 0);

            this.keyTime = now;
        },

        _animate: function (destX, destY, duration, easingFn) {
            var that = this,
                startX = this.x,
                startY = this.y,
                startTime = utils.getTime(),
                destTime = startTime + duration;

            function step () {
                var now = utils.getTime(),
                    newX, newY,
                    easing;

                if ( now >= destTime ) {
                    that.isAnimating = false;
                    that._translate(destX, destY);

                    if ( !that.resetPosition(that.options.bounceTime) ) {
                        that._execEvent('scrollEnd');
                    }

                    return;
                }

                now = ( now - startTime ) / duration;
                easing = easingFn(now);
                newX = ( destX - startX ) * easing + startX;
                newY = ( destY - startY ) * easing + startY;
                that._translate(newX, newY);

                if ( that.isAnimating ) {
                    rAF(step);
                }
            }

            this.isAnimating = true;
            step();
        },
        handleEvent: function (e) {
            switch ( e.type ) {
                case 'touchstart':
                case 'pointerdown':
                case 'MSPointerDown':
                case 'mousedown':
                    this._start(e);
                    break;
                case 'touchmove':
                case 'pointermove':
                case 'MSPointerMove':
                case 'mousemove':
                    this._move(e);
                    break;
                case 'touchend':
                case 'pointerup':
                case 'MSPointerUp':
                case 'mouseup':
                case 'touchcancel':
                case 'pointercancel':
                case 'MSPointerCancel':
                case 'mousecancel':
                    this._end(e);
                    break;
                case 'orientationchange':
                case 'resize':
                    this._resize();
                    break;
                case 'transitionend':
                case 'webkitTransitionEnd':
                case 'oTransitionEnd':
                case 'MSTransitionEnd':
                    this._transitionEnd(e);
                    break;
                case 'wheel':
                case 'DOMMouseScroll':
                case 'mousewheel':
                    this._wheel(e);
                    break;
                case 'keydown':
                    this._key(e);
                    break;
                case 'click':
                    if ( this.enabled && !e._constructed ) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    break;
            }
        }
    };
    function createDefaultScrollbar (direction, interactive, type) {
        var scrollbar = document.createElement('div'),
            indicator = document.createElement('div');

        if ( type === true ) {
            scrollbar.style.cssText = 'position:absolute;z-index:9999';
            indicator.style.cssText = '-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);border-radius:3px';
        }

        indicator.className = 'iScrollIndicator';

        if ( direction == 'h' ) {
            if ( type === true ) {
                scrollbar.style.cssText += ';height:7px;left:2px;right:2px;bottom:0';
                indicator.style.height = '100%';
            }
            scrollbar.className = 'iScrollHorizontalScrollbar';
        } else {
            if ( type === true ) {
                scrollbar.style.cssText += ';width:7px;bottom:2px;top:2px;right:1px';
                indicator.style.width = '100%';
            }
            scrollbar.className = 'iScrollVerticalScrollbar';
        }

        scrollbar.style.cssText += ';overflow:hidden';

        if ( !interactive ) {
            scrollbar.style.pointerEvents = 'none';
        }

        scrollbar.appendChild(indicator);

        return scrollbar;
    }

    function Indicator (scroller, options) {
        this.wrapper = typeof options.el == 'string' ? document.querySelector(options.el) : options.el;
        this.wrapperStyle = this.wrapper.style;
        this.indicator = this.wrapper.children[0];
        this.indicatorStyle = this.indicator.style;
        this.scroller = scroller;

        this.options = {
            listenX: true,
            listenY: true,
            interactive: false,
            resize: true,
            defaultScrollbars: false,
            shrink: false,
            fade: false,
            speedRatioX: 0,
            speedRatioY: 0
        };

        for ( var i in options ) {
            this.options[i] = options[i];
        }

        this.sizeRatioX = 1;
        this.sizeRatioY = 1;
        this.maxPosX = 0;
        this.maxPosY = 0;

        if ( this.options.interactive ) {
            if ( !this.options.disableTouch ) {
                utils.addEvent(this.indicator, 'touchstart', this);
                utils.addEvent(window, 'touchend', this);
            }
            if ( !this.options.disablePointer ) {
                utils.addEvent(this.indicator, utils.prefixPointerEvent('pointerdown'), this);
                utils.addEvent(window, utils.prefixPointerEvent('pointerup'), this);
            }
            if ( !this.options.disableMouse ) {
                utils.addEvent(this.indicator, 'mousedown', this);
                utils.addEvent(window, 'mouseup', this);
            }
        }

        if ( this.options.fade ) {
            this.wrapperStyle[utils.style.transform] = this.scroller.translateZ;
            var durationProp = utils.style.transitionDuration;
            if(!durationProp) {
                return;
            }
            this.wrapperStyle[durationProp] = utils.isBadAndroid ? '0.0001ms' : '0ms';
            // remove 0.0001ms
            var self = this;
            if(utils.isBadAndroid) {
                rAF(function() {
                    if(self.wrapperStyle[durationProp] === '0.0001ms') {
                        self.wrapperStyle[durationProp] = '0s';
                    }
                });
            }
            this.wrapperStyle.opacity = '0';
        }
    }

    Indicator.prototype = {
        handleEvent: function (e) {
            switch ( e.type ) {
                case 'touchstart':
                case 'pointerdown':
                case 'MSPointerDown':
                case 'mousedown':
                    this._start(e);
                    break;
                case 'touchmove':
                case 'pointermove':
                case 'MSPointerMove':
                case 'mousemove':
                    this._move(e);
                    break;
                case 'touchend':
                case 'pointerup':
                case 'MSPointerUp':
                case 'mouseup':
                case 'touchcancel':
                case 'pointercancel':
                case 'MSPointerCancel':
                case 'mousecancel':
                    this._end(e);
                    break;
            }
        },

        destroy: function () {
            if ( this.options.fadeScrollbars ) {
                clearTimeout(this.fadeTimeout);
                this.fadeTimeout = null;
            }
            if ( this.options.interactive ) {
                utils.removeEvent(this.indicator, 'touchstart', this);
                utils.removeEvent(this.indicator, utils.prefixPointerEvent('pointerdown'), this);
                utils.removeEvent(this.indicator, 'mousedown', this);

                utils.removeEvent(window, 'touchmove', this);
                utils.removeEvent(window, utils.prefixPointerEvent('pointermove'), this);
                utils.removeEvent(window, 'mousemove', this);

                utils.removeEvent(window, 'touchend', this);
                utils.removeEvent(window, utils.prefixPointerEvent('pointerup'), this);
                utils.removeEvent(window, 'mouseup', this);
            }

            if ( this.options.defaultScrollbars && this.wrapper.parentNode ) {
                this.wrapper.parentNode.removeChild(this.wrapper);
            }
        },

        _start: function (e) {
            var point = e.touches ? e.touches[0] : e;

            e.preventDefault();
            e.stopPropagation();

            this.transitionTime();

            this.initiated = true;
            this.moved = false;
            this.lastPointX = point.pageX;
            this.lastPointY = point.pageY;

            this.startTime  = utils.getTime();

            if ( !this.options.disableTouch ) {
                utils.addEvent(window, 'touchmove', this);
            }
            if ( !this.options.disablePointer ) {
                utils.addEvent(window, utils.prefixPointerEvent('pointermove'), this);
            }
            if ( !this.options.disableMouse ) {
                utils.addEvent(window, 'mousemove', this);
            }

            this.scroller._execEvent('beforeScrollStart');
        },

        _move: function (e) {
            var point = e.touches ? e.touches[0] : e,
                deltaX, deltaY,
                newX, newY,
                timestamp = utils.getTime();

            if ( !this.moved ) {
                this.scroller._execEvent('scrollStart');
            }

            this.moved = true;

            deltaX = point.pageX - this.lastPointX;
            this.lastPointX = point.pageX;

            deltaY = point.pageY - this.lastPointY;
            this.lastPointY = point.pageY;

            newX = this.x + deltaX;
            newY = this.y + deltaY;

            this._pos(newX, newY);

// INSERT POINT: indicator._move

            e.preventDefault();
            e.stopPropagation();
        },

        _end: function (e) {
            if ( !this.initiated ) {
                return;
            }

            this.initiated = false;

            e.preventDefault();
            e.stopPropagation();

            utils.removeEvent(window, 'touchmove', this);
            utils.removeEvent(window, utils.prefixPointerEvent('pointermove'), this);
            utils.removeEvent(window, 'mousemove', this);

            if ( this.scroller.options.snap ) {
                var snap = this.scroller._nearestSnap(this.scroller.x, this.scroller.y);

                var time = this.options.snapSpeed || Math.max(
                        Math.max(
                            Math.min(Math.abs(this.scroller.x - snap.x), 1000),
                            Math.min(Math.abs(this.scroller.y - snap.y), 1000)
                        ), 300);

                if ( this.scroller.x != snap.x || this.scroller.y != snap.y ) {
                    this.scroller.directionX = 0;
                    this.scroller.directionY = 0;
                    this.scroller.currentPage = snap;
                    this.scroller.scrollTo(snap.x, snap.y, time, this.scroller.options.bounceEasing);
                }
            }

            if ( this.moved ) {
                this.scroller._execEvent('scrollEnd');
            }
        },

        transitionTime: function (time) {
            time = time || 0;
            var durationProp = utils.style.transitionDuration;
            if(!durationProp) {
                return;
            }

            this.indicatorStyle[durationProp] = time + 'ms';

            if ( !time && utils.isBadAndroid ) {
                this.indicatorStyle[durationProp] = '0.0001ms';
                // remove 0.0001ms
                var self = this;
                rAF(function() {
                    if(self.indicatorStyle[durationProp] === '0.0001ms') {
                        self.indicatorStyle[durationProp] = '0s';
                    }
                });
            }
        },

        transitionTimingFunction: function (easing) {
            this.indicatorStyle[utils.style.transitionTimingFunction] = easing;
        },

        refresh: function () {
            this.transitionTime();

            if ( this.options.listenX && !this.options.listenY ) {
                this.indicatorStyle.display = this.scroller.hasHorizontalScroll ? 'block' : 'none';
            } else if ( this.options.listenY && !this.options.listenX ) {
                this.indicatorStyle.display = this.scroller.hasVerticalScroll ? 'block' : 'none';
            } else {
                this.indicatorStyle.display = this.scroller.hasHorizontalScroll || this.scroller.hasVerticalScroll ? 'block' : 'none';
            }

            if ( this.scroller.hasHorizontalScroll && this.scroller.hasVerticalScroll ) {
                utils.addClass(this.wrapper, 'iScrollBothScrollbars');
                utils.removeClass(this.wrapper, 'iScrollLoneScrollbar');

                if ( this.options.defaultScrollbars && this.options.customStyle ) {
                    if ( this.options.listenX ) {
                        this.wrapper.style.right = '8px';
                    } else {
                        this.wrapper.style.bottom = '8px';
                    }
                }
            } else {
                utils.removeClass(this.wrapper, 'iScrollBothScrollbars');
                utils.addClass(this.wrapper, 'iScrollLoneScrollbar');

                if ( this.options.defaultScrollbars && this.options.customStyle ) {
                    if ( this.options.listenX ) {
                        this.wrapper.style.right = '2px';
                    } else {
                        this.wrapper.style.bottom = '2px';
                    }
                }
            }

            utils.getRect(this.wrapper);    // force refresh

            if ( this.options.listenX ) {
                this.wrapperWidth = this.wrapper.clientWidth;
                if ( this.options.resize ) {
                    this.indicatorWidth = Math.max(Math.round(this.wrapperWidth * this.wrapperWidth / (this.scroller.scrollerWidth || this.wrapperWidth || 1)), 8);
                    this.indicatorStyle.width = this.indicatorWidth + 'px';
                } else {
                    this.indicatorWidth = this.indicator.clientWidth;
                }

                this.maxPosX = this.wrapperWidth - this.indicatorWidth;

                if ( this.options.shrink == 'clip' ) {
                    this.minBoundaryX = -this.indicatorWidth + 8;
                    this.maxBoundaryX = this.wrapperWidth - 8;
                } else {
                    this.minBoundaryX = 0;
                    this.maxBoundaryX = this.maxPosX;
                }

                this.sizeRatioX = this.options.speedRatioX || (this.scroller.maxScrollX && (this.maxPosX / this.scroller.maxScrollX));
            }

            if ( this.options.listenY ) {
                this.wrapperHeight = this.wrapper.clientHeight;
                if ( this.options.resize ) {
                    this.indicatorHeight = Math.max(Math.round(this.wrapperHeight * this.wrapperHeight / (this.scroller.scrollerHeight || this.wrapperHeight || 1)), 8);
                    this.indicatorStyle.height = this.indicatorHeight + 'px';
                } else {
                    this.indicatorHeight = this.indicator.clientHeight;
                }

                this.maxPosY = this.wrapperHeight - this.indicatorHeight;

                if ( this.options.shrink == 'clip' ) {
                    this.minBoundaryY = -this.indicatorHeight + 8;
                    this.maxBoundaryY = this.wrapperHeight - 8;
                } else {
                    this.minBoundaryY = 0;
                    this.maxBoundaryY = this.maxPosY;
                }

                this.maxPosY = this.wrapperHeight - this.indicatorHeight;
                this.sizeRatioY = this.options.speedRatioY || (this.scroller.maxScrollY && (this.maxPosY / this.scroller.maxScrollY));
            }

            this.updatePosition();
        },

        updatePosition: function () {
            var x = this.options.listenX && Math.round(this.sizeRatioX * this.scroller.x) || 0,
                y = this.options.listenY && Math.round(this.sizeRatioY * this.scroller.y) || 0;

            if ( !this.options.ignoreBoundaries ) {
                if ( x < this.minBoundaryX ) {
                    if ( this.options.shrink == 'scale' ) {
                        this.width = Math.max(this.indicatorWidth + x, 8);
                        this.indicatorStyle.width = this.width + 'px';
                    }
                    x = this.minBoundaryX;
                } else if ( x > this.maxBoundaryX ) {
                    if ( this.options.shrink == 'scale' ) {
                        this.width = Math.max(this.indicatorWidth - (x - this.maxPosX), 8);
                        this.indicatorStyle.width = this.width + 'px';
                        x = this.maxPosX + this.indicatorWidth - this.width;
                    } else {
                        x = this.maxBoundaryX;
                    }
                } else if ( this.options.shrink == 'scale' && this.width != this.indicatorWidth ) {
                    this.width = this.indicatorWidth;
                    this.indicatorStyle.width = this.width + 'px';
                }

                if ( y < this.minBoundaryY ) {
                    if ( this.options.shrink == 'scale' ) {
                        this.height = Math.max(this.indicatorHeight + y * 3, 8);
                        this.indicatorStyle.height = this.height + 'px';
                    }
                    y = this.minBoundaryY;
                } else if ( y > this.maxBoundaryY ) {
                    if ( this.options.shrink == 'scale' ) {
                        this.height = Math.max(this.indicatorHeight - (y - this.maxPosY) * 3, 8);
                        this.indicatorStyle.height = this.height + 'px';
                        y = this.maxPosY + this.indicatorHeight - this.height;
                    } else {
                        y = this.maxBoundaryY;
                    }
                } else if ( this.options.shrink == 'scale' && this.height != this.indicatorHeight ) {
                    this.height = this.indicatorHeight;
                    this.indicatorStyle.height = this.height + 'px';
                }
            }

            this.x = x;
            this.y = y;

            if ( this.scroller.options.useTransform ) {
                this.indicatorStyle[utils.style.transform] = 'translate(' + x + 'px,' + y + 'px)' + this.scroller.translateZ;
            } else {
                this.indicatorStyle.left = x + 'px';
                this.indicatorStyle.top = y + 'px';
            }
        },

        _pos: function (x, y) {
            if ( x < 0 ) {
                x = 0;
            } else if ( x > this.maxPosX ) {
                x = this.maxPosX;
            }

            if ( y < 0 ) {
                y = 0;
            } else if ( y > this.maxPosY ) {
                y = this.maxPosY;
            }

            x = this.options.listenX ? Math.round(x / this.sizeRatioX) : this.scroller.x;
            y = this.options.listenY ? Math.round(y / this.sizeRatioY) : this.scroller.y;

            this.scroller.scrollTo(x, y);
        },

        fade: function (val, hold) {
            if ( hold && !this.visible ) {
                return;
            }

            clearTimeout(this.fadeTimeout);
            this.fadeTimeout = null;

            var time = val ? 250 : 500,
                delay = val ? 0 : 300;

            val = val ? '1' : '0';

            this.wrapperStyle[utils.style.transitionDuration] = time + 'ms';

            this.fadeTimeout = setTimeout((function (val) {
                this.wrapperStyle.opacity = val;
                this.visible = +val;
            }).bind(this, val), delay);
        }
    };

    IScroll.utils = utils;

    if ( typeof module != 'undefined' && module.exports ) {
        module.exports = IScroll;
    } else if ( typeof define == 'function' && define.amd ) {
        define( function () { return IScroll; } );
    } else {
        window.IScroll = IScroll;
    }

})(window, document, Math);
//以上是滚动Iscroll插件