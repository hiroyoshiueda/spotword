var user_mydesk_write_modify_flag = false;

var swEditor = {
	mceInit2 :
	{
		script_url : "/js/sweditor/tinymce/tiny_mce.js",
		theme : "advanced",
		skin:"mt",
		language : "ja",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,outdent,indent,blockquote,hr,|,justifyleft,justifycenter,justifyright,|,code,fullscreen",
		theme_advanced_buttons2 : "formatselect,forecolor,|,removeformat,pagebreak",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : "",
		dialog_type : "modal",
		content_css : "css/content.css"
	},
	mceInit :
	{
		script_url : "/js/sweditor/tinymce/tiny_mce.js",
		language : "ja",
		mode : "exact",
		elements : '',
		theme : "advanced",
		skin : 'mt',
		width : null,
		height : null,
		force_br_newlines : false,
		force_p_newlines : true,
		forced_root_block : "",
		remove_linebreaks : false,
		accessibility_warnings: false,

		plugins : "inlinesourceeditor,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,inlinepopups,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_path : false,
		theme_advanced_blockformats : "p,h1,h2,h3,h4,h5,h6",

		//theme_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",

		theme_advanced_source_editor_height : 300,
		theme_advanced_resizing_min_height : 200,
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,outdent,indent,blockquote,hr,|,justifyleft,justifycenter,justifyright",
		theme_advanced_buttons2 : "formatselect,forecolor,|,removeformat,|,imageselect",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",

		convert_urls : false,
		extended_valid_elements : "form[action|accept|accept-charset|enctype|method|class|style|mt::asset-id],iframe[src|width|height|name|align|frameborder|scrolling|marginheight|marginwidth]",
		cleanup : true,
		dialog_type : "modal",

		init_instance_callback : function(ed) {
//			var iframe = document.getElementById(ed.id + '_ifr');
//			iframe.style.position = 'static';
//
//			var container = ed.getContentAreaContainer().parentNode.parentNode.parentNode.parentNode.parentNode;
//            var container2 = container.parentNode;
//
//			if (container.className.match(/textarea-wrapper/i)) {
//				container.style.padding = '0px';
//				container.style.border = '0px';
//			}
//			if (container2.className.match(/textarea-wrapper/i)) {
//				container2.style.padding = '0px';
//				container2.style.border = '0px';
//			}
		},

		onchange_callback : function(ed)
		{
			$(ed.getBody()).text();
		},

		setup : function(ed) {
			ed.addButton('imageselect', {
				title : '画像の挿入',
				image : '',
				label : '',
				onclick : function(){
					ed.focus();
					var id_val = $('#_id').val();
					var w = 1050;
					var h = 680;
					var x = 100;
					var y = 0;
					var opt = 'width='+w+',height='+h+',top='+y+',left='+x+',status=yes,scrollbars=yes,location=yes';
					var image_upload_win = window.open("/user/mydesk/upload_image?id="+id_val,  'image_upload', opt);
//					$.timer(500, function (timer) {
//						if (image_upload_win.setEditor) {
//							image_upload_win.setEditor(ed);
//							//image_upload_win.focus();
//							timer.stop();
//						}
//					});
				}
			});
			ed.onKeyDown.add(function(ed, evt){
				user_mydesk_write_modify_flag = true;
			});
//			ed.addButton('edithtml', {
//				title : 'HTMLタグ',
//				image : '',
//				label : 'HTML表示',
//				onclick : function(){
//
//					if (swEditor.changeStatus == 'rich') {
//						swEditor.changeStatus = 'html';
//						$('#page_contents_edithtml > span.mceButtonLabel').html('HTML非表示');
//					} else {
//						swEditor.changeStatus = 'rich';
//						$('#page_contents_edithtml > span.mceButtonLabel').html('HTML表示');
//					}
//					$('textarea.webui-ignore').css('height', $('#page_contents_ifr').css('height'));
//					tinyMCE.execCommand('mceInlineSourceEditor', false, 'page_contents');
//					$('textarea.webui-ignore').val($('textarea.webui-ignore').val().replace(/<br \/>([^\n]{1})/ig, '<br />\n$1'));
//				}
//			});
//			tinyMCE.addI18n({
//				'ja': {
//					'common': {
//						'serif': '\u660e\u671d\u4f53',
//						'sans-serif': '\u30b4\u30b7\u30c3\u30af\u4f53',
//						'monospace': '\u7b49\u5e45\u30d5\u30a9\u30f3\u30c8'
//					}
//				}
//			});
		},

		placement : null
	},
	changeStatus : 'rich',
	change : function(type)
	{
		if (this.changeStatus != type) {
			this.changeStatus = type;
			if (this.changeStatus == 'html') {
				$('#tap-editor-change-rich').addClass('tap-editor-change-taboff');
				$('#tap-editor-change-html').removeClass('tap-editor-change-taboff');
				$('#page_contents_tbl td.mceToolbar').hide();
			} else {
				$('#tap-editor-change-html').addClass('tap-editor-change-taboff');
				$('#tap-editor-change-rich').removeClass('tap-editor-change-taboff');
				$('#page_contents_tbl td.mceToolbar').show();
			}
			$('textarea.webui-ignore').css('height', $('#page_contents_ifr').css('height'));
			tinyMCE.execCommand('mceInlineSourceEditor', false, 'page_contents');
			$('textarea.webui-ignore').val($('textarea.webui-ignore').val().replace(/<br \/>([^\n]{1})/ig, '<br />\n$1'));
		}
	},
	editorOnLoad : function()
	{
		window.onbeforeunload = function(){
//			var mce = typeof(tinyMCE) != 'undefined' ? tinyMCE.activeEditor : false, title, content;
//			if (mce && !mce.isHidden()) {
//				if (mce.isDirty()) return 'aaa';
//			} else {
//				if (user_mydesk_write_modify_flag) return 'bbb';
//			}
			if (user_mydesk_write_modify_flag) return swEditor.saveAlert;
		};
//		if (!$.browser.msie) {
//			window.onbeforeunload = function(event) {
//				event = event || window.event;
//				if ($.browser.webkit && !$.browser.opera && !$.browser.msie && !$.browser.mozilla ) {
//					var userAgent = navigator.userAgent.toLowerCase();
//					if ( userAgent.indexOf("chrome") != -1 ) {
//						return event.returnValue = 'このページから移動してもよろしいですか？\n他のページへ移動すると保存していないデータは破棄されます。';
//					}
//				}
//				return event.returnValue = '';
//			}
//		}
	},
	saveAlert : '他のページへ移動すると保存されていない内容は破棄されます。'
};