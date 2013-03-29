/**
* Bootstrap.js by @fat & @mdo
* plugins: bootstrap-transition.js, bootstrap-modal.js, bootstrap-dropdown.js, bootstrap-scrollspy.js, bootstrap-tab.js, bootstrap-tooltip.js, bootstrap-popover.js, bootstrap-affix.js, bootstrap-alert.js, bootstrap-button.js, bootstrap-collapse.js, bootstrap-carousel.js, bootstrap-typeahead.js
* Copyright 2012 Twitter, Inc.
* http://www.apache.org/licenses/LICENSE-2.0.txt
*/
!function(a){a(function(){a.support.transition=function(){var a=function(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"},c;for(c in b)if(a.style[c]!==undefined)return b[c]}();return a&&{end:a}}()})}(window.jQuery),!function(a){var b=function(b,c){this.options=c,this.$element=a(b).delegate('[data-dismiss="modal"]',"click.dismiss.modal",a.proxy(this.hide,this)),this.options.remote&&this.$element.find(".modal-body").load(this.options.remote)};b.prototype={constructor:b,toggle:function(){return this[this.isShown?"hide":"show"]()},show:function(){var b=this,c=a.Event("show");this.$element.trigger(c);if(this.isShown||c.isDefaultPrevented())return;this.isShown=!0,this.escape(),this.backdrop(function(){var c=a.support.transition&&b.$element.hasClass("fade");b.$element.parent().length||b.$element.appendTo(document.body),b.$element.show(),c&&b.$element[0].offsetWidth,b.$element.addClass("in").attr("aria-hidden",!1),b.enforceFocus(),c?b.$element.one(a.support.transition.end,function(){b.$element.focus().trigger("shown")}):b.$element.focus().trigger("shown")})},hide:function(b){b&&b.preventDefault();var c=this;b=a.Event("hide"),this.$element.trigger(b);if(!this.isShown||b.isDefaultPrevented())return;this.isShown=!1,this.escape(),a(document).off("focusin.modal"),this.$element.removeClass("in").attr("aria-hidden",!0),a.support.transition&&this.$element.hasClass("fade")?this.hideWithTransition():this.hideModal()},enforceFocus:function(){var b=this;a(document).on("focusin.modal",function(a){b.$element[0]!==a.target&&!b.$element.has(a.target).length&&b.$element.focus()})},escape:function(){var a=this;this.isShown&&this.options.keyboard?this.$element.on("keyup.dismiss.modal",function(b){b.which==27&&a.hide()}):this.isShown||this.$element.off("keyup.dismiss.modal")},hideWithTransition:function(){var b=this,c=setTimeout(function(){b.$element.off(a.support.transition.end),b.hideModal()},500);this.$element.one(a.support.transition.end,function(){clearTimeout(c),b.hideModal()})},hideModal:function(a){this.$element.hide().trigger("hidden"),this.backdrop()},removeBackdrop:function(){this.$backdrop.remove(),this.$backdrop=null},backdrop:function(b){var c=this,d=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var e=a.support.transition&&d;this.$backdrop=a('<div class="modal-backdrop '+d+'" />').appendTo(document.body),this.$backdrop.click(this.options.backdrop=="static"?a.proxy(this.$element[0].focus,this.$element[0]):a.proxy(this.hide,this)),e&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),e?this.$backdrop.one(a.support.transition.end,b):b()}else!this.isShown&&this.$backdrop?(this.$backdrop.removeClass("in"),a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one(a.support.transition.end,a.proxy(this.removeBackdrop,this)):this.removeBackdrop()):b&&b()}},a.fn.modal=function(c){return this.each(function(){var d=a(this),e=d.data("modal"),f=a.extend({},a.fn.modal.defaults,d.data(),typeof c=="object"&&c);e||d.data("modal",e=new b(this,f)),typeof c=="string"?e[c]():f.show&&e.show()})},a.fn.modal.defaults={backdrop:!0,keyboard:!0,show:!0},a.fn.modal.Constructor=b,a(document).on("click.modal.data-api",'[data-toggle="modal"]',function(b){var c=a(this),d=c.attr("href"),e=a(c.attr("data-target")||d&&d.replace(/.*(?=#[^\s]+$)/,"")),f=e.data("modal")?"toggle":a.extend({remote:!/#/.test(d)&&d},e.data(),c.data());b.preventDefault(),e.modal(f).one("hide",function(){c.focus()})})}(window.jQuery),!function(a){function d(){a(b).each(function(){e(a(this)).removeClass("open")})}function e(b){var c=b.attr("data-target"),d;return c||(c=b.attr("href"),c=c&&/#/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,"")),d=a(c),d.length||(d=b.parent()),d}var b="[data-toggle=dropdown]",c=function(b){var c=a(b).on("click.dropdown.data-api",this.toggle);a("html").on("click.dropdown.data-api",function(){c.parent().removeClass("open")})};c.prototype={constructor:c,toggle:function(b){var c=a(this),f,g;if(c.is(".disabled, :disabled"))return;return f=e(c),g=f.hasClass("open"),d(),g||(f.toggleClass("open"),c.focus()),!1},keydown:function(b){var c,d,f,g,h,i;if(!/(38|40|27)/.test(b.keyCode))return;c=a(this),b.preventDefault(),b.stopPropagation();if(c.is(".disabled, :disabled"))return;g=e(c),h=g.hasClass("open");if(!h||h&&b.keyCode==27)return c.click();d=a("[role=menu] li:not(.divider) a",g);if(!d.length)return;i=d.index(d.filter(":focus")),b.keyCode==38&&i>0&&i--,b.keyCode==40&&i<d.length-1&&i++,~i||(i=0),d.eq(i).focus()}},a.fn.dropdown=function(b){return this.each(function(){var d=a(this),e=d.data("dropdown");e||d.data("dropdown",e=new c(this)),typeof b=="string"&&e[b].call(d)})},a.fn.dropdown.Constructor=c,a(document).on("click.dropdown.data-api touchstart.dropdown.data-api",d).on("click.dropdown touchstart.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.dropdown.data-api touchstart.dropdown.data-api",b,c.prototype.toggle).on("keydown.dropdown.data-api touchstart.dropdown.data-api",b+", [role=menu]",c.prototype.keydown)}(window.jQuery),!function(a){function b(b,c){var d=a.proxy(this.process,this),e=a(b).is("body")?a(window):a(b),f;this.options=a.extend({},a.fn.scrollspy.defaults,c),this.$scrollElement=e.on("scroll.scroll-spy.data-api",d),this.selector=(this.options.target||(f=a(b).attr("href"))&&f.replace(/.*(?=#[^\s]+$)/,"")||"")+" .nav li > a",this.$body=a("body"),this.refresh(),this.process()}b.prototype={constructor:b,refresh:function(){var b=this,c;this.offsets=a([]),this.targets=a([]),c=this.$body.find(this.selector).map(function(){var b=a(this),c=b.data("target")||b.attr("href"),d=/^#\w/.test(c)&&a(c);return d&&d.length&&[[d.position().top,c]]||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){b.offsets.push(this[0]),b.targets.push(this[1])})},process:function(){var a=this.$scrollElement.scrollTop()+this.options.offset,b=this.$scrollElement[0].scrollHeight||this.$body[0].scrollHeight,c=b-this.$scrollElement.height(),d=this.offsets,e=this.targets,f=this.activeTarget,g;if(a>=c)return f!=(g=e.last()[0])&&this.activate(g);for(g=d.length;g--;)f!=e[g]&&a>=d[g]&&(!d[g+1]||a<=d[g+1])&&this.activate(e[g])},activate:function(b){var c,d;this.activeTarget=b,a(this.selector).parent(".active").removeClass("active"),d=this.selector+'[data-target="'+b+'"],'+this.selector+'[href="'+b+'"]',c=a(d).parent("li").addClass("active"),c.parent(".dropdown-menu").length&&(c=c.closest("li.dropdown").addClass("active")),c.trigger("activate")}},a.fn.scrollspy=function(c){return this.each(function(){var d=a(this),e=d.data("scrollspy"),f=typeof c=="object"&&c;e||d.data("scrollspy",e=new b(this,f)),typeof c=="string"&&e[c]()})},a.fn.scrollspy.Constructor=b,a.fn.scrollspy.defaults={offset:10},a(window).on("load",function(){a('[data-spy="scroll"]').each(function(){var b=a(this);b.scrollspy(b.data())})})}(window.jQuery),!function(a){var b=function(b){this.element=a(b)};b.prototype={constructor:b,show:function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.attr("data-target"),e,f,g;d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,""));if(b.parent("li").hasClass("active"))return;e=c.find(".active:last a")[0],g=a.Event("show",{relatedTarget:e}),b.trigger(g);if(g.isDefaultPrevented())return;f=a(d),this.activate(b.parent("li"),c),this.activate(f,f.parent(),function(){b.trigger({type:"shown",relatedTarget:e})})},activate:function(b,c,d){function g(){e.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"),b.addClass("active"),f?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu")&&b.closest("li.dropdown").addClass("active"),d&&d()}var e=c.find("> .active"),f=d&&a.support.transition&&e.hasClass("fade");f?e.one(a.support.transition.end,g):g(),e.removeClass("in")}},a.fn.tab=function(c){return this.each(function(){var d=a(this),e=d.data("tab");e||d.data("tab",e=new b(this)),typeof c=="string"&&e[c]()})},a.fn.tab.Constructor=b,a(document).on("click.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(b){b.preventDefault(),a(this).tab("show")})}(window.jQuery),!function(a){var b=function(a,b){this.init("tooltip",a,b)};b.prototype={constructor:b,init:function(b,c,d){var e,f;this.type=b,this.$element=a(c),this.options=this.getOptions(d),this.enabled=!0,this.options.trigger=="click"?this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this)):this.options.trigger!="manual"&&(e=this.options.trigger=="hover"?"mouseenter":"focus",f=this.options.trigger=="hover"?"mouseleave":"blur",this.$element.on(e+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(f+"."+this.type,this.options.selector,a.proxy(this.leave,this))),this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},getOptions:function(b){return b=a.extend({},a.fn[this.type].defaults,b,this.$element.data()),b.delay&&typeof b.delay=="number"&&(b.delay={show:b.delay,hide:b.delay}),b},enter:function(b){var c=a(b.currentTarget)[this.type](this._options).data(this.type);if(!c.options.delay||!c.options.delay.show)return c.show();clearTimeout(this.timeout),c.hoverState="in",this.timeout=setTimeout(function(){c.hoverState=="in"&&c.show()},c.options.delay.show)},leave:function(b){var c=a(b.currentTarget)[this.type](this._options).data(this.type);this.timeout&&clearTimeout(this.timeout);if(!c.options.delay||!c.options.delay.hide)return c.hide();c.hoverState="out",this.timeout=setTimeout(function(){c.hoverState=="out"&&c.hide()},c.options.delay.hide)},show:function(){var a,b,c,d,e,f,g;if(this.hasContent()&&this.enabled){a=this.tip(),this.setContent(),this.options.animation&&a.addClass("fade"),f=typeof this.options.placement=="function"?this.options.placement.call(this,a[0],this.$element[0]):this.options.placement,b=/in/.test(f),a.detach().css({top:0,left:0,display:"block"}).insertAfter(this.$element),c=this.getPosition(b),d=a[0].offsetWidth,e=a[0].offsetHeight;switch(b?f.split(" ")[1]:f){case"bottom":g={top:c.top+c.height,left:c.left+c.width/2-d/2};break;case"top":g={top:c.top-e,left:c.left+c.width/2-d/2};break;case"left":g={top:c.top+c.height/2-e/2,left:c.left-d};break;case"right":g={top:c.top+c.height/2-e/2,left:c.left+c.width}}a.offset(g).addClass(f).addClass("in")}},setContent:function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},hide:function(){function d(){var b=setTimeout(function(){c.off(a.support.transition.end).detach()},500);c.one(a.support.transition.end,function(){clearTimeout(b),c.detach()})}var b=this,c=this.tip();return c.removeClass("in"),a.support.transition&&this.$tip.hasClass("fade")?d():c.detach(),this},fixTitle:function(){var a=this.$element;(a.attr("title")||typeof a.attr("data-original-title")!="string")&&a.attr("data-original-title",a.attr("title")||"").removeAttr("title")},hasContent:function(){return this.getTitle()},getPosition:function(b){return a.extend({},b?{top:0,left:0}:this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight})},getTitle:function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||(typeof c.title=="function"?c.title.call(b[0]):c.title),a},tip:function(){return this.$tip=this.$tip||a(this.options.template)},validate:function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},enable:function(){this.enabled=!0},disable:function(){this.enabled=!1},toggleEnabled:function(){this.enabled=!this.enabled},toggle:function(b){var c=a(b.currentTarget)[this.type](this._options).data(this.type);c[c.tip().hasClass("in")?"hide":"show"]()},destroy:function(){this.hide().$element.off("."+this.type).removeData(this.type)}},a.fn.tooltip=function(c){return this.each(function(){var d=a(this),e=d.data("tooltip"),f=typeof c=="object"&&c;e||d.data("tooltip",e=new b(this,f)),typeof c=="string"&&e[c]()})},a.fn.tooltip.Constructor=b,a.fn.tooltip.defaults={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover",title:"",delay:0,html:!1}}(window.jQuery),!function(a){var b=function(a,b){this.init("popover",a,b)};b.prototype=a.extend({},a.fn.tooltip.Constructor.prototype,{constructor:b,setContent:function(){var a=this.tip(),b=this.getTitle(),c=this.getContent();a.find(".popover-title")[this.options.html?"html":"text"](b),a.find(".popover-content > *")[this.options.html?"html":"text"](c),a.removeClass("fade top bottom left right in")},hasContent:function(){return this.getTitle()||this.getContent()},getContent:function(){var a,b=this.$element,c=this.options;return a=b.attr("data-content")||(typeof c.content=="function"?c.content.call(b[0]):c.content),a},tip:function(){return this.$tip||(this.$tip=a(this.options.template)),this.$tip},destroy:function(){this.hide().$element.off("."+this.type).removeData(this.type)}}),a.fn.popover=function(c){return this.each(function(){var d=a(this),e=d.data("popover"),f=typeof c=="object"&&c;e||d.data("popover",e=new b(this,f)),typeof c=="string"&&e[c]()})},a.fn.popover.Constructor=b,a.fn.popover.defaults=a.extend({},a.fn.tooltip.defaults,{placement:"right",trigger:"click",content:"",template:'<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'})}(window.jQuery),!function(a){var b=function(b,c){this.options=a.extend({},a.fn.affix.defaults,c),this.$window=a(window).on("scroll.affix.data-api",a.proxy(this.checkPosition,this)).on("click.affix.data-api",a.proxy(function(){setTimeout(a.proxy(this.checkPosition,this),1)},this)),this.$element=a(b),this.checkPosition()};b.prototype.checkPosition=function(){if(!this.$element.is(":visible"))return;var b=a(document).height(),c=this.$window.scrollTop(),d=this.$element.offset(),e=this.options.offset,f=e.bottom,g=e.top,h="affix affix-top affix-bottom",i;typeof e!="object"&&(f=g=e),typeof g=="function"&&(g=e.top()),typeof f=="function"&&(f=e.bottom()),i=this.unpin!=null&&c+this.unpin<=d.top?!1:f!=null&&d.top+this.$element.height()>=b-f?"bottom":g!=null&&c<=g?"top":!1;if(this.affixed===i)return;this.affixed=i,this.unpin=i=="bottom"?d.top-c:null,this.$element.removeClass(h).addClass("affix"+(i?"-"+i:""))},a.fn.affix=function(c){return this.each(function(){var d=a(this),e=d.data("affix"),f=typeof c=="object"&&c;e||d.data("affix",e=new b(this,f)),typeof c=="string"&&e[c]()})},a.fn.affix.Constructor=b,a.fn.affix.defaults={offset:0},a(window).on("load",function(){a('[data-spy="affix"]').each(function(){var b=a(this),c=b.data();c.offset=c.offset||{},c.offsetBottom&&(c.offset.bottom=c.offsetBottom),c.offsetTop&&(c.offset.top=c.offsetTop),b.affix(c)})})}(window.jQuery),!function(a){var b='[data-dismiss="alert"]',c=function(c){a(c).on("click",b,this.close)};c.prototype.close=function(b){function f(){e.trigger("closed").remove()}var c=a(this),d=c.attr("data-target"),e;d||(d=c.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),e=a(d),b&&b.preventDefault(),e.length||(e=c.hasClass("alert")?c:c.parent()),e.trigger(b=a.Event("close"));if(b.isDefaultPrevented())return;e.removeClass("in"),a.support.transition&&e.hasClass("fade")?e.on(a.support.transition.end,f):f()},a.fn.alert=function(b){return this.each(function(){var d=a(this),e=d.data("alert");e||d.data("alert",e=new c(this)),typeof b=="string"&&e[b].call(d)})},a.fn.alert.Constructor=c,a(document).on("click.alert.data-api",b,c.prototype.close)}(window.jQuery),!function(a){var b=function(b,c){this.$element=a(b),this.options=a.extend({},a.fn.button.defaults,c)};b.prototype.setState=function(a){var b="disabled",c=this.$element,d=c.data(),e=c.is("input")?"val":"html";a+="Text",d.resetText||c.data("resetText",c[e]()),c[e](d[a]||this.options[a]),setTimeout(function(){a=="loadingText"?c.addClass(b).attr(b,b):c.removeClass(b).removeAttr(b)},0)},b.prototype.toggle=function(){var a=this.$element.closest('[data-toggle="buttons-radio"]');a&&a.find(".active").removeClass("active"),this.$element.toggleClass("active")},a.fn.button=function(c){return this.each(function(){var d=a(this),e=d.data("button"),f=typeof c=="object"&&c;e||d.data("button",e=new b(this,f)),c=="toggle"?e.toggle():c&&e.setState(c)})},a.fn.button.defaults={loadingText:"loading..."},a.fn.button.Constructor=b,a(document).on("click.button.data-api","[data-toggle^=button]",function(b){var c=a(b.target);c.hasClass("btn")||(c=c.closest(".btn")),c.button("toggle")})}(window.jQuery),!function(a){var b=function(b,c){this.$element=a(b),this.options=a.extend({},a.fn.collapse.defaults,c),this.options.parent&&(this.$parent=a(this.options.parent)),this.options.toggle&&this.toggle()};b.prototype={constructor:b,dimension:function(){var a=this.$element.hasClass("width");return a?"width":"height"},show:function(){var b,c,d,e;if(this.transitioning)return;b=this.dimension(),c=a.camelCase(["scroll",b].join("-")),d=this.$parent&&this.$parent.find("> .accordion-group > .in");if(d&&d.length){e=d.data("collapse");if(e&&e.transitioning)return;d.collapse("hide"),e||d.data("collapse",null)}this.$element[b](0),this.transition("addClass",a.Event("show"),"shown"),a.support.transition&&this.$element[b](this.$element[0][c])},hide:function(){var b;if(this.transitioning)return;b=this.dimension(),this.reset(this.$element[b]()),this.transition("removeClass",a.Event("hide"),"hidden"),this.$element[b](0)},reset:function(a){var b=this.dimension();return this.$element.removeClass("collapse")[b](a||"auto")[0].offsetWidth,this.$element[a!==null?"addClass":"removeClass"]("collapse"),this},transition:function(b,c,d){var e=this,f=function(){c.type=="show"&&e.reset(),e.transitioning=0,e.$element.trigger(d)};this.$element.trigger(c);if(c.isDefaultPrevented())return;this.transitioning=1,this.$element[b]("in"),a.support.transition&&this.$element.hasClass("collapse")?this.$element.one(a.support.transition.end,f):f()},toggle:function(){this[this.$element.hasClass("in")?"hide":"show"]()}},a.fn.collapse=function(c){return this.each(function(){var d=a(this),e=d.data("collapse"),f=typeof c=="object"&&c;e||d.data("collapse",e=new b(this,f)),typeof c=="string"&&e[c]()})},a.fn.collapse.defaults={toggle:!0},a.fn.collapse.Constructor=b,a(document).on("click.collapse.data-api","[data-toggle=collapse]",function(b){var c=a(this),d,e=c.attr("data-target")||b.preventDefault()||(d=c.attr("href"))&&d.replace(/.*(?=#[^\s]+$)/,""),f=a(e).data("collapse")?"toggle":c.data();c[a(e).hasClass("in")?"addClass":"removeClass"]("collapsed"),a(e).collapse(f)})}(window.jQuery),!function(a){var b=function(b,c){this.$element=a(b),this.options=c,this.options.slide&&this.slide(this.options.slide),this.options.pause=="hover"&&this.$element.on("mouseenter",a.proxy(this.pause,this)).on("mouseleave",a.proxy(this.cycle,this))};b.prototype={cycle:function(b){return b||(this.paused=!1),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},to:function(b){var c=this.$element.find(".item.active"),d=c.parent().children(),e=d.index(c),f=this;if(b>d.length-1||b<0)return;return this.sliding?this.$element.one("slid",function(){f.to(b)}):e==b?this.pause().cycle():this.slide(b>e?"next":"prev",a(d[b]))},pause:function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition.end&&(this.$element.trigger(a.support.transition.end),this.cycle()),clearInterval(this.interval),this.interval=null,this},next:function(){if(this.sliding)return;return this.slide("next")},prev:function(){if(this.sliding)return;return this.slide("prev")},slide:function(b,c){var d=this.$element.find(".item.active"),e=c||d[b](),f=this.interval,g=b=="next"?"left":"right",h=b=="next"?"first":"last",i=this,j;this.sliding=!0,f&&this.pause(),e=e.length?e:this.$element.find(".item")[h](),j=a.Event("slide",{relatedTarget:e[0]});if(e.hasClass("active"))return;if(a.support.transition&&this.$element.hasClass("slide")){this.$element.trigger(j);if(j.isDefaultPrevented())return;e.addClass(b),e[0].offsetWidth,d.addClass(g),e.addClass(g),this.$element.one(a.support.transition.end,function(){e.removeClass([b,g].join(" ")).addClass("active"),d.removeClass(["active",g].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger("slid")},0)})}else{this.$element.trigger(j);if(j.isDefaultPrevented())return;d.removeClass("active"),e.addClass("active"),this.sliding=!1,this.$element.trigger("slid")}return f&&this.cycle(),this}},a.fn.carousel=function(c){return this.each(function(){var d=a(this),e=d.data("carousel"),f=a.extend({},a.fn.carousel.defaults,typeof c=="object"&&c),g=typeof c=="string"?c:f.slide;e||d.data("carousel",e=new b(this,f)),typeof c=="number"?e.to(c):g?e[g]():f.interval&&e.cycle()})},a.fn.carousel.defaults={interval:5e3,pause:"hover"},a.fn.carousel.Constructor=b,a(document).on("click.carousel.data-api","[data-slide]",function(b){var c=a(this),d,e=a(c.attr("data-target")||(d=c.attr("href"))&&d.replace(/.*(?=#[^\s]+$)/,"")),f=a.extend({},e.data(),c.data());e.carousel(f),b.preventDefault()})}(window.jQuery),!function(a){var b=function(b,c){this.$element=a(b),this.options=a.extend({},a.fn.typeahead.defaults,c),this.matcher=this.options.matcher||this.matcher,this.sorter=this.options.sorter||this.sorter,this.highlighter=this.options.highlighter||this.highlighter,this.updater=this.options.updater||this.updater,this.$menu=a(this.options.menu).appendTo("body"),this.source=this.options.source,this.shown=!1,this.listen()};b.prototype={constructor:b,select:function(){var a=this.$menu.find(".active").attr("data-value");return this.$element.val(this.updater(a)).change(),this.hide()},updater:function(a){return a},show:function(){var b=a.extend({},this.$element.offset(),{height:this.$element[0].offsetHeight});return this.$menu.css({top:b.top+b.height,left:b.left}),this.$menu.show(),this.shown=!0,this},hide:function(){return this.$menu.hide(),this.shown=!1,this},lookup:function(b){var c;return this.query=this.$element.val(),!this.query||this.query.length<this.options.minLength?this.shown?this.hide():this:(c=a.isFunction(this.source)?this.source(this.query,a.proxy(this.process,this)):this.source,c?this.process(c):this)},process:function(b){var c=this;return b=a.grep(b,function(a){return c.matcher(a)}),b=this.sorter(b),b.length?this.render(b.slice(0,this.options.items)).show():this.shown?this.hide():this},matcher:function(a){return~a.toLowerCase().indexOf(this.query.toLowerCase())},sorter:function(a){var b=[],c=[],d=[],e;while(e=a.shift())e.toLowerCase().indexOf(this.query.toLowerCase())?~e.indexOf(this.query)?c.push(e):d.push(e):b.push(e);return b.concat(c,d)},highlighter:function(a){var b=this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&");return a.replace(new RegExp("("+b+")","ig"),function(a,b){return"<strong>"+b+"</strong>"})},render:function(b){var c=this;return b=a(b).map(function(b,d){return b=a(c.options.item).attr("data-value",d),b.find("a").html(c.highlighter(d)),b[0]}),b.first().addClass("active"),this.$menu.html(b),this},next:function(b){var c=this.$menu.find(".active").removeClass("active"),d=c.next();d.length||(d=a(this.$menu.find("li")[0])),d.addClass("active")},prev:function(a){var b=this.$menu.find(".active").removeClass("active"),c=b.prev();c.length||(c=this.$menu.find("li").last()),c.addClass("active")},listen:function(){this.$element.on("blur",a.proxy(this.blur,this)).on("keypress",a.proxy(this.keypress,this)).on("keyup",a.proxy(this.keyup,this)),this.eventSupported("keydown")&&this.$element.on("keydown",a.proxy(this.keydown,this)),this.$menu.on("click",a.proxy(this.click,this)).on("mouseenter","li",a.proxy(this.mouseenter,this))},eventSupported:function(a){var b=a in this.$element;return b||(this.$element.setAttribute(a,"return;"),b=typeof this.$element[a]=="function"),b},move:function(a){if(!this.shown)return;switch(a.keyCode){case 9:case 13:case 27:a.preventDefault();break;case 38:a.preventDefault(),this.prev();break;case 40:a.preventDefault(),this.next()}a.stopPropagation()},keydown:function(b){this.suppressKeyPressRepeat=!~a.inArray(b.keyCode,[40,38,9,13,27]),this.move(b)},keypress:function(a){if(this.suppressKeyPressRepeat)return;this.move(a)},keyup:function(a){switch(a.keyCode){case 40:case 38:case 16:case 17:case 18:break;case 9:case 13:if(!this.shown)return;this.select();break;case 27:if(!this.shown)return;this.hide();break;default:this.lookup()}a.stopPropagation(),a.preventDefault()},blur:function(a){var b=this;setTimeout(function(){b.hide()},150)},click:function(a){a.stopPropagation(),a.preventDefault(),this.select()},mouseenter:function(b){this.$menu.find(".active").removeClass("active"),a(b.currentTarget).addClass("active")}},a.fn.typeahead=function(c){return this.each(function(){var d=a(this),e=d.data("typeahead"),f=typeof c=="object"&&c;e||d.data("typeahead",e=new b(this,f)),typeof c=="string"&&e[c]()})},a.fn.typeahead.defaults={source:[],items:8,menu:'<ul class="typeahead dropdown-menu"></ul>',item:'<li><a href="#"></a></li>',minLength:1},a.fn.typeahead.Constructor=b,a(document).on("focus.typeahead.data-api",'[data-provide="typeahead"]',function(b){var c=a(this);if(c.data("typeahead"))return;b.preventDefault(),c.typeahead(c.data())})}(window.jQuery);/*
    json2.js
    2012-10-08

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html


    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.


    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the value

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.
*/

/*jslint evil: true, regexp: true */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/


// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

if (typeof JSON !== 'object') {
    JSON = {};
}

(function () {
    'use strict';

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf())
                ? this.getUTCFullYear()     + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())      + 'T' +
                    f(this.getUTCHours())     + ':' +
                    f(this.getUTCMinutes())   + ':' +
                    f(this.getUTCSeconds())   + 'Z'
                : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function (key) {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string'
                ? c
                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0
                    ? '[]'
                    : gap
                    ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                    : '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0
                ? '{}'
                : gap
                ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
                : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function'
                    ? walk({'': j}, '')
                    : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());
if (window.top)
	window.top.location.href = window.location.href;

var CDMobile = {};

CDMobile.urlValidate = function(url) {
	var pattern = /http:\/\/[\w-]*(\.[\w-]*)+/ig;
	return pattern.test(url);
};
CDMobile.emailValidate = function(email) {
	var pattern = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/ig;
	return pattern.test(email);
};
CDMobile.shareToWeixinFriend = function(event){
	if (typeof WeixinJSBridge == 'undefined') {
		return false;
	}
	else {
		var imgurl = $(this).attr('data-image');
		var title = $(this).attr('data-title');
		var desc = $(this).attr('data-desc');
		WeixinJSBridge.invoke('shareTimeline', {
			'img_url': imgurl || '',
			'link': location.href,
			'desc': desc,
			'title': title
		}, function(res) {
			// 返回res.err_msg,取值
			// share_timeline:cancel 用户取消
			// share_timeline:fail　发送失败
			// share_timeline:ok 发送成功
			WeixinJSBridge.log(res.err_msg);
		});
	}
	return false;
};

CDMobile.increaseVisitNums = function(id, url) {
	if (id <= 0 || url.length == 0) return false;
	var data = 'id=' + id;
	var jqXhr = $.post(url, data, undefined, 'json');
	jqXhr.done(function(data){
		$('.beta-post-detail .beta-visit-nums').text(data);
	});
};

CDMobile.switchImageSize = function(event){
    event.preventDefault();
    _hmt && _hmt.push(['_trackEvent', '图片', '缩略图与大图切换点击']);
    
    var itemDiv = $(this).parents('.post-item');
    itemDiv.find('.post-image .thumbnail-more').toggle();
    itemDiv.find('.post-image .thumb a .thumb').toggle();
    itemDiv.find('.post-image .thumb-pall').toggle();
    var originalUrl = itemDiv.find('.post-image .thumb a').attr('href');
    itemDiv.find('.post-image .thumb a .original').attr('src', originalUrl).toggle();
    var itemPos = itemDiv.position();
    $('body').scrollTop(parseInt(itemPos.top) - 55);
};

CDMobile.ratingPost = function(event){
	event.preventDefault();
	
	_hmt && _hmt.push(['_trackEvent', '文章评价', '点击按钮']);
	var tthis = $(this);
	var itemDiv = tthis.parents('.post-box');
	var pid = tthis.attr('data-id');
	var score = tthis.attr('data-score');
	var url = tthis.attr('data-url');
	
	var jqXhr = $.ajax({
		type: 'POST',
		url: url,
		data: {pid: pid, score: score},
		dataType: 'json',
		beforeSend: function(){
			tthis.toggleClass('voted');
		}
	});
	
	jqXhr.done(function(data){
		if (data.errno == 0) {
			var old = parseInt(tthis.text());
			var newScore = 0;
			if (tthis.hasClass('upscore'))
				newScore = old + 1;
			else if (tthis.hasClass('downscore'))
				newScore = old - 1;
				
			tthis.text(newScore);
			itemDiv.find('a.upscore, a.downscore').addClass('disabled');
			itemDiv.find('.item-toolbar').off('click', 'a.upscore, a.downscore');
		}
		else {
			alert('评价出错');
			tthis.toggleClass('voted');
		}
	});
	
	jqXhr.fail(function(){
		alert('x');
		tthis.toggleClass('voted');
	});
};

CDMobile.showShareBox = function(event) {
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '分享列表', '显示']);
	
	var bdshare = $(this).parents('.item-toolbar').find('#bdshare');
	if (bdshare.attr('data').length == 0) {
		var item = $(this).parents('.post-box');
		var bddata = {
			"url": item.find('.item-title a').attr('href'),
			"text": '转自@挖段子网：' + $.trim(item.find('.item-title').text()),
			"pic": item.find('.post-image .thumb a').attr('href'),
		};
		bdshare.attr('data', JSON.stringify(bddata));
	}
	$(this).parents('.item-toolbar').find('.sharebox:hidden').stop(true, true).delay(50).show();
};

CDMobile.hideShareBox = function(event) {
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '分享列表', '隐藏']);
	
	$(this).parents('.item-toolbar').find('.sharebox:visible').stop(true, true).delay(50).hide();
};

CDMobile.fetchComments = function(event) {
	event.preventDefault();
	var tthis = $(this);
	var commentBlock = tthis.parents('.item-toolbar').next('.comment-list');
	if (commentBlock.filter(':visible').length > 0) {
		commentBlock.hide();
		_hmt && _hmt.push(['_trackEvent', '评论', '隐藏评论列表']);
		return true;
	}
	else if (commentBlock.html().length > 0) {
		commentBlock.show();
		_hmt && _hmt.push(['_trackEvent', '评论', '查看评论列表']);
		return true;
	}
	_hmt && _hmt.push(['_trackEvent', '评论', '查看评论列表']);
	
	var url = tthis.attr('data-url');
	var commentCount = tthis.text();
	var jqXhr = $.ajax({
		url: url,
		dataType: 'json',
		type: 'get',
		cache: true,
		beforeSend: function(jqXHR, settings){
			tthis.text('...');
		}
	});
	
	jqXhr.done(function(data, textStatus, jqXHR){
		commentBlock.html(data.html).show();
		commentBlock.find('textarea').focus();
	});
	
	jqXhr.fail(function(jqXHR, textStatus, errorThrown){
		alert('fail');
	});
	
	jqXhr.always(function(){
		tthis.text(commentCount);
	});
};

CDMobile.RatingComment = function(event){
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '评论评价按钮', '点击']);
	
	var tthis = $(this);
	var pid = parseInt(tthis.attr('data-id'));
	var score = parseInt(tthis.attr('data-value'));
	var scoreEl = tthis.parents('.comment-item').find('.comment-score');
	var url = tthis.attr('data-url');

	var this_pushed = tthis.hasClass('pushed');
	var up_pushed = tthis.parent().find('a.arrow-up').hasClass('pushed');
	var down_pushed = tthis.parent().find('a.arrow-down').hasClass('pushed');
	var score_step = 0;
	if (up_pushed || down_pushed) {
		if (this_pushed) {
			tthis.toggleClass('pushed');
			score_step = (score > 0) ? -1 : 1;
		}
		else {
			tthis.parent().find('a').toggleClass('pushed');
			score_step = (score > 0) ? 2 : -2;
		}
	}
	else {
		tthis.toggleClass('pushed');
		if (score > 0)
			score_step = 1;
		else {
			score_step = -1;
		}
	}

	// 此处先更新页面数字，不管成功与否
	scoreEl.text(parseInt(scoreEl.text()) + score_step);

	var xhr = $.ajax({
		url: url,
		type: 'POST',
		dataType: 'json',
		data: {id:pid, score:score}
	});
	xhr.done(function(data){
		if (parseInt(data.errno) == 0) {
			// success
		}
	});
};

CDMobile.PostComment = function(event) {
	event.preventDefault();
	var form = $(this).parents('form');
	var contentElement = form.find('.comment-content');
	var errorElement = form.next('.caption-error');
	var loadingElement = form.find('.save-caption-loader');
	var placeholder = contentElement.attr('data-placeholder');
	var content = $.trim(contentElement.val());
	
	var postid = parseInt(form.find('input[name=postid]').val());
	if (postid <= 0 || content.length <= 0 || content == placeholder)
		return false;

	var xhr = $.ajax({
		url: form.attr('action'),
		type: 'POST',
		dataType: 'json',
		data: form.serialize(),
		beforeSend: function(){
			errorElement.empty().hide();
			loadingElement.show();
		}
	});
	xhr.done(function(data){
		loadingElement.hide();
		if (data.errno == 0) {
			errorElement.after(data.html);
			contentElement.val('').removeClass('expand');
			_hmt && _hmt.push(['_trackEvent', '评论', '评论成功']);
		}
		else {
			errorElement.html(data.error).show();
			_hmt && _hmt.push(['_trackEvent', '评论', '评论失败']);
		}
	});
	xhr.fail(function(){
		loadingElement.hide();
		errorElement.html('发送请求错误！').show();
		_hmt && _hmt.push(['_trackEvent', '评论', '评论失败']);
	});
};







var BetaPost = {
	digg: function(event) {
		event.preventDefault();
		var tthis = $(this);
		var postid = parseInt(tthis.attr('data-id'));
		var url = tthis.attr('data-url');
		
		var digg_cookie_name = 'beta_digg';
		var _cookies = JSON.parse($.cookie(digg_cookie_name));
		if (!$.isArray(_cookies)) _cookies = [];
		if ($.inArray(postid, _cookies) > -1) return false;
		
		var jqXhr = $.ajax({
			type: 'post',
			url: url,
			data: {pid: postid},
			dataType: 'json',
		});
		
		jqXhr.done(function(data){
			if (data.errno != 0) {
				tthis.find('.digg-count').text(data.digg_nums);
				_cookies.push(postid);
				$.unique(_cookies);
				$.cookie(digg_cookie_name, JSON.stringify(_cookies), {expires:7, path:'/'});
			}
		});
		
		$(document).off('click', '#beta-digg-button');
	}
};

var BetaComment = {
	create: function(event){
		event.preventDefault();
		var form = $(this);
		var msg = form.next('.beta-alert-message');
		if (msg.length == 0)
			msg = $('#beta-create-message').clone().removeAttr('id');
		var msgtext = msg.find('.text');
		form.after(msg);

		var captchaEl = form.find('.beta-captcha');
		if (captchaEl.length > 0)
			form.find('input.beta-captcha').blur();

		if (form.find('.help-block.error').length > 0) {
			msgtext.html('请输入评论内容和验证码后再发布');
			msg.removeClass('alert-success').addClass('alert-error').show();
			return false;
		}
		else {
			var contentEl = form.find('.comment-content');
			var content = $.trim(contentEl.val());
			var minlen = parseInt(contentEl.attr('minlen'));
			minlen = (isNaN(minlen) || minlen == 0) ? 5 : minlen;
			if (content.length < minlen) {
				msgtext.html('请输入评论内容和验证码后再发布');
				msg.removeClass('alert-success').addClass('alert-error').show();
				contentEl.focus();
				return false;
			}
			if (captchaEl.length > 0) {
				var captcha = $.trim(captchaEl.val());
				if (captcha.length != 4) {
					msgtext.html('请输入评论内容和验证码后再发布');
					msg.removeClass('alert-success').addClass('alert-error').show();
					captchaEl.focus();
					return false;
				}
			}
		}

		var button = form.find('button[type=submit]');
		var jqXhr = $.ajax({
			type: 'post',
			url: form.attr('action'),
			data: form.serialize(),
			dataType: 'json',
			cache: false,
			beforeSend: function(jqXhr){
				button.button('loading');
				msgtext.html('发送数据中...');
				msg.removeClass('alert-error').addClass('alert-success').show();
			}
		});
		jqXhr.done(function(data){
			msgtext.html(data.text);
			if (data.errno == 0) {
				form.find(':text, textarea').val('');
				form.find('.beta-control-group').removeClass('success error');
				form.find('.beta-control-group').removeClass('alert-success alert-error');
				msg.removeClass('alert-error').addClass('alert-success').show();
				var lastComment = $('.beta-comment-item:last');
				if (lastComment.length == 0)
					lastComment = $('#beta-comment-list');
				lastComment.after(data.html);
				if (form.attr('id') == undefined) form.remove();
				form.find('.comment-content').addClass('mini');
				$('form .comment-captcha').hide();
				$('.beta-no-comments').remove();
			}
			else {
				msg.removeClass('alert-success').addClass('alert-error').show();
				form.find('.refresh-captcha').trigger('click');
			}
		});
		jqXhr.fail(function(event, jqXHR, ajaxSettings, thrownError){
			jqXhr.abort();
			msgtext.html('请求错误.');
			msg.removeClass('alert-success').addClass('alert-error').show();
		});
		jqXhr.always(function(){
			button.button('reset');
		});
		return false;
	},
	reply: function() {
		var form = $(this).parents('.beta-comment-item').next('form');
		if (form.length == 0) {
			form = $('#comment-form').clone().removeAttr('id').addClass('comment-reply-form').attr('action', $(this).attr('data-url'));;
			form.find('.comment-captcha').hide();
			form.find(':text, textarea').val('');
			$(this).parents('.beta-comment-item').after(form);
//			form.find('.comment-content').focus();
			form.find('.beta-control-group').removeClass('error success');
		}
		else if (form.filter(':visible').length == 0) {
			form.show();
//			form.find('.comment-content').focus();
		}
		else
			form.hide();

		form.next('.beta-alert-message:visible').hide();
	},
	rating: function(event) {
		event.preventDefault();
		var tthis = $(this);
		var commentItem = tthis.parents('.beta-comment-item');
		var msg = commentItem.next('.beta-alert-message');
		if (msg.length == 0)
			msg = $('#beta-comment-message').clone().removeAttr('id');
		var msgtext = msg.find('.text');

		if (tthis.attr('data-clicked')) {
			msgtext.html('您已经参与过了，谢谢');
			if (msg.hasClass('alert-success')) msg.removeClass('alert-success');
			if (!msg.hasClass('alert-error')) msg.addClass('alert-error');
			commentItem.after(msg);
			msg.show();
			return false;
		}

		var url = tthis.attr('data-url');
		var jqXhr = $.ajax({
			url: url,
			dataType: 'json',
			type: 'post',
			cache: false,
			beforeSend: function(jqXhr) {
				msgtext.html('发送数据中...');
				msg.removeClass('alert-error').addClass('alert-success');
				commentItem.after(msg);
				msg.show();
			}
		});
		jqXhr.done(function(data){
			if (data.errno == 0) {
				var jqnums = tthis.find('.beta-comment-join-nums');
				jqnums.text(parseInt(jqnums.text()) + 1);
				tthis.attr('data-clicked', 1);
				msgtext.html(data.text);
				if (msg.hasClass('alert-error')) msg.removeClass('alert-error');
				if (!msg.hasClass('alert-success')) msg.addClass('alert-success');
			}
			else {
				if (msg.hasClass('alert-success')) msg.removeClass('alert-success');
				if (!msg.hasClass('alert-error')) msg.addClass('alert-error');
			}
			commentItem.after(msg);
			msg.show();
		});
		jqXhr.fail(function(event, jqXHR, ajaxSettings, thrownError){
			msgtext.html('请求错误.');
			if (msg.hasClass('alert-success')) msg.removeClass('alert-success');
			if (!msg.hasClass('alert-error')) msg.addClass('alert-error');
			commentItem.after(msg);
			msg.show();
		});
	},
	captchaValidate: function(event){
		var tthis = $(this);
		var captcha = $.trim(tthis.val());
		var help = tthis.parents('.beta-controls').find('.beta-help-inline');
		var helperror = tthis.parents('.beta-controls').find('.beta-help-error');
		var group = tthis.parents('.beta-control-group');

		help.hide();
		if (captcha.length == 4) {
			group.removeClass('error').addClass('success');
			return true;
		}
		else {
			helperror.show();
			group.removeClass('success').addClass('error');
			return false;
		}
	},
	contentValidate: function(event) {
		var tthis = $(this);
		var content = $.trim(tthis.val());
		var minlen = parseInt(tthis.attr('minlen'));
		var help = tthis.parents('form').find('.help-block');
		minlen = (isNaN(minlen) || minlen == 0) ? 5 : minlen;
		if (content.length > minlen) {
			help.removeClass('error').addClass('success');
			return true;
		}
		else {
			help.removeClass('success').addClass('error');
			if (content.length == 0) tthis.addClass('mini');
			return false;
		}
	},
	showCaptcha: function(event) {
		var tthis = $(this);
		tthis.removeClass('mini');
		var captchaRow = tthis.parents('form').find('.comment-captcha:hidden');
		if (captchaRow.length == 0) return false;

		var captchaEl = tthis.parents('form').find('img.beta-captcha-img');
		captchaEl.attr('src', captchaEl.attr('lazy-src'));
		captchaEl.trigger('click');
		captchaRow.fadeIn('fast');
	},
	refreshCaptcha: function(event) {
		event.preventDefault();
		var url = $(this).parents('.beta-controls').find('.refresh-captcha').attr('href');
		var jqXhr = $.ajax({
			url: url,
			dataType: 'json',
			cache: false
		});
		jqXhr.done(function(data){
			$('.beta-captcha-img').attr('src', data['url']);
			$('body').data('captcha.hash', [data['hash1'], data['hash2']]);
		});
		return false;
	},
	loadMore: function(event){
		var tthis = $(this);
		var page = parseInt(tthis.attr('data-page')) + 1;
		var url = tthis.attr('data-url');
		var data = {page: page};
		
		var jqXhr = $.ajax({
			url: url,
			dataType: 'json',
			type: 'get',
			data: data,
			cache: false,
			beforeSend: function(jqXhr) {
				tthis.button('loading');
			}
		});
		
		jqXhr.done(function(data){
			if (data.errno == 0) {
				var lastComment = $('.beta-comment-item:last');
				if (lastComment.length == 0)
					lastComment = $('#beta-comment-list');
				lastComment.after(data.html);
				
				if (data.html.length > 0)
					tthis.attr('data-page', page);
			}
			else
				alert('Load more comments error');
		});
		
		jqXhr.always(function(){
			tthis.button('reset');
		});
	}
};


CDMobile.addContact = function(wxid, cb) { 
	if (typeof WeixinJSBridge == 'undefined') return false;
	WeixinJSBridge.invoke('addContact', {
		webtype: '1',
		username: wxid
	}, function(d) {
		// 返回d.err_msg取值，d还有一个属性是err_desc
		// add_contact:cancel 用户取消
		// add_contact:fail　关注失败
		// add_contact:ok 关注成功
		// add_contact:added 已经关注
		WeixinJSBridge.log(d.err_msg);
		cb && cb(d.err_msg);
	});
};





