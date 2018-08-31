{literal}
<script type="text/javascript">
//<![CDATA[
function selectTab(tabName)
{
	for (var i=1; i<=3; i++) {
		$('#tab-0' + i).removeClass('mydesk-tab-btn-selected');
		$('#mydesk-tab-0' + i).css('display', 'none');
	}
	$('#' + tabName).addClass('mydesk-tab-btn-selected');
	$('#mydesk-' + tabName).show();
}
function doDeleteComment(commentId, bookId)
{
	if (confirm('このコメントを削除しますか？')) {
		jump('delete?id='+commentId+'&book_id='+bookId);
	}
	return false;
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="mydesk">
<div id="mydesk-tab">
<div id="mydesk-tab-title"><h2>{$form.htitle}</h2></div>
{*<div id="mydesk-tab-btn">
<ul>
	<li id="tab-01" class="mydesk-tab-btn-selected"><a href="javascript:selectTab('tab-01')">公開中({$form.public_list|@length})</a></li>
	<li id="tab-02"><a href="javascript:selectTab('tab-02')">非公開({$form.making_list|@length})</a></li>
</ul>
<!-- #mydesk-tab-btn --></div>*}
<!-- #mydesk-tab --></div>
{if $form.success}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> 読み込みが完了しました。<a href="/user/mydesk/edit?id={$form.id}">こちら</a>から内容を確認・編集できます。</div>
{/if}
<div id="book-comment-list" style="border:0px;">
<div id="mydesk-tab-01">
<form id="importform" method="post" action="uploadfile" enctype="multipart/form-data">
{include file="_parts/hidden.tpl"}
<input type="hidden" name="MAX_FILE_SIZE" value="{$smarty.const.APP_CONST_IMPORT_FILE_MAX_SIZE}" />

<p style="margin-bottom:10px;">既に作成済みの電子書籍ファイル（ePub）をスポットワード上に読み込みます。</p>
<div class="infomsg" style="margin-bottom:15px;">
<ul>
	<li class="with_dot">アップロード可能なファイルは{$smarty.const.APP_CONST_IMPORT_FILE_EXT_TXT}（DRMなどにより暗号化されていないもの）のみです。</li>
	<li class="with_dot">1回にアップロード可能な容量は{$smarty.const.APP_CONST_IMPORT_FILE_MAX_SIZE|size_to_mb}MBまでです。</li>
</ul>
</div>
<div style="margin-bottom:15px;">
<ul id="upload-picture">
	<li><label>ファイルの選択</label>
		<input name="upload_file" type="file" size="30" />
		<span>→</span>
	</li>
	<li style="padding-top:16px;"><input value="アップロード" type="submit" /></li>
</ul>
<div class="clear"></div>
{$form.errors.upload_file|@errormsg}
</div>

</form>
<!-- #mydesk-tab-01 --></div>

<!-- #book-comment-list --></div>
<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/mydesk_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>

