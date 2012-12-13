/*!
 * jQuery Smooth Scroll Plugin v1.4
 *
 * Date: Mon Apr 25 00:02:30 2011 EDT
 * Requires: jQuery v1.3+
 *
 * Copyright 2010, Karl Swedberg
 * Dual licensed under the MIT and GPL licenses (just like jQuery):
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 *
 *
 *
*/
jQuery(document).ready(function($) {
function totop_button(a){var b=$("#gantry-totop");b.removeClass("off on");if(a=="on"){b.addClass("on")}else{b.addClass("off")}}(function(a){function b(a){return a.replace(/^\//,"").replace(/(index|default).[a-zA-Z]{3,4}$/,"").replace(/\/$/,"")}var c=b(location.pathname),d=function(b){var c=[],d=false,e=b.dir&&b.dir=="left"?"scrollLeft":"scrollTop";this.each(function(){if(!(this==document||this==window)){var b=a(this);if(b[e]()>0)c.push(this);else{b[e](1);d=b[e]()>0;b[e](0);d&&c.push(this)}}});if(b.el==="first"&&c.length)c=[c.shift()];return c};a.fn.extend({scrollable:function(a){return this.pushStack(d.call(this,{dir:a}))},firstScrollable:function(a){return this.pushStack(d.call(this,{el:"first",dir:a}))},smoothScroll:function(d){d=d||{};var e=a.extend({},a.fn.smoothScroll.defaults,d);this.die("click.smoothscroll").live("click.smoothscroll",function(f){var g=a(this),h=location.hostname===this.hostname||!this.hostname,i=e.scrollTarget||(b(this.pathname)||c)===c,j=this.hash,m=true;if(!e.scrollTarget&&(!h||!i||!j))m=false;else{h=e.exclude;i=0;for(var n=h.length;m&&i<n;)if(g.is(h[i++]))m=false;h=e.excludeWithin;i=0;for(n=h.length;m&&i<n;)if(g.closest(h[i++]).length)m=false}if(m){e.scrollTarget=d.scrollTarget||j;e.link=this;f.preventDefault();a.smoothScroll(e)}});return this}});a.smoothScroll=function(b,c){var d,e,f,g=0;e="offset";var h="scrollTop",i={};if(typeof b==="number"){d=a.fn.smoothScroll.defaults;f=b}else{d=a.extend({},a.fn.smoothScroll.defaults,b||{});if(d.scrollElement){e="position";d.scrollElement.css("position")=="static"&&d.scrollElement.css("position","relative")}f=c||a(d.scrollTarget)[e]()&&a(d.scrollTarget)[e]()[d.direction]||0}d=a.extend({link:null},d);h=d.direction=="left"?"scrollLeft":h;if(d.scrollElement){e=d.scrollElement;g=e[h]()}else e=a("html, body").firstScrollable();i[h]=f+g+d.offset;e.animate(i,{duration:d.speed,easing:d.easing,complete:function(){d.afterScroll&&a.isFunction(d.afterScroll)&&d.afterScroll.call(d.link,d)}})};a.smoothScroll.version="1.4";a.fn.smoothScroll.defaults={exclude:[],excludeWithin:[],offset:0,direction:"top",scrollElement:null,scrollTarget:null,afterScroll:null,easing:"swing",speed:400}})(jQuery);jQuery(document).ready(function(a){window.setInterval(function(){var b=a(this).scrollTop();var c=a(this).height();if(b>0){var d=b+c/2}else{var d=1}if(d<1e3){totop_button("off")}else{totop_button("on")}},300);a("#gantry-totop").click(function(b){b.preventDefault();a(this).smoothScroll()})})
});