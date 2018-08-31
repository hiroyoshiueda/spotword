var SR_NEXT = '次へ';
var SR_PREV = '前へ';
var SR_NEW_READ = '別画面で読む';

var SR_FONT_FAMILY = '"Hiragino Kaku Gothic Pro","ヒラギノ角ゴ Pro W3","Meiryo","メイリオ","Osaka","MS Gothic",arial,helvetica,clean,sans-serif';
var SR_KINSOKU_BEGIN = '、。，．・：；！？」』）｝〕］】》!?%)]},.:;';
var SR_KINSOKU_END = '「『（｛〔［《【([{';
var SR_CONTAINER_XML_ERR = 'container.xmlの解析に失敗しました。';
var SR_NOW_LOADING = '読み込み中...';

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 *
 * Open source under the BSD License.
 *
 * Copyright Â© 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
*/

jQuery.easing['jswing']=jQuery.easing['swing'];jQuery.extend(jQuery.easing,{def:'easeOutQuad',swing:function(x,t,b,c,d){return jQuery.easing[jQuery.easing.def](x,t,b,c,d)},easeInQuad:function(x,t,b,c,d){return c*(t/=d)*t+b},easeOutQuad:function(x,t,b,c,d){return-c*(t/=d)*(t-2)+b},easeInOutQuad:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t+b;return-c/2*((--t)*(t-2)-1)+b},easeInCubic:function(x,t,b,c,d){return c*(t/=d)*t*t+b},easeOutCubic:function(x,t,b,c,d){return c*((t=t/d-1)*t*t+1)+b},easeInOutCubic:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t+b;return c/2*((t-=2)*t*t+2)+b},easeInQuart:function(x,t,b,c,d){return c*(t/=d)*t*t*t+b},easeOutQuart:function(x,t,b,c,d){return-c*((t=t/d-1)*t*t*t-1)+b},easeInOutQuart:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t+b;return-c/2*((t-=2)*t*t*t-2)+b},easeInQuint:function(x,t,b,c,d){return c*(t/=d)*t*t*t*t+b},easeOutQuint:function(x,t,b,c,d){return c*((t=t/d-1)*t*t*t*t+1)+b},easeInOutQuint:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t*t+b;return c/2*((t-=2)*t*t*t*t+2)+b},easeInSine:function(x,t,b,c,d){return-c*Math.cos(t/d*(Math.PI/2))+c+b},easeOutSine:function(x,t,b,c,d){return c*Math.sin(t/d*(Math.PI/2))+b},easeInOutSine:function(x,t,b,c,d){return-c/2*(Math.cos(Math.PI*t/d)-1)+b},easeInExpo:function(x,t,b,c,d){return(t==0)?b:c*Math.pow(2,10*(t/d-1))+b},easeOutExpo:function(x,t,b,c,d){return(t==d)?b+c:c*(-Math.pow(2,-10*t/d)+1)+b},easeInOutExpo:function(x,t,b,c,d){if(t==0)return b;if(t==d)return b+c;if((t/=d/2)<1)return c/2*Math.pow(2,10*(t-1))+b;return c/2*(-Math.pow(2,-10*--t)+2)+b},easeInCirc:function(x,t,b,c,d){return-c*(Math.sqrt(1-(t/=d)*t)-1)+b},easeOutCirc:function(x,t,b,c,d){return c*Math.sqrt(1-(t=t/d-1)*t)+b},easeInOutCirc:function(x,t,b,c,d){if((t/=d/2)<1)return-c/2*(Math.sqrt(1-t*t)-1)+b;return c/2*(Math.sqrt(1-(t-=2)*t)+1)+b},easeInElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);return-(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b},easeOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);return a*Math.pow(2,-10*t)*Math.sin((t*d-s)*(2*Math.PI)/p)+c+b},easeInOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d/2)==2)return b+c;if(!p)p=d*(.3*1.5);if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);if(t<1)return-.5*(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;return a*Math.pow(2,-10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p)*.5+c+b},easeInBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;return c*(t/=d)*t*((s+1)*t-s)+b},easeOutBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;return c*((t=t/d-1)*t*((s+1)*t+s)+1)+b},easeInOutBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;if((t/=d/2)<1)return c/2*(t*t*(((s*=(1.525))+1)*t-s))+b;return c/2*((t-=2)*t*(((s*=(1.525))+1)*t+s)+2)+b},easeInBounce:function(x,t,b,c,d){return c-jQuery.easing.easeOutBounce(x,d-t,0,c,d)+b},easeOutBounce:function(x,t,b,c,d){if((t/=d)<(1/2.75)){return c*(7.5625*t*t)+b}else if(t<(2/2.75)){return c*(7.5625*(t-=(1.5/2.75))*t+.75)+b}else if(t<(2.5/2.75)){return c*(7.5625*(t-=(2.25/2.75))*t+.9375)+b}else{return c*(7.5625*(t-=(2.625/2.75))*t+.984375)+b}},easeInOutBounce:function(x,t,b,c,d){if(t<d/2)return jQuery.easing.easeInBounce(x,t*2,0,c,d)*.5+b;return jQuery.easing.easeOutBounce(x,t*2-d,0,c,d)*.5+c*.5+b}});

/*
 * jQuery Booklet Plugin
 * Copyright (c) 2010 W. Grauvogel (http://builtbywill.com/)
 *
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version : 1.1.0
 *
 * Originally based on the work of:
 *	1) Charles Mangin (http://clickheredammit.com/pageflip/)
 */
(function($){$.fn.booklet=function(options){var o=$.extend({},$.fn.booklet.defaults,options);return $(this).each(function(){var command,config,obj,id,i,target;if(typeof options=="string"){if($(this).data("booklet")){command=options.toLowerCase();obj=$.fn.booklet.interfaces[$(this).data("id")];if(command=="next")obj.next();else command=="prev"&&obj.prev()}}else if(typeof options=="number"){if($(this).data("booklet")){target=options;obj=$.fn.booklet.interfaces[$(this).data("id")];if(target%2!=0)target-=1;obj.gotoPage(target)}}else{config=$.extend(true,{},o);id=$.fn.booklet.interfaces.length;for(i=0;i<id;i++)if(typeof $.fn.booklet.interfaces[i]=="undefined"){id=i;break}obj=new booklet($(this),config,id);$.fn.booklet.interfaces[id]=obj}})};function booklet(target,options,id){var self,opts,b,src,hash,i,j,p,diff,busy,init,rhover,lhover,titles=[],chapters=[],pN,p0,p1,p2,p3,p4,pNwrap,p0wrap,p1wrap,p2wrap,p3wrap,p4wrap,wraps,sF,sB,overlays,overlayN,overlayP,overlayC,tabs,tabN,tabP,arrows,arrowN,arrowP,next,prev,ctrlsN,ctrlsP,firstBtn,lastBtn,menu,chapter,dd,ddUL,ddH,ddLI,ddA,ddT,ddC,ddCUL,ddCH,ddCLI,ddCA,ddCT,empty='<div class="b-page-empty" title="" rel=""></div>',blank='<div class="b-page-blank" title="" rel=""></div>';busy=false;init=false;rhover=lhover=false;self=this;self.options=options;self.id=id;self.hash="";opts=self.options;b=target.addClass("booklet");src=b.children(".b-load");initPages();b.data("booklet",true);b.data("id",id);b.data("total",src.children().length);if(!opts.width)opts.width=b.width();if(!opts.height)opts.height=b.height();b.width(opts.width);b.height(opts.height);opts.pWidth=opts.width/2;opts.pWidthN="-"+opts.width/2+"px";opts.pWidthH=opts.width/4;opts.pHeight=opts.height;opts.pTotal=src.children().length;opts.speedH=opts.speed/2;if(opts.direction=="LTR")opts.curr=0;else if(opts.direction=="RTL")opts.curr=opts.pTotal-2;if(!isNaN(opts.startingPage)&&opts.startingPage<=opts.pTotal&&opts.startingPage>0){if(opts.startingPage%2!=0)opts.startingPage--;opts.curr=opts.startingPage}if(opts.name)document.title=opts.name;else opts.name=document.title;if(opts.shadows){opts.shadowTopFwdWidth="-"+opts.shadowTopFwdWidth+"px";opts.shadowTopBackWidth="-"+opts.shadowTopBackWidth+"px"}if(opts.menu){menu=$(opts.menu).addClass("b-menu");p=opts.curr;if(opts.pageSelector){dd=$('<div class="b-selector b-selector-page"><span class="b-current">'+(p+1)+" - "+(p+2)+"</span></div>").appendTo(menu);ddUL=$("<ul></ul>").appendTo(dd).empty().css("height","auto");for(i=0;i<opts.pTotal;i+=2){j=i;nums=j+1+"-"+(j+2);if(opts.closed){j--;if(i==0)nums="1";else if(i==opts.pTotal-2)nums=opts.pTotal-2;else nums=j+1+"-"+(j+2);if(opts.covers){j--;if(i==0)nums="";else if(i==opts.pTotal-2)nums="";else nums=j+1+"-"+(j+2)}}if(opts.direction=="RTL"){nums=Math.abs(j-opts.pTotal)-1+" - "+Math.abs(j-opts.pTotal);if(opts.closed){if(i==opts.pTotal-2)nums="1";else if(i==0)nums=opts.pTotal-2;else nums=Math.abs(j-opts.pTotal)-3+" - "+(Math.abs(j-opts.pTotal)-2);if(opts.covers)if(i==opts.pTotal-2)nums="";else if(i==0)nums="";else nums=Math.abs(j-opts.pTotal)-5+" - "+(Math.abs(j-opts.pTotal)-4)}dd.find(".b-current").text(nums);ddLI=$('<li><a href="#/page/'+(i+1)+'" id="selector-page-'+i+'"><span class="b-text">'+titles[i+1]+'</span><span class="b-num">'+nums+"</span></a></li>").prependTo(ddUL)}else{i==0&&dd.find(".b-current").text(nums);ddLI=$('<li><a href="#/page/'+(i+1)+'" id="selector-page-'+i+'"><span class="b-text">'+titles[i]+'</span><span class="b-num">'+nums+"</span></a></li>").appendTo(ddUL)}ddA=ddLI.find("a");!opts.hash&&ddA.click(function(){opts.direction=="RTL"&&dd.find(".b-current").text($(this).find(".b-num").text());ddT=parseInt($(this).attr("id").replace("selector-page-",""));self.gotoPage(ddT);return false})}ddH=ddUL.height();ddUL.css({height:0,"padding-bottom":0});dd.unbind("hover").hover(function(){ddUL.stop().animate({height:ddH,paddingBottom:10},500)},function(){ddUL.stop().animate({height:0,paddingBottom:0},500)})}if(opts.chapterSelector){chapter=chapters[opts.curr];if(chapter=="")chapter=chapters[opts.curr+1];ddC=$('<div class="b-selector b-selector-chapter"><span class="b-current">'+chapter+"</span></div>").appendTo(menu);ddCUL=$("<ul></ul>").appendTo(ddC).empty().css("height","auto");for(i=0;i<opts.pTotal;i+=1)if(chapters[i]!=""&&typeof chapters[i]!="undefined"){if(opts.direction=="RTL"){j=i;if(j%2!=0)j--;ddC.find(".b-current").text(chapters[i]);ddCLI=$('<li><a href="#/page/'+(j+1)+'" id="selector-page-'+j+'"><span class="b-text">'+chapters[i]+"</span></a></li>").prependTo(ddCUL)}else ddCLI=$('<li><a href="#/page/'+(i+1)+'" id="selector-page-'+i+'"><span class="b-text">'+chapters[i]+"</span></a></li>").appendTo(ddCUL);ddCA=ddCLI.find("a");!opts.hash&&ddCA.click(function(){opts.direction=="RTL"&&ddC.find(".b-current").text($(this).find(".b-text").text());ddCT=parseInt($(this).attr("id").replace("selector-page-",""));self.gotoPage(ddCT);return false})}ddCH=ddCUL.height();ddCUL.css({height:0,"padding-bottom":0});ddC.unbind("hover").hover(function(){ddCUL.stop().animate({height:ddCH,paddingBottom:10},500)},function(){ddCUL.stop().animate({height:0,paddingBottom:0},500)})}}$.extend(self,{next:function(){!busy&&self.gotoPage(opts.curr+2)},prev:function(){!busy&&self.gotoPage(opts.curr-2)},first:function(){!busy&&self.gotoPage(0)},last:function(){if(!busy){var targetNum=opts.pTotal%2!=0?opts.pTotal-1:opts.pTotal;self.gotoPage(opts.pTotal-4)}},gotoPage:function(num){if(num>opts.curr&&num<opts.pTotal&&num>=0&&!busy){busy=true;diff=num-opts.curr;opts.curr=num;opts.before.call(self,opts);updatePager();updateCtrls();updateHash(opts.curr+1,opts);initAnim(diff,true,sF);p2.stop().animate({width:0},opts.speedH,opts.easeIn);p3.stop().animate({left:opts.pWidthH,width:opts.pWidthH,paddingLeft:opts.shadowBtmWidth},opts.speedH,opts.easeIn).animate({left:0,width:opts.pWidth,paddingLeft:0},opts.speedH);p3wrap.animate({left:opts.shadowBtmWidth},opts.speedH,opts.easeIn).animate({left:0},opts.speedH,opts.easeOut,function(){updateAfter()})}else if(num<opts.curr&&num<opts.pTotal&&num>=0&&!busy){busy=true;diff=opts.curr-num;opts.curr=num;opts.before.call(self,opts);updatePager();updateCtrls();updateHash(opts.curr+1,opts);initAnim(diff,false,sB);p1.animate({left:opts.pWidth,width:0},opts.speed,opts.easing);p1wrap.animate({left:opts.pWidthN},opts.speed,opts.easing);p0.animate({left:opts.pWidthH,width:opts.pWidthH},opts.speedH,opts.easeIn).animate({left:opts.pWidth,width:opts.pWidth},opts.speedH,opts.easeOut);p0wrap.animate({right:opts.shadowBtmWidth},opts.speedH,opts.easeIn).animate({right:0},opts.speedH,opts.easeOut,function(){updateAfter()})}}});if(opts.next){next=$(opts.next);next.click(function(e){e.preventDefault();self.next()})}if(opts.prev){prev=$(opts.prev);prev.click(function(e){e.preventDefault();self.prev()})}if(opts.first){firstBtn=$(opts.first);firstBtn.click(function(e){e.preventDefault();self.first()})}if(opts.last){lastBtn=$(opts.last);lastBtn.click(function(e){e.preventDefault();self.last()})}if(opts.overlays){overlayP=$('<div class="b-overlay b-overlay-prev b-prev" title="'+SR_PREV+'"><div id="b-overlay-prev-m"></div></div>').appendTo(b);overlayN=$('<div class="b-overlay b-overlay-next b-next" title="'+SR_NEXT+'"><div id="b-overlay-next-m"></div></div>').appendTo(b);overlayC=$('<div id="b-overlay-center" title="'+SR_NEW_READ+'"></div>').appendTo(b);overlays=b.find(".b-overlay");overlayP.find("#b-overlay-prev-m").click(function(e){e.preventDefault();self.prev()}).hover(function(){$(this).stop().fadeTo("fast",.8)},function(){$(this).stop().fadeTo("fast",.3)});overlayN.find("#b-overlay-next-m").click(function(e){e.preventDefault();self.next()}).hover(function(){$(this).stop().fadeTo("fast",.8)},function(){$(this).stop().fadeTo("fast",.3)});overlayC.click(opts.overlayopen).hover(function(){$(this).stop().fadeTo("fast",.8)},function(){$(this).stop().fadeTo("fast",0)})}if(opts.tabs){tabP=$('<div class="b-tab b-tab-prev b-prev" title="Previous Page">Previous</div>').appendTo(b);tabN=$('<div class="b-tab b-tab-next b-next" title="Next Page">Next</div>').appendTo(b);tabs=b.find(".b-tab");opts.tabWidth&&tabs.width(opts.tabWidth);opts.tabHeight&&tabs.height(opts.tabHeight);tabs.css({top:"-"+tabN.outerHeight()+"px"});b.css({marginTop:tabN.outerHeight()});if(opts.direction=="RTL"){tabN.html("Previous").attr("title","Previous Page");tabP.html("Next").attr("title","Next Page")}}else b.css({marginTop:0});if(opts.arrows){arrowP=$('<div class="b-arrow b-arrow-prev b-prev" title="'+SR_PREV+'"><div>'+SR_PREV+"</div></div>").appendTo(b);arrowN=$('<div class="b-arrow b-arrow-next b-next" title="'+SR_NEXT+'"><div>'+SR_NEXT+"</div></div>").appendTo(b);arrows=b.find(".b-arrow");if(opts.direction=="RTL"){arrowN.html("<div>"+SR_PREV+"</div>").attr("title",SR_PREV);arrowP.html("<div>"+SR_NEXT+"</div>").attr("title",SR_NEXT)}}ctrlsN=b.find(".b-next");ctrlsP=b.find(".b-prev");if(!opts.overlays){ctrlsN.click(function(e){e.preventDefault();self.next()});ctrlsP.click(function(e){e.preventDefault();self.prev()})}if(opts.hovers){ctrlsN.hover(function(){if(!busy&&opts.curr+2<=opts.pTotal-2){p2.stop().animate({width:opts.pWidth-40},500,opts.easing);p3.stop().animate({left:opts.width-40,width:20,paddingLeft:10},500,opts.easing);rhover=true}},function(){if(!busy&&opts.curr+2<=opts.pTotal-2){p2.stop().animate({width:opts.pWidth},500,opts.easing);p3.stop().animate({left:opts.width,width:0,paddingLeft:0},500,opts.easing);rhover=false}});ctrlsP.hover(function(){if(!busy&&opts.curr-2>=0){p1.stop().animate({left:10,width:opts.pWidth-10},400,opts.easing);p1wrap.stop().animate({left:"-10px"},400,opts.easing);p0.stop().animate({left:10,width:40},400,opts.easing);p0wrap.stop().animate({right:10},400,opts.easing);lhover=true}},function(){if(!busy&&opts.curr-2>=0){p1.stop().animate({left:0,width:opts.pWidth},400,opts.easing);p1wrap.stop().animate({left:0},400,opts.easing);p0.stop().animate({left:0,width:0},400,opts.easing);p0wrap.stop().animate({right:0},400,opts.easing);lhover=false}})}if(opts.arrows)if($.support.opacity){ctrlsN.hover(function(){arrowN.stop().fadeTo("fast",1)},function(){arrowN.stop().fadeTo("fast",.5)});ctrlsP.hover(function(){arrowP.stop().fadeTo("fast",1)},function(){arrowP.stop().fadeTo("fast",.5)})}opts.keyboard&&$(document).keyup(function(event){if(event.keyCode==37)self.prev();else event.keyCode==39&&self.next()});if(opts.hash){setupHash();clearInterval();setInterval(function(){pollHash()},250)}resetPages();function initPages(){if(src.children().length%2!=0)if(opts.closed&&opts.covers)src.children().last().before(blank);else src.children().last().after(blank);if(opts.closed){$(empty).attr({title:opts.closedFrontTitle||"Beginning",rel:opts.closedFrontChapter||"Beginning of Book"}).prependTo(src);src.children().last().attr({title:opts.closedBackTitle||"End",rel:opts.closedBackChapter||"End of Book"});src.append(empty)}if(opts.direction=="LTR")j=0;else{j=src.children().length;if(opts.closed)j-=2;if(opts.covers)j-=2;$(src.children().get().reverse()).each(function(){$(this).appendTo(src)})}src.children().each(function(i){if($(this).attr("rel"))chapters[i]=$(this).attr("rel");else chapters[i]="";titles[i]=$(this).attr("title");if($(this).hasClass("b-page-empty"))$(this).wrap('<div class="b-page"><div class="b-wrap"></div></div>');else if(opts.closed&&opts.covers&&(i==1||i==src.children().length-2))$(this).wrap('<div class="b-page"><div class="b-wrap b-page-cover"></div></div>');else if(i%2!=0)$(this).wrap('<div class="b-page"><div class="b-wrap b-wrap-right"></div></div>');else $(this).wrap('<div class="b-page"><div class="b-wrap b-wrap-left"></div></div>');$(this).parents(".b-page").addClass("b-page-"+i);if(opts.pageNumbers&&!$(this).hasClass("b-page-empty")&&(!opts.closed||opts.closed&&!opts.covers||opts.closed&&opts.covers&&i!=1&&i!=src.children().length-2)){if(opts.direction=="LTR")j++;$(this).parent().append('<div class="b-counter">'+j+"</div>");if(opts.direction=="RTL")j--}})}function resetPages(){b.find(".b-page").removeClass("b-pN b-p0 b-p1 b-p2 b-p3 b-p4").hide();if(init){j=opts.pTotal-1;for(i=0;i<opts.pTotal;i++)b.find(".b-page-"+i).detach().appendTo(src)}if(opts.curr-2>=0){b.find(".b-page-"+(opts.curr-2)).addClass("b-pN").show();b.find(".b-page-"+(opts.curr-1)).addClass("b-p0").show()}b.find(".b-page-"+opts.curr).addClass("b-p1").show();b.find(".b-page-"+(opts.curr+1)).addClass("b-p2").show();if(opts.curr+3<=opts.pTotal){b.find(".b-page-"+(opts.curr+2)).addClass("b-p3").show();b.find(".b-page-"+(opts.curr+3)).addClass("b-p4").show()}pN=b.find(".b-pN");p0=b.find(".b-p0");p1=b.find(".b-p1");p2=b.find(".b-p2");p3=b.find(".b-p3");p4=b.find(".b-p4");pNwrap=b.find(".b-pN .b-wrap");p0wrap=b.find(".b-p0 .b-wrap");p1wrap=b.find(".b-p1 .b-wrap");p2wrap=b.find(".b-p2 .b-wrap");p3wrap=b.find(".b-p3 .b-wrap");p4wrap=b.find(".b-p4 .b-wrap");wraps=b.find(".b-wrap");pcover=b.find(".b-page-cover");wraps.attr("style","");wraps.css({width:opts.pWidth-opts.pagePadding*2,height:opts.pHeight-opts.pagePadding*2});pcover.css({width:opts.pWidth,height:opts.pHeight});p1.css({left:0,width:opts.pWidth,height:opts.pHeight});p2.css({left:opts.pWidth,width:opts.pWidth,height:opts.pHeight});pN.css({left:0,width:opts.pWidth,height:opts.pHeight});p0.css({left:0,width:0,height:opts.pHeight});p3.stop().css({left:opts.pWidth*2,width:0,height:opts.pHeight,paddingLeft:0});p3wrap.stop().css({left:0});p4.css({left:opts.pWidth,width:opts.pWidth,height:opts.pHeight});if(opts.curr+3<=opts.pTotal){p3.after(p0.detach());p1.after(p4.detach())}else p0.detach().appendTo(src);init=true;sF=sB=null;b.find(".b-shadow-b, .b-shadow-f").remove();if(opts.shadows){sF=$('<div class="b-shadow-f"></div>').appendTo(p3).css({right:0,width:opts.pWidth,height:opts.pHeight});sB=$('<div class="b-shadow-b"></div>').appendTo(p0).css({left:0,width:opts.pWidth,height:opts.pHeight})}updateCtrls()}function initAnim(diff,inc,shadow){if(inc&&diff>2){b.find(".b-p3, .b-p4").removeClass("b-p3 b-p4").hide();b.find(".b-page-"+opts.curr).addClass("b-p3").show().stop().css({left:opts.pWidth*2,width:0,height:opts.pHeight,paddingLeft:0});b.find(".b-page-"+(opts.curr+1)).addClass("b-p4").show().css({left:opts.pWidth,width:opts.pWidth,height:opts.pHeight});b.find(".b-page-"+opts.curr+" .b-wrap").show().css({width:opts.pWidth-opts.pagePadding*2,height:opts.pHeight-opts.pagePadding*2});b.find(".b-page-"+(opts.curr+1)+" .b-wrap").show().css({width:opts.pWidth-opts.pagePadding*2,height:opts.pHeight-opts.pagePadding*2});p3=b.find(".b-p3");p4=b.find(".b-p4");p3wrap=b.find(".b-p3 .b-wrap");p4wrap=b.find(".b-p4 .b-wrap");rhover&&p3.css({left:opts.width-40,width:20,"padding-left":10});opts.shadows&&shadow.appendTo(p3);p1.after(p4.detach());p2.after(p3.detach())}else if(!inc&&diff>2){b.find(".b-pN, .b-p0").removeClass("b-pN b-p0").hide();b.find(".b-page-"+opts.curr).addClass("b-pN").show().css({left:0,width:opts.pWidth,height:opts.pHeight});b.find(".b-page-"+(opts.curr+1)).addClass("b-p0").show().css({left:0,width:0,height:opts.pHeight});b.find(".b-page-"+opts.curr+" .b-wrap").show().css({width:opts.pWidth-opts.pagePadding*2,height:opts.pHeight-opts.pagePadding*2});b.find(".b-page-"+(opts.curr+1)+" .b-wrap").show().css({width:opts.pWidth-opts.pagePadding*2,height:opts.pHeight-opts.pagePadding*2});pN=b.find(".b-pN");p0=b.find(".b-p0");pNwrap=b.find(".b-pN .b-wrap");p0wrap=b.find(".b-p0 .b-wrap");if(lhover){p0.css({left:10,width:40});p0wrap.css({right:10})}opts.shadows&&shadow.appendTo(p0);p0.detach().appendTo(src)}if(opts.closed){if(!inc&&opts.curr==0)pN.hide();else!inc&&pN.show();if(inc&&opts.curr>=opts.pTotal-2)p4.hide();else inc&&p4.show()}if(opts.shadows)if($.support.opacity)shadow.animate({opacity:1},opts.speedH,opts.easeIn).animate({opacity:0},opts.speedH,opts.easeOut);else if(inc)shadow.animate({right:opts.shadowTopFwdWidth},opts.speed,opts.easeIn);else shadow.animate({left:opts.shadowTopBackWidth},opts.speed,opts.easeIn)}function updateAfter(){resetPages();updatePager();updateCtrls();opts.after.call(self,opts);busy=false}function updateCtrls(){if(opts.overlays||opts.tabs||opts.arrows){if(opts.curr<opts.pTotal-2)ctrlsN.fadeIn("fast").css("cursor",opts.cursor);else ctrlsN.fadeOut("fast").css("cursor","default");if(opts.curr>=2&&opts.curr!=0)ctrlsP.fadeIn("fast").css("cursor",opts.cursor);else ctrlsP.fadeOut("fast").css("cursor","default")}}function updatePager(){if(opts.pageSelector)if(opts.direction=="RTL"){nums=Math.abs(opts.curr-opts.pTotal)-1+" - "+Math.abs(opts.curr-opts.pTotal);if(opts.closed){if(opts.curr==opts.pTotal-2)nums="1";else if(opts.curr==0)nums=opts.pTotal-2;else nums=Math.abs(opts.curr-opts.pTotal)-2+" - "+(Math.abs(opts.curr-opts.pTotal)-1);if(opts.covers)if(opts.curr==opts.pTotal-2)nums="";else if(opts.curr==0)nums="";else nums=Math.abs(opts.curr-opts.pTotal)-3+" - "+(Math.abs(opts.curr-opts.pTotal)-2)}$(opts.menu+" .b-selector-page .b-current").text(nums)}else{nums=opts.curr+1+" - "+(opts.curr+2);if(opts.closed){if(opts.curr==0)nums="1";else if(opts.curr==opts.pTotal-2)nums=opts.pTotal-2;else nums=opts.curr+"-"+(opts.curr+1);if(opts.covers)if(opts.curr==0)nums="";else if(opts.curr==opts.pTotal-2)nums="";else nums=opts.curr-1+"-"+opts.curr}$(opts.menu+" .b-selector-page .b-current").text(nums)}if(opts.chapterSelector){if(chapters[opts.curr]!="")$(opts.menu+" .b-selector-chapter .b-current").text(chapters[opts.curr]);else chapters[opts.curr+1]!=""&&$(opts.menu+" .b-selector-chapter .b-current").text(chapters[opts.curr+1]);if(opts.direction=="RTL"&&chapters[opts.curr+1]!="")$(opts.menu+" .b-selector-chapter .b-current").text(chapters[opts.curr+1]);else chapters[opts.curr]!=""&&$(opts.menu+" .b-selector-chapter .b-current").text(chapters[opts.curr])}}function setupHash(){hash=getHashNum();if(!isNaN(hash)&&hash<=opts.pTotal-1&&hash>=0&&hash!=""){if(hash%2!=0)hash--;opts.curr=hash}else updateHash(opts.curr+1,opts);self.hash=hash}function pollHash(){hash=getHashNum();if(!isNaN(hash)&&hash<=opts.pTotal-1&&hash>=0)if(hash!=opts.curr&&hash.toString()!=self.hash){if(hash%2!=0)hash--;document.title=opts.name+" - Page "+(hash+1);if(!busy){self.gotoPage(hash);self.hash=hash}}}function getHashNum(){var hash=window.location.hash.split("/");if(hash.length>1)return parseInt(hash[2])-1;else return ""}function updateHash(hash,opts){if(opts.hash)window.location.hash="/page/"+hash}}$.fn.booklet.interfaces=[];$.fn.booklet.defaults={name:null,width:600,height:400,speed:1e3,direction:"LTR",startingPage:0,easing:"easeInOutQuad",easeIn:"easeInQuad",easeOut:"easeOutQuad",closed:false,closedFrontTitle:null,closedFrontChapter:null,closedBackTitle:null,closedBackChapter:null,covers:false,pagePadding:10,pageNumbers:true,hovers:true,overlays:true,tabs:false,tabWidth:60,tabHeight:20,arrows:false,cursor:"pointer",hash:false,keyboard:true,next:null,prev:null,menu:null,pageSelector:false,chapterSelector:false,shadows:true,shadowTopFwdWidth:166,shadowTopBackWidth:166,shadowBtmWidth:50,before:function(){},after:function(){},overlayopen:null}})(jQuery);

/*
 * jQuery Spotword reader Plugin
 */
var is_spotreader_load=false;(function($){$.fn.spotreader=function(options){var defaults={load:null,mode:"",width:0,height:0,overlayopen:null,cover:""},setting=$.extend(defaults,options),showObj=$(this),showId=showObj.attr("id"),loadingObj=$("#loading"),isCover=false,itemImage={},scWidth=0,scHeight=0,caWidth=0,caHeight=0,boWidth=0,boHeight=0,fontStyles={fontSize:12,lineHeight:24,letterSpacing:2,fontFamily:SR_FONT_FAMILY},KINSOKU_BEGIN=SR_KINSOKU_BEGIN,KINSOKU_END=SR_KINSOKU_END,pages=[],pageNum=0,startReader=function(){var w,h,img_src;if(setting.cover!=""){var covQ=$("<img />").attr("src",setting.cover);covQ.attr("width",boWidth).attr("height",boHeight);pages.push($("<div />").append(covQ).html());pageNum++;isCover=true}$(setting.load+" > div").each(function(i){$(this).find("img").each(function(){var img=$(this);img_src=img.attr("src");w=img.attr("width");h=img.attr("height");itemImage[img_src]={width:w,height:h}});parsePage($(this).html(),false,true)});buildPages();showBooklet()},showBooklet=function(){var top_elm=$('<div class="b-load"></div>');top_elm.css("font-size",fontStyles.fontSize+"px");top_elm.css("line-height",fontStyles.lineHeight+"px");top_elm.css("letter-spacing",fontStyles.letterSpacing+"px");top_elm.css("font-family",fontStyles.fontFamily);for(var i=0;i<pages.length;i++)top_elm.append("<div>"+pages[i]+"</div>");var booklet_opts={speed:500,width:boWidth*2,height:boHeight,pagePadding:30,keyboard:true,pageNumbers:false};if(setting.mode!="inline"){booklet_opts.arrows=true,booklet_opts.prev="#spotreader-navi-prev";booklet_opts.next="#spotreader-navi-next";booklet_opts.first="#spotreader-navi-first";booklet_opts.last="#spotreader-navi-last";booklet_opts.overlays=false;booklet_opts.hash=true}if(setting.overlayopen)booklet_opts.overlayopen=setting.overlayopen;booklet_opts.hovers=false;booklet_opts.shadows=false;if(isCover){booklet_opts.closed=true;booklet_opts.covers=true;top_elm.append("<div></div>")}showObj.append(top_elm);showObj.booklet(booklet_opts);loadingObj.hide();pages=null;is_spotreader_load=false;showObj.addClass("book-center-bg")},convertParseHtml=function(html){if(html==null||html=="")return "";return html.replace(/(\n|\r|\t|\v)/mg,"").replace(/<br ?[^>]*>/img,"\n").replace(/<\/(p|div|li)>/img,"\n").replace(/<(p|div|ul|ol)[^>]*>/img,"").replace(/<(li|\/ul|\/ol)[^>]*>/img,"")},parsePage=function(section,cover,init){if(init)section=convertParseHtml(section);if(section==null||section=="")return "";var c="",nc="",nnc="",buf="",c_w=0,c_h=0,w_n=fontStyles.fontSize+fontStyles.letterSpacing,h_n=fontStyles.lineHeight,s_w=Math.floor(caWidth/w_n)-1,s_h=Math.floor(caHeight/h_n),ascii_w=(fontStyles.fontSize/2+fontStyles.letterSpacing)/w_n+.1,start=0,max=section.length,is_float=false,is_float_h=0,is_float_w=0;while(start<max){c=section.substr(start,1);if(c!=null){if(c=="<"){var tagStr=c,tagQ,tc="",tn=start+1,tm;while(true){tc=section.substr(tn,1);tagStr+=tc;if(tc==">"||tc==null){tm=tagStr.match(/^<img [^>]*src="([^"]+)"/i);if(tm&&tm[1]){var img_src=tm[1],max_w=cover?boWidth:caWidth,max_h=cover?boHeight:caHeight;if(itemImage[img_src]&&itemImage[img_src].width||cover){var i_w=itemImage[img_src].width,i_h=itemImage[img_src].height;if(cover){i_h=max_h;i_w=max_w}else if(i_w>max_w){i_h=i_h*(max_w/i_w);i_w=max_w}tagQ=$(tagStr).css({width:i_w,height:i_h,"margin-bottom":5});tagStr=$("<div />").html(tagQ).html();if(tagQ.css("float")!=""&&tagQ.css("float")!="none"){is_float=true;float_w=s_w-Math.floor(i_w/w_n)-1;float_h=Math.floor(i_h/h_n)+c_h}else{var nokori=caHeight-c_h*h_n;if(nokori<i_h){c_w=0;c_h=0;buf=strim(buf);if(buf!=""){pages.push(buf);pageNum++;buf="";is_float=false}}else c_h+=Math.floor(i_h/h_n)}}else{var h_size=5;tagQ=$(tagStr).css({"max-width":max_w,"max-height":h_n*h_size,"margin-bottom":5});tagStr=$("<div />").html(tagQ).html();if(tagQ.css("float")!=""){h_size=2;c_w+=s_w/2}c_h+=h_size}buf+=tagStr}else if(tagStr.match(/^<a /i)){tagStr+=tagStr.replace(/ href="([^"]+)"/i,' href="$1" target="_blank"');buf+=tagStr}else if(tagStr.match(/<\/h[1-6]{1}>/i)){buf+=tagStr;c_w=0;c_h+=1}else buf+=tagStr;start=tn;break}tn++}start++;continue}if(c!="\n")if(c=="")c_w+=0;else if(isAscii(c))c_w+=ascii_w;else c_w+=1;if((is_float?c_w>=float_w:c_w>=s_w)||c=="\n"){nc=section.substr(start+1,1)||"";nnc=section.substr(start+2,1)||"";if(KINSOKU_BEGIN.indexOf(nc,0)>-1&&KINSOKU_BEGIN.indexOf(nnc,0)==-1){if(c=="\n")c=nc;else c+=nc;c+="\n";start++}else if(c!="\n")c+="\n";c_w=0;c_h+=1}buf+=c;if(is_float&&c_h>float_h){is_float=false;float_w=0;float_h=0}if(c_h>=s_h){c_w=0;c_h=0;buf=strim(buf);if(buf!=""){pages.push(buf.replace(/\n/mg,"<br />"));buf="";pageNum++;is_float=false}}}start++}buf=strim(buf);if(buf!=""){pages.push(buf.replace(/\n/mg,"<br />"));buf="";pageNum++;is_float=false}return section},buildPages=function(){for(var start=setting.cover==""?0:1,max=pages.length,i=start;i<max;i++)if(i>start){var bTag=rebuildPrev(pages[i-1]);pages[i]=bTag+pages[i]}for(var i=start;i<max;i++){var eTag=rebuildNext(pages[i]);pages[i]=pages[i]+eTag}},rebuildPrev=function(html){for(var len=html.length-1,c="",buf=[],arr=[],tags=[],i=len;i>=0;i--){c=html.substr(i,1);if(c==">"){buf=[];buf.push(c);while(i>=0){c=html.substr(--i,1);buf.push(c);if(c=="<"){buf.reverse();var tag=buf.join("").toLowerCase();if(tag.substr(0,3)=="<br"||tag.substr(0,4)=="<img")break;if(tag.substr(0,2)=="</")arr.push(tag.substr(2,tag.length-3));else{var m=tag.match(/^<([^> ]+)/);if(m)if(arr[arr.length-1]==m[1])arr.pop();else tags.push(tag)}break}}}}tags.reverse();return tags.join("")},rebuildNext=function(html){for(var len=html.length,c="",buf=[],arr=[],tags=[],i=0;i<len;i++){c=html.substr(i,1);if(c=="<"){buf=[];buf.push(c);while(i<len){c=html.substr(++i,1);buf.push(c);if(c==">"){var tag=buf.join("").toLowerCase();if(tag.substr(0,3)=="<br"||tag.substr(0,4)=="<!--"||tag.substr(0,4)=="<img")break;if(tag.substr(0,2)=="</")if(arr[arr.length-1]==tag.substr(2,tag.length-3))arr.pop();else tags.push(tag);else{var m=tag.match(/^<([^> ]+)/);m&&arr.push(m[1])}break}}}}if(arr.length>0)for(var i=arr.length-1;i>=0;i--)tags.push("</"+arr[i]+">");return tags.join("")},init=function(){is_spotreader_load=true;pages=[];pageNum=0;var loading;if(setting.mode=="inline"){scWidth=setting.width;boWidth=scWidth/2;boHeight=boWidth/.75;scHeight=boHeight;caHeight=boHeight-65;caWidth=boWidth-65}else{scHeight=$(window).height();scWidth=$(window).width();boHeight=scHeight-100;boWidth=boHeight*.75;caHeight=boHeight-70;caWidth=boWidth-70;loading=$("<img />").attr("src","/js/spotreader/images/loading.gif");loadingObj=$('<div id="loading"><div>'+SR_NOW_LOADING+"</div></div>");loadingObj.css({position:"absolute",top:(scHeight-19)/2,left:(scWidth-220)/2,color:"#666","text-align":"center"});$("#spotreader-navi-big").click(function(e){e.preventDefault();fontStyles.fontSize+=2;fontStyles.lineHeight=fontStyles.fontSize*2;resetReader()});$("#spotreader-navi-small").click(function(e){e.preventDefault();fontStyles.fontSize-=2;fontStyles.lineHeight=fontStyles.fontSize*2;resetReader()});loadingObj.prepend(loading);showObj.append(loadingObj)}showObj.height(scHeight);showObj.width(scWidth)},resetReader=function(){is_spotreader_load=true;showObj.booklet("clearpollhash");showObj.empty();showObj.removeClass("booklet book-center-bg");showObj.append(loadingObj);loadingObj.show();pages=[];pageNum=0;$.fn.booklet.interfaces=[];startReader()},exitMsg=function(msg){epub=null;alert(msg);return false},isAscii=function(s){var c=s.charCodeAt(0);if(c>=0&&c<129||c==63728||c>=65377&&c<65440||c>=63729&&c<63732)return true;return false},strim=function(str){return str.replace(/^[ \r\n]+/,"").replace(/[ \r\n]+$/,"")},debug=function(obj){var str="";if(typeof obj==="object")for(var i in obj)str+=i+" => "+debug(obj[i]);else str+=obj+"\n";return str};if(is_spotreader_load==false){init();startReader()}return this}})(jQuery)