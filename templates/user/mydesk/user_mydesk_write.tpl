{literal}
<script type="text/javascript">
//<![CDATA[
user_mydesk_write_modify_flag = false;
function goSaveOrCancel()
{
	if (user_mydesk_write_modify_flag!=false && confirm('内容が変更されています。保存しますか？')) {
		goSubmit();
	} else {
		user_mydesk_write_modify_flag = false;
		$('#cancelform').submit();
	}
}
function goSubmit()
{
//	if (!$('#tinymce', $('#page_contents_ifr').contents()).html().match(/^<div id="page_contents_body">/)) {
//		$('#page_contents').val('<div id="page_contents_body">' + $('#tinymce', $('#page_contents_ifr').contents()).html() + '</div>');
//	}
	user_mydesk_write_modify_flag = false;
	$("#mainform").attr("action", "writing");
	$("#mainform").removeAttr("target");
	$('#mainform').submit();
}
function autoSave()
{
	var id = "#mainform";
	var post_data = getFormData(id);
	post_data['page_contents'] = $('#tinymce', $('#page_contents_ifr').contents()).html();
//	if (!post_data['page_contents'].match(/^<div id="page_contents_body">/)) {
//		post_data['page_contents'] = '<div id="page_contents_body">' + post_data['page_contents'] + '</div>';
//	}
	ajaxPost('auto_save_api', post_data, function(data, dataType){
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#autosave-msg').html(data.nowdate + 'に自動保存しました。');
		} else {
			$('#autosave-msg').html(XMLHttpRequest.responseText);
		}
	});
}
function doPagePreview()
{
	window.open("about:blank", "page_preview", "scrollbars=yes");
	$("#mainform").attr("action", "/user/preview/");
	$("#mainform").attr("target", "page_preview");
	$("#mainform").submit();
	return false;
}
//]]>
</script>
{/literal}
<div style="margin-bottom:5px;font-size:88%;"><img src="/img/icon_back.png" widht="16" height="16" alt="戻る" align="top" /> <a href="#" onclick="goSaveOrCancel();return false;" title="内容一覧へ戻る">内容一覧へ戻る</a></div>
<div id="mydesk">
{include file="_parts/mydesk_successmsg.tpl"}
<div id="mydesk-tab" class="mydesk-tab-wide">
<div id="mydesk-tab-title"><h2>{$form.htitle}</h2></div>
<div id="mydesk-tab-btn">
<ul>
	<li><a href="edit?id={$form.id}">基本情報</a></li>
	<li class="mydesk-tab-btn-selected"><a href="page?id={$form.id}">内容</a></li>
	<li><a href="image?id={$form.id}">画像管理</a></li>
	<li><a href="publish?id={$form.id}">公開設定</a></li>
</ul>
<!-- #mydesk-tab-btn --></div>
<!-- #mydesk-tab --></div>
{if $form.errors}
<div class="errormsg" style="margin-top:20px;"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
<form id="mainform" method="post" action="writing">
{include file="_parts/hidden.tpl"}
<p id="autosave-msg"></p>
<div class="column_wrapper{$form.errors.page_title|@errorclass}">
	<label for="page_title">
	 章のタイトル<span style="font-weight:normal;color:#666;">（目次のタイトル）</span><span class="must">*</span><span class="char-length">あと<span id="title-count">{$form.page_title|length_check:40}</span>文字</span>
	</label>
	<input id="page_title" class="input-box ime-on" tabindex="1" name="page_title" value="{$form.page_title}" onkeyup="lengthCheck('#title-count', 40, this.value.length);" size="40" type="text" />
	{$form.errors.page_title|@errormsg}
	<p class="notice"></p>
</div>
<div class="column_wrapper{$form.errors.page_contents|@errorclass}">
	<textarea id="page_contents" class="input-box ime-on" tabindex="2" name="page_contents" style="color:#fff;height:300px;width:780px;" rows="5" cols="40">{$form.page_contents}</textarea>
	<div id="tap-editor-changebar">
	 <span id="tap-editor-change">
		<a id="tap-editor-change-rich" onclick="swEditor.change('rich');return false;">タグの非表示</a>
		<a id="tap-editor-change-html" onclick="swEditor.change('html');return false;" class="tap-editor-change-taboff">HTMLタグを表示</a>
	 </span>
	</div>
	{$form.errors.page_contents|@errormsg}
	<p class="notice"></p>
</div>
<div style="width:780px;text-align:center;">
<img src="/img/icon_page_preview.png" width="16" height="16" align="top" /> <a href="#" onclick="return doPagePreview();">表示を確認する</a>
</div>
<div id="btn-area">
<div style="width:780px;text-align:center;">
{tag type="select" name="status" options=$statusOptions selected=$form.status}　<input id="submit-btn" value="保存する" onclick="goSubmit();" class="btn-02" type="button" />
</div>
</div>
</form>
<form id="cancelform" method="post" action="cancelwrite">
{include file="_parts/hidden.tpl"}
</form>
<div style="margin-top:10px;font-size:88%;"><img src="/img/icon_back.png" widht="16" height="16" alt="戻る" align="top" /> <a href="#" onclick="goSaveOrCancel();return false;" title="内容一覧へ戻る">内容一覧へ戻る</a></div>
<!-- #mydesk --></div>

{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('#page_title').focus();
	swEditor.editorOnLoad();
	$('#page_contents').tinymce(swEditor.mceInit);
	$('#page_title')[$.browser.opera ? 'keypress' : 'keydown'](function(e){
		user_mydesk_write_modify_flag = true;
		if (e.which == 9 && !e.shiftKey && !e.controlKey && !e.altKey) {
			tinyMCE.get('page_contents').focus();
		}
	});
});
var first_auto_save = true;
$.timer(60000, function (timer) {
	if (!first_auto_save && user_mydesk_write_modify_flag) autoSave();
	first_auto_save = false;
});
//CKEDITOR.replace('page_contents', editorConfig());
//]]>
</script>
{/literal}