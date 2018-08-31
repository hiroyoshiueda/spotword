var is_spotreader_load = false;

(function($){
	$.fn.spotreader = function(options){
		var defaults = {
			'epub_url' : null,
			'mode' : '',
			'width' : 0,
			'height': 0,
			'overlayopen' : null
		};
		var setting = $.extend(defaults, options);
		var showObj = $(this);
		var showId = showObj.attr('id');
		var loadingObj;
		var naviObj = $('#spotreader-navi');
		var opfDir = '';
		var opfFile = '';
		var coverId = '';
		var isCover = false;
		var itemrefIdref = [];
		var itemImage = {};
		var items = {};
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
			var container_xml = setting.epub_url + '/META-INF/container.xml';
			$.ajax({
				url : container_xml,
				async : true,
				timeout : 10000,
				dataType : 'xml',
				error : function(xhr, textStatus, errorThrown){
					alert(textStatus);
					//console.dir(errorThrown);
				},
				success : function(data, textStatus)
				{
					opfFile = $(data).find('rootfile').attr('full-path');
					var m = opfFile.match(/([^\/]*)\/?([^\/]+\.opf)$/i);
					opfDir = (m[1]) ? '/'+m[1] : '';
					if (!opfFile) return exitMsg(SR_CONTAINER_XML_ERR);
					loadOpfFile();
				}
			});
		};

		var loadOpfFile = function()
		{
			var opf_file = setting.epub_url + '/' + opfFile;
			$.ajax({
				url : opf_file,
				async : true,
				timeout : 10000,
//				dataType : 'xml',
				error : function(xhr, textStatus, errorThrown){
					alert(opfFile+': '+textStatus);
					//console.dir(errorThrown);
				},
				success : function(data, textStatus)
				{
					parseOpfFile(data);
				}
			});
		};

		var parseOpfFile = function(data)
		{
			$(data).find('metadata').find('meta').each(function(){
				if ($(this).attr('name') == 'cover') coverId = $(this).attr('content');
			});

			$(data).find('itemref').each(function(){
				var idref = $(this).attr('idref');
				itemrefIdref.push(idref);
			});
			var dataItem = $(data).find('item');
			var last = dataItem.size() - 1;
			var is_image = false;
			dataItem.each(function(i){
				var item = $(this);
				items[item.attr('id')] = item;
				if (item.attr('media-type').match(/^image\/.+/)) {
					loadItemImage(item.attr('id'), item.attr('href'), (i==last));
					is_image = true;
				}
			});
			if (is_image == false) {
				showPage();
			}
		};

		var showPage = function()
		{
			for (var i=0; i<itemrefIdref.length; i++) {
				var id_str = itemrefIdref[i];
				if (!items[id_str]) continue;
				var item = items[id_str];
				if (item.attr('id') == 'cov') isCover = true;
				if (item.attr('media-type') == 'application/xhtml+xml') {
					loadItem(item.attr('id'), item.attr('href'), false);
				}
			}

			showBooklet();
		};

		var loadItem = function(itemId, itemHref, isAsync)
		{
			if (!itemId || !itemHref) return;
			var url = setting.epub_url + opfDir + '/' + itemHref;
			$.ajax({
				url : url,
				async : isAsync,
				timeout : 10000,
				dataType : 'text',
				error : function(xhr, textStatus, errorThrown){
					alert(textStatus);
				},
				success : function(data, textStatus)
				{
					parsePage(data, (itemId == 'cov'), true);
				}
			});
		};

		var loadItemImage = function(itemId, itemHref, func)
		{
			if (!itemId || !itemHref) return;
			var img_src = setting.epub_url + opfDir + '/' + itemHref;
			if (itemHref.match(/^https?:\/\/.+/i)) img_src = itemHref;
			var w, h;
			var img = $('<img />').attr('src', img_src).bind('load', function(){
				w = $(this).attr('width');
				h = $(this).attr('height');
				itemImage[img_src] = {width: w, height: h};
				if (func) showPage();
			});
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
			return html.replace(/<br ?\/?>/img, '\n').replace(/<\/(p|div|li)>/img, '\n').replace(/<(p|div|ul|ol)[^>]*>/img, '').replace(/<(li|\/ul|\/ol)[^>]*>/img,'');
		};

		var parsePage = function(section, cover, init)
		{
			if (init) {
				var m = section.replace(/(\n|\r|\t|\v)/mg, '').match(/<body>(.*)<\/body>/i);
				section = m[1];
				section = convertParseHtml(section);
			}
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
			var image_dir = setting.epub_url + opfDir + '/';
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
									var img_src = tm[1].replace(/^\.?\.?\/?([^h]+[^"]+)/i, image_dir+'$1');
									tagStr = tagStr.replace(/ src="[^"]+"/i, ' src="'+img_src+'"');
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
												buf = $.trim(buf);
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
									// img以外
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
					// 改行
					if ((is_float ? c_w >= float_w : c_w >= s_w) || c == "\n") {
						// 次の文字を確認
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
						// float終了
						is_float = false;
						float_w = 0;
						float_h = 0;
					}

					if (c_h >= s_h) {
						c_w = 0;
						c_h = 0;
						buf = $.trim(buf);
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
			buf = $.trim(buf);
			if (buf!='') {
				pages.push(buf.replace(/\n/mg, '<br />'));
				buf = '';
				pageNum++;
				is_float = false;
			}
//console.dir(pages);
			return section;
		};

		var init = function()
		{
			is_spotreader_load = true;
			showObj.empty();
			pages = [];
			pageNum = 0;

			var loading = $('<img />').attr('src', '/js/spotreader/images/loading.gif');
			loadingObj = $('<div id="loading"><div>'+SR_NOW_LOADING+'</div></div>');

			// ipad: 1024x768(4:3)
			if (setting.mode=='inline') {
				scWidth = setting.width;
				boWidth = scWidth / 2;
				boHeight = boWidth / 0.75;
				scHeight = boHeight;
				caHeight = boHeight - 60;
				caWidth = boWidth - 60;
				loadingObj.css({'position':'absolute', 'top':(scHeight-19) / 2, 'left':(scWidth-220) / 2, 'color':'#666', 'text-align':'center'});
			} else {
				scHeight = $(window).height();
				scWidth = $(window).width();
				var navi_left = (scWidth - naviObj.width()) / 2;
				naviObj.css({'left':navi_left});
				boHeight = scHeight - 100;
				boWidth = boHeight * 0.75;
				caHeight = boHeight - 70;
				caWidth = boWidth - 70;
				loadingObj.css({'position':'absolute', 'top':(scHeight-19) / 2, 'left':(scWidth-220) / 2, 'color':'#666', 'text-align':'center'});
			}

			showObj.height(scHeight);
			showObj.width(scWidth);

			loadingObj.prepend(loading);
			showObj.append(loadingObj);
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