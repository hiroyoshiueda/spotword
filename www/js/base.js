String.prototype.trim = function()
{
    return this.replace(/^[\s　]+|[\s　]+$/g, '');
}
String.prototype.escapeHTML = function()
{
    return this.replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
}
String.prototype.nl2br = function()
{
	return this.replace(/\n/g, "<br />");
}
function debug(obj)
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
}
function jump(url)
{
	window.location.href = url;
}
function openwin(url, winname)
{
	window.open(url,  winname);
}
function lengthCheck(show_id, limit, num)
{
	$(show_id).html(limit - num);
	if ((limit - num) < 0){
		$(show_id).css('color', '#ff0000');
	} else {
		$(show_id).css('color', '#4c4c4c');
	}
}
function goRequest()
{
	var v = ($('#request-form-body').val() || '').trim();
	if (v == '') {
		$('#request-form-body').focus();
		return false;
	}
	$('#request-form-body').hide();
	$('#request-form-btn').hide();
	var url = $('#requestform').attr('action');
	ajaxPost(url, {body:v}, function(data, dataType){
		if (data.status == 1) {
			$('#request-form-success').show();
		} else {
			$('#request-form-msg').html(data.message);
		}
	});
	return true;
}
function goPublic()
{
	if ($('#copyright_flag').attr('checked')==false) {
		alert('【著作権の確認】\nこの作品は他人の著作権を侵害していませんか？\n\n確認して問題なければ「はい。侵害していません。」にチェックを入れてください。');
	} else {
		if (confirm('この作品を公開してよろしいですか？')) {
			$("#publicform").submit();
		}
	}
}
function goClosed()
{
	if (confirm('この作品を非公開にしてよろしいですか？')) {
		$("#publicform").submit();
	}
}
function goUpdate()
{
//	if (confirm('改訂せずに現在の編集内容を公開中の本に反映させてよろしいですか？')) {
	if (confirm('改訂せずにそのまま保存して公開しますか？')) {
		$("#updateform").submit();
	}
}
function goRevision()
{
	if ($.trim($('#revision_body').val())=='') {
		$('#revision_body_errmsg').html('改訂内容を履歴として入力してください。');
		return false;
	}
	if (confirm('編集内容を改訂して公開しますか？\n・改訂内容は履歴として公開されます。')) {
		$("#revisionform").submit();
	}
}
function revisionInputOn()
{
	$('#revision-btn-area').hide();
	$('#revision-input-area').show();
}
function revisionInputOff()
{
	$('#revision-input-area').hide();
	$('#revision-btn-area').show();
}
function previewImage()
{
	var offsetX = 30;
	var offsetY = 100;
	$("a.preview-image").hover(function(e){
		this.t = this.title;
		this.title = "";
		var c = (this.t != "") ? "<span>"+this.t : "</span>";
		var src = $('img', this).attr('src');
		//var wh = $(window).height();
		//var py = (wh / 2)<e.pageY ? e.pageY : e.pageY - (wh / 2);
		$("body").append("<p id='preview-image-popup'></p>");
		$("#preview-image-popup").html('<img src="'+src+'" alt="Image Preview" />'+c);
		$("#preview-image-popup").css("top", (e.pageY - offsetY)+"px").css("left", (e.pageX + offsetX)+"px").fadeIn("fast");
	}, function(){
		this.title = this.t;
		$("#preview-image-popup").remove();
	});
	$("a.preview-image").mousemove(function(e){
		$("#preview-image-popup").css("top", (e.pageY - offsetY)+"px").css("left", (e.pageX + offsetX)+"px");
	});
};
function getFormData(id)
{
	var data = {};
	var input_data = $(id + " :input");
	$.each(input_data, function(){
		if (this.name!='') {
			if (this.type == 'checkbox') {
				if (this.checked) {
					if (data[this.name]) {
						data[this.name] += ',' + this.value;
					} else {
						data[this.name] = this.value;
					}
				}
			} else {
				data[this.name] = this.value;
			}
		}
	});
	return data;
}
var AJAX_STATUS_SUCCESS = 1;
var AJAX_STATUS_ERROR = 0;
function ajaxPost(url, postData, successFunc)
{
	$.ajax({
		async: false,
		cache: false,
		success: successFunc,
		error: function(XMLHttpRequest, textStatus, errorThrown){
			//alert('通信中にエラーが発生しました。status=' + textStatus + '\n' + XMLHttpRequest.responseText);
			alert('通信中にエラーが発生しました。status: ' + textStatus);
		},
		data: postData,
		dataType: 'json',
		timeout: 5000,
		type: 'POST',
		url: url
	});
}
function ajaxValidateErrors(errors)
{
	var key = '', errmsg = '';
	for (key in errors) {
		var errmsg = errors[key].join('<br />');
		$('p.errormsg-bg').remove();
		$('#'+key).after('<p class="errormsg-bg">' + errmsg + '</p>');
	}
}
function addBookshelf(id)
{
	$('#add-bookshelf-btn').html(ajaxLoaderImg());
	$('#add-bookshelf-btn').removeAttr('onclick');
	$('#add-bookshelf-btn').removeAttr('href');

	var url = '/book/add_bookshelf_api';

	ajaxPost(url, {id:id}, function(data, dataType){
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#add-bookshelf-btn').html('マイ本棚に追加済み');
			$('#add-bookshelf-btn').addClass('off');
		} else {
			ajaxValidateErrors(data.errors);
		}
	});

//	$.ajax({
//		async: false,
//		complete: function(XMLHttpRequest, status){
//			if (status == "success") {
//				//alert(XMLHttpRequest.responseText);
//				var json = eval("("+XMLHttpRequest.responseText+")");
//				if (json.status == 1) {
//					$('#add-bookshelf-btn').html('マイ本棚に追加済み');
//					$('#add-bookshelf-btn').addClass('off');
//				} else {
//					alert("エラーが発生しました。\n" + json.message);
//				}
//			} else {
//				alert("エラーが発生しました。\n" + "status=" + status + "\n" + XMLHttpRequest.responseText);
//			}
//		},
//		dataType: 'json',
//		timeout: 3000,
//		type: 'GET',
//		url: url
//	});
}
function ajaxLoaderImg() { return '<img src="/img/ajax-loader.gif" width="16" height="16" align="top" />'; }
function slowScrollTop()
{
	$('html,body').animate({ scrollTop: 0 }, 'normal');
	return false;
}

function getBrowserWidth() {
    if ( window.innerWidth ) {
        return window.innerWidth;
    }
    else if ( document.documentElement && document.documentElement.clientWidth != 0 ) {
        return document.documentElement.clientWidth;
    }
    else if ( document.body ) {
        return document.body.clientWidth;
    }
    return 0;
}
function getBrowserHeight() {
    if ( window.innerHeight ) {
        return window.innerHeight;
    }
    else if ( document.documentElement && document.documentElement.clientHeight != 0 ) {
        return document.documentElement.clientHeight;
    }
    else if ( document.body ) {
        return document.body.clientHeight;
    }
    return 0;
}

jQuery.cookie = function(name, value, options)
{
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else {
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
jQuery.timer = function (interval, callback)
{
	var interval = interval || 100;

	if (!callback)
		return false;

	_timer = function (interval, callback) {
		this.stop = function () {
			clearInterval(self.id);
		};

		this.internalCallback = function () {
			callback(self);
		};

		this.reset = function (val) {
			if (self.id)
				clearInterval(self.id);

			var val = val || 100;
			this.id = setInterval(this.internalCallback, val);
		};

		this.interval = interval;
		this.id = setInterval(this.internalCallback, this.interval);

		var self = this;
	};

	return new _timer(interval, callback);
};
/**
 * jQuery hashchange 1.0.0
 *
 * (based on jquery.history)
 *
 * Copyright (c) 2008 Chris Leishman (chrisleishman.com)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 */
//(function($) {
//
//	$.fn.extend({
//	    hashchange: function(callback) { this.bind('hashchange', callback) },
//	    openOnClick: function(href) {
//			if (href === undefined || href.length == 0)
//				href = '#';
//			return this.click(function(ev) {
//				if (href && href.charAt(0) == '#') {
//					// execute load in separate call stack
//					window.setTimeout(function() { $.locationHash(href) }, 0);
//				} else {
//					window.location(href);
//				}
//				ev.stopPropagation();
//				return false;
//			});
//	    }
//	});
//
//	// IE 8 introduces the hashchange event natively - so nothing more to do
//	if ($.browser.msie && document.documentMode && document.documentMode >= 8) {
//		$.extend({
//			locationHash: function(hash) {
//		        if (!hash) hash = '#';
//		        else if (hash.charAt(0) != '#') hash = '#' + hash;
//		        location.hash = hash;
//		    }
//		});
//		return;
//	}
//
//	var curHash;
//	// hidden iframe for IE (earlier than 8)
//	var iframe;
//
//	$.extend({
//		locationHash: function(hash) {
//			if (curHash === undefined) return;
//
//			if (!hash) hash = '#';
//			else if (hash.charAt(0) != '#') hash = '#' + hash;
//
//			location.hash = hash;
//
//			if (curHash == hash) return;
//			curHash = hash;
//
//			if ($.browser.msie) updateIEFrame(hash);
//			$.event.trigger('hashchange');
//		}
//	});
//
//	$(document).ready(function() {
//	    curHash = location.hash;
//	    if ($.browser.msie) {
//	        // stop the callback firing twice during init if no hash present
//	        if (curHash == '') curHash = '#';
//	        // add hidden iframe for IE
//	        iframe = $('<iframe />').hide().get(0);
//	        $('body').prepend(iframe);
//	        updateIEFrame(location.hash);
//	        setInterval(checkHashIE, 100);
//	    } else {
//	        setInterval(checkHash, 100);
//	    }
//	});
//	$(window).unload(function() { iframe = null });
//
//	function checkHash() {
//	    var hash = location.hash;
//	    if (hash != curHash) {
//	        curHash = hash;
//	        $.event.trigger('hashchange');
//	    }
//	}
//
//	if ($.browser.msie) {
//	    // Attach a live handler for any anchor links
//	    $('a[href^=#]').live('click', function() {
//	        var hash = $(this).attr('href');
//	        // Don't intercept the click if there is an existing anchor on the page
//	        // that matches this hash
//	        if ($(hash).length == 0 && $('a[name='+hash.slice(1)+']').length == 0) {
//	            $.locationHash(hash);
//	            return false;
//	        }
//	    });
//	}
//
//	function checkHashIE() {
//	    // On IE, check for location.hash of iframe
//	    var idoc = iframe.contentDocument || iframe.contentWindow.document;
//	    var hash = idoc.location.hash;
//	    if (hash == '') hash = '#';
//
//	    if (hash != curHash) {
//	        if (location.hash != hash) location.hash = hash;
//	        curHash = hash;
//	        $.event.trigger('hashchange');
//	    }
//	}
//
//	function updateIEFrame(hash) {
//	    if (hash == '#') hash = '';
//	    var idoc = iframe.contentWindow.document;
//	    idoc.open();
//	    idoc.close();
//	    if (idoc.location.hash != hash) idoc.location.hash = hash;
//	}
//
//})(jQuery);
