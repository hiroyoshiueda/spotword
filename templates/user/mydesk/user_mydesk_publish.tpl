{literal}
<script type="text/javascript">
//<![CDATA[
function publising()
{
	$("#mainform").submit();
}
function setCoverFile(obj)
{
	$('#cover_file').val(obj.cover_file);
	$('#cover_path').val(obj.cover_path);
	$('#cover_size').val(obj.cover_size);
	$('#cover_s_file').val(obj.cover_s_file);
	$('#cover_s_path').val(obj.cover_s_path);
	$('#cover_s_size').val(obj.cover_s_size);
	$('#cover_image').attr('src', '/tmp/' + obj.cover_s_path);
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div style="margin-bottom:5px;font-size:88%;"><img src="/img/icon_back.png" widht="16" height="16" alt="戻る" align="top" /> <a href="/user/mydesk/">本の一覧へ</a></div>
<div id="mydesk">
{include file="_parts/mydesk_successmsg.tpl"}
<div id="mydesk-tab">
<div id="mydesk-tab-title"><h2>{$form.htitle}</h2></div>
<div id="mydesk-tab-btn">
<ul>
	<li><a href="edit?id={$form.id}">基本情報</a></li>
	<li><a href="page?id={$form.id}">内容</a></li>
	<li><a href="image?id={$form.id}">画像管理</a></li>
	<li class="mydesk-tab-btn-selected">公開設定</li>
</ul>
<!-- #mydesk-tab-btn --></div>
<!-- #mydesk-tab --></div>

<form id="mainform" method="post" action="publising">
{include file="_parts/hidden.tpl"}
<input type="hidden" id="cover_file" name="cover_file" value="{$form.cover_file}" />
<input type="hidden" id="cover_path" name="cover_path" value="{$form.cover_path}" />
<input type="hidden" id="cover_size" name="cover_size" value="{$form.cover_size}" />
<input type="hidden" id="cover_s_file" name="cover_s_file" value="{$form.cover_s_file}" />
<input type="hidden" id="cover_s_path" name="cover_s_path" value="{$form.cover_s_path}" />
<input type="hidden" id="cover_s_size" name="cover_s_size" value="{$form.cover_s_size}" />
{if $form.save}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> 保存しました。</div>
{/if}
{if $form.errors}
<div class="errormsg" style="margin:20px 0px;"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
<div class="column_wrapper">
	<label for="login">
	 本の表紙
	</label>
	<div style="padding:10px;"><img id="cover_image" src="{if $form.id>0}{$form.cover_s_path|cover_img_src:$userInfo.user_id}{else}/img/no_image.png{/if}" width="{$smarty.const.APP_CONST_COVER_IAMGE_S_WIDTH}" height="{$smarty.const.APP_CONST_COVER_IAMGE_S_HEIGHT}" /></div>
	<div style="padding:0px 3px;"><input value="画像を選択する" onclick="openwin('cover', 'cover_select');" style="padding:2px;" type="button" /></div>
	<p class="notice">
	</p>
</div>
<div class="column_wrapper">
	<label for="login">
	 コメント
	</label>
	この本へのコメントを {tag type="select" name="comment_flag" options=$commentFlagOptions selected=$form.comment_flag}
	<p class="notice">
	</p>
</div>
<div class="column_wrapper">
	<label for="login">
	 EPUB
	</label>
	EPUB形式でのダウンロードを {tag type="select" name="epub_flag" options=$commentFlagOptions selected=$form.epub_flag}
	<p class="notice">
	</p>
</div>
<div id="btn-area">
<input value="保存する" onclick="publising();" class="btn-02" type="button" />
</div>

</form>
<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/mydesk_side_publish.tpl" publication=$form.publication}

{include file="_parts/mydesk_side.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>
