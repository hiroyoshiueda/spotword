var is_spotreader_load = false;

(function($){
	$.fn.spotreader = function(options){
		var defaults = {
			'load' : null,
			'mode' : '',
			'width' : 0,
			'height': 0,
			'overlayopen' : null,
			'cover' : ''
		};
		var setting = $.extend(defaults, options);
		var showObj = $(this);
		var showId = showObj.attr('id');
		var loadingObj = $('#loading');
//		var naviObj = $('#spotreader-navi');
		var isCover = false;
		var itemImage = {};
		var scWidth = 0;
		var scHeight = 0;
		var caWidth = 0;
		var caHeight = 0;
		var boWidth = 0;
		var boHeight = 0;

		var fontStyles = {
			'fontSize' : 12,
			'lineHeight' : 24,
			'letterSpacing' : 2,
			'fontFamily' : SR_FONT_FAMILY
		};

		var KINSOKU_BEGIN = SR_KINSOKU_BEGIN;
		var KINSOKU_END = SR_KINSOKU_END;
		var pages = [];
		var pageNum = 0;

		var startReader = function()
		{
			var w, h, img_src;
			if (setting.cover!='') {
				var covQ = $('<img />').attr('src', setting.cover);
				covQ.attr('width', boWidth).attr('height', boHeight);
				pages.push($('<div />').append(covQ).html());
				pageNum++;
				isCover = true;
			}
			$(setting.load+' > div').each(function(i){
				$(this).find('img').each(function(){
					var img = $(this);
					img_src = img.attr('src');
					w = img.attr('width');
					h = img.attr('height');
					itemImage[img_src] = {width: w, height: h};
					//console.dir({width: w, height: h});
//					img.bind('load', function(){
//						img_src = img.attr('src');
//						w = img.attr('width');
//						h = img.attr('height');
//						itemImage[img_src] = {width: w, height: h};
//						console.dir({width: w, height: h});
//					});
				});
				parsePage($(this).html(), false, true);
			});

			buildPages();
//			alert(pages[3]);
//console.dir(pages);
			showBooklet();
		};

		var showBooklet = function()
		{
			var top_elm = $('<div class="b-load"></div>');
			top_elm.css('font-size', fontStyles.fontSize+'px');
			top_elm.css('line-height', fontStyles.lineHeight+'px');
			top_elm.css('letter-spacing', fontStyles.letterSpacing+'px');
			top_elm.css('font-family', fontStyles.fontFamily);
			for (var i=0; i<pages.length; i++) {
				top_elm.append('<div>'+pages[i]+'</div>');
			}
			var booklet_opts = {
				speed: 500,
				width: boWidth*2,
				height: boHeight,
				pagePadding: 30,
				keyboard: true,
				pageNumbers: false
			};
			if (setting.mode!='inline') {
				booklet_opts.arrows = true,
				booklet_opts.prev = '#spotreader-navi-prev';
				booklet_opts.next = '#spotreader-navi-next';
				booklet_opts.first = '#spotreader-navi-first';
				booklet_opts.last = '#spotreader-navi-last';
				booklet_opts.overlays = false;
				booklet_opts.hash = true;
			}
			if (setting.overlayopen) booklet_opts.overlayopen = setting.overlayopen;
			booklet_opts.hovers = false;
			booklet_opts.shadows = false;
			if (isCover) {
				booklet_opts.closed = true;
				booklet_opts.covers = true;
				top_elm.append('<div></div>');
			}
			showObj.append(top_elm);
			showObj.booklet(booklet_opts);
			loadingObj.hide();
			pages = null;
			is_spotreader_load = false;
			showObj.addClass('book-center-bg');
		};

		var convertParseHtml = function(html)
		{
			if (html==null || html=='') return '';
			return html.replace(/(\n|\r|\t|\v)/mg, '').replace(/<br ?[^>]*>/img, '\n').replace(/<\/(p|div|li)>/img, '\n').replace(/<(p|div|ul|ol)[^>]*>/img, '').replace(/<(li|\/ul|\/ol)[^>]*>/img,'');
		};

		var parsePage = function(section, cover, init)
		{
			if (init) section = convertParseHtml(section);
			if (section==null || section=='') return '';

			var c = '', nc = '', nnc = '', buf = '';
			var c_w = 0;
			var c_h = 0;
			var w_n = fontStyles.fontSize + fontStyles.letterSpacing;
			var h_n = fontStyles.lineHeight;
			var s_w = Math.floor(caWidth / w_n) - 1;
			var s_h = Math.floor(caHeight / h_n);
			var ascii_w = (((fontStyles.fontSize/2) + fontStyles.letterSpacing) / w_n) + 0.1;
			var start = 0;
			var max = section.length;
			var is_float = false;
			var is_float_h = 0;
			var is_float_w = 0;

			while (start < max) {
				c = section.substr(start, 1);
				if (c!=null) {
					// tag start
					if (c == "<") {
						var tagStr = c;
						var tagQ;
						var tc = '';
						var tn = start + 1;
						var tm;
						while (true) {
							tc = section.substr(tn, 1);
							tagStr += tc;
							if (tc == '>' || tc == null) {
								tm = tagStr.match(/^<img [^>]*src="([^"]+)"/i);
								if (tm && tm[1]) {
									var img_src = tm[1];
									//tagStr = tagStr.replace(/ src="[^"]+"/i, ' src="'+img_src+'"');
									var max_w = cover ? boWidth : caWidth;
									var max_h = cover ? boHeight : caHeight;
									if ((itemImage[img_src] && itemImage[img_src].width) || cover) {
										var i_w = itemImage[img_src].width;
										var i_h = itemImage[img_src].height;
										if (cover) {
											i_h = max_h;
											i_w = max_w;
										} else if (i_w>max_w) {
											i_h = i_h * (max_w / i_w);
											i_w = max_w;
										}
										tagQ = $(tagStr).css({'width':i_w, 'height':i_h, 'margin-bottom':5});
										tagStr = $('<div />').html(tagQ).html();
										// to float.
										if (tagQ.css('float')!='' && tagQ.css('float')!='none') {
											is_float = true;
											float_w = s_w - Math.floor(i_w / w_n) - 1;
											float_h = Math.floor(i_h / h_n) + c_h;
										} else {
											var nokori = caHeight - (c_h * h_n);
											if (nokori<i_h) {
												c_w = 0;
												c_h = 0;
												buf = strim(buf);
												if (buf!='') {
													pages.push(buf);
													pageNum++;
													buf = '';
													is_float = false;
												}
											} else {
												c_h += Math.floor(i_h / h_n);
											}
										}
									} else {
										// notfoud image is 5 char height.
										var h_size = 5;
										tagQ = $(tagStr).css({'max-width':max_w, 'max-height':h_n*h_size, 'margin-bottom':5});
										tagStr = $('<div />').html(tagQ).html();
										// float is 2 char.
										if (tagQ.css('float')!='') {
											h_size = 2;
											c_w += (s_w/2);
										}
										c_h += h_size;
									}
									buf += tagStr;
								} else if (tagStr.match(/^<a /i)) {
									//tagStr += tagStr.replace(/ href="([^http]{4}:\/\/]+[^"]+)"/i, ' href="$1" target="_blank"');
									tagStr += tagStr.replace(/ href="([^"]+)"/i, ' href="$1" target="_blank"');
									buf += tagStr;
								} else if (tagStr.match(/<\/h[1-6]{1}>/i)) {
									buf += tagStr;
									c_w = 0;
									c_h += 1;
								} else {
									// other
									buf += tagStr;
								}
								start = tn;
								break;
							}
							tn++;
						}
						start++;
						continue;
					}
					// tag end
					if (c != "\n") {
						if (c=='') {
							c_w += 0;
						} else if (isAscii(c)) {
							c_w += ascii_w;
						} else {
							c_w += 1;
						}
					}
					// br
					if ((is_float ? c_w >= float_w : c_w >= s_w) || c == "\n") {
						// next char
						nc = section.substr(start + 1, 1) || '';
						nnc = section.substr(start + 2, 1) || '';
						if (KINSOKU_BEGIN.indexOf(nc, 0) > -1 && KINSOKU_BEGIN.indexOf(nnc, 0) == -1) {
							if (c == "\n") {
								c = nc;
							} else {
								c += nc;
							}
							c += "\n";
							start++;
						} else if (c != "\n") {
							c += "\n";
						}
						c_w = 0;
						c_h += 1;
					}

					buf += c;

					if (is_float && c_h > float_h) {
						// float end
						is_float = false;
						float_w = 0;
						float_h = 0;
					}

					if (c_h >= s_h) {
						c_w = 0;
						c_h = 0;
						buf = strim(buf);
						if (buf!='') {
							pages.push(buf.replace(/\n/mg, '<br />'));
							buf = '';
							pageNum++;
							is_float = false;
						}
					}
				}
				start++;
			}
			buf = strim(buf);
			if (buf!='') {
				pages.push(buf.replace(/\n/mg, '<br />'));
				buf = '';
				pageNum++;
				is_float = false;
			}
//console.dir(pages);
			return section;
		};

		var buildPages = function()
		{
			var start = (setting.cover=='') ? 0 : 1;
			var max = pages.length;
			for (var i=start; i<max; i++) {
				if (i>start) {
					var bTag = rebuildPrev(pages[i - 1]);
					pages[i] = bTag + pages[i];
				}
			}
			for (var i=start; i<max; i++) {
				var eTag = rebuildNext(pages[i]);
				pages[i] = pages[i] + eTag;
			}
		};
		var rebuildPrev = function(html)
		{
			var len = html.length-1;
			var c = '';
			var buf = [];
			var arr = [];
			var tags = [];
			for (var i=len; i>=0; i--) {
				c = html.substr(i, 1);
				if (c == '>') {
					buf = [];
					buf.push(c);
					while (i>=0) {
						c = html.substr(--i, 1);
						buf.push(c);
						if (c == '<') {
							buf.reverse();
							var tag = buf.join('').toLowerCase();
							if (tag.substr(0, 3) == '<br' || tag.substr(0, 4) == '<img') break;
							if (tag.substr(0, 2) == '</') {
								arr.push(tag.substr(2, tag.length-3));
							} else {
								var m = tag.match(/^<([^> ]+)/);
								if (m) {
									if (arr[arr.length-1] == m[1]) {
										arr.pop();
									} else {
										tags.push(tag);
									}
								}
							}
							break;
						}
					}
				}
			}
			tags.reverse();
			return tags.join('');
		};
		var rebuildNext = function(html)
		{
			var len = html.length;
			var c = '';
			var buf = [];
			var arr = [];
			var tags = [];
			for (var i=0; i<len; i++) {
				c = html.substr(i, 1);
				if (c == '<') {
					buf = [];
					buf.push(c);
					while (i<len) {
						c = html.substr(++i, 1);
						buf.push(c);
						if (c == '>') {
							var tag = buf.join('').toLowerCase();
							if (tag.substr(0, 3) == '<br' || tag.substr(0, 4) == '<!--' || tag.substr(0, 4) == '<img') break;
							if (tag.substr(0, 2) == '</') {
								if (arr[arr.length-1] == tag.substr(2, tag.length-3)) {
									arr.pop();
								} else {
									tags.push(tag);
								}
							} else {
								var m = tag.match(/^<([^> ]+)/);
								if (m) {
									arr.push(m[1]);
								}
							}
							break;
						}
					}
				}
			}
			if (arr.length>0) {
				for (var i=arr.length-1; i>=0; i--) {
					tags.push('</'+arr[i]+'>');
				}
			}
			return tags.join('');
		};

//		var buildTag = function(html)
//		{
//			html = rebuildTag(html, 'p');
//			html = rebuildTag(html, 'strong');
//			html = rebuildTag(html, 'span');
//			return html;
//		};
//
//		var rebuildTag = function(html, tag)
//		{
//			var big = new RegExp("<("+tag+")[^>]*>", "img");
//			var end = new RegExp("<\/("+tag+")>", "img");
//			var arr = html.split(big);
//			var len = arr.length;
//			if (len > 1) {
//				if (!arr[len - 1].match(end)) {
//					html += '</'+tag+'>';
//				}
//			}
//			arr = html.split(end);
//			len = arr.length;
//			if (len > 1) {
//				if (!arr[0].match(big)) {
//					html = '<'+tag+'>' + html;
//				}
//			}
//			return html;
//		};

		var init = function()
		{
			is_spotreader_load = true;
//			showObj.empty();
			pages = [];
			pageNum = 0;

			var loading;
//			var loading = $('<img />').attr('src', '/js/spotreader/images/loading.gif');
//			loadingObj = $('<div id="loading"><div>'+SR_NOW_LOADING+'</div></div>');

			// ipad: 1024x768(4:3)
			if (setting.mode=='inline') {
				scWidth = setting.width;
				boWidth = scWidth / 2;
				boHeight = boWidth / 0.75;
				scHeight = boHeight;
				caHeight = boHeight - 65;
				caWidth = boWidth - 65;
//				loadingObj.css({'position':'absolute', 'top':(scHeight-19) / 2, 'left':(scWidth-220) / 2, 'color':'#666', 'text-align':'center'});
			} else {
				scHeight = $(window).height();
				scWidth = $(window).width();
//				var navi_left = (scWidth - naviObj.width()) / 2;
//				naviObj.css({'left':navi_left});
				boHeight = scHeight - 100;
				boWidth = boHeight * 0.75;
				caHeight = boHeight - 70;
				caWidth = boWidth - 70;
				loading = $('<img />').attr('src', '/js/spotreader/images/loading.gif');
				loadingObj = $('<div id="loading"><div>'+SR_NOW_LOADING+'</div></div>');
				loadingObj.css({'position':'absolute', 'top':(scHeight-19) / 2, 'left':(scWidth-220) / 2, 'color':'#666', 'text-align':'center'});
				// font size
				$('#spotreader-navi-big').click(function(e){
					e.preventDefault();
					fontStyles.fontSize += 2;
					fontStyles.lineHeight = fontStyles.fontSize * 2;
					resetReader();
				});
				$('#spotreader-navi-small').click(function(e){
					e.preventDefault();
					fontStyles.fontSize -= 2;
					fontStyles.lineHeight = fontStyles.fontSize * 2;
					resetReader();
				});
				loadingObj.prepend(loading);
				showObj.append(loadingObj);
			}

			showObj.height(scHeight);
			showObj.width(scWidth);

//			loadingObj.prepend(loading);
//			showObj.append(loadingObj);
		};

		var resetReader = function()
		{
			is_spotreader_load = true;
//			showObj.html('');
//			showObj.find('div.b-load').remove();
//			var h = window.location.hash;
			//window.location.hash = '';
			showObj.booklet('clearpollhash');
			showObj.empty();
			showObj.removeClass('booklet book-center-bg');
			showObj.append(loadingObj);
			loadingObj.show();
			pages = [];
			pageNum = 0;
			$.fn.booklet.interfaces = [];
			startReader();
//			window.location.hash = h;
		};

		var exitMsg = function(msg)
		{
			epub = null;
			alert(msg);
			return false;
		};

		var isAscii = function(s)
		{
//			return (s.charCodeAt(0)<=255);
			var c = s.charCodeAt(0);
			if ( (c >= 0x0 && c < 0x81) || (c == 0xf8f0) || (c >= 0xff61 && c < 0xffa0) || (c >= 0xf8f1 && c < 0xf8f4)) {
				return true;
			}
			return false;
		};

		var strim = function(str)
		{
			return str.replace(/^[ \r\n]+/, '').replace(/[ \r\n]+$/, '');
		};

		var debug = function(obj)
		{
			var str = '';
			if (typeof obj === 'object') {
				for (var i in obj) {
					str += i + ' => ' + debug(obj[i]);
				}
			} else {
				str += obj + "\n";
			}
			return str;
		};

		if (is_spotreader_load == false) {
			init();
			startReader();
		}

		return this;
	};
})(jQuery);