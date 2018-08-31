{literal}
<script type="text/javascript">
//<![CDATA[
function doDeleteImage()
{
	var is_chk = false;
	$('input.image-ids').each(function(){
		if ($(this).attr('checked')) is_chk = true;
	});
	if (is_chk == false) {
		alert('削除する画像を選択してください。');
	} else if (confirm("選択した画像を削除しますか？\n\n※削除した画像は復元できません。\n※公開中の本の画像も削除されます。")) {
		$('#mainform').submit();
	}
}
$(function(){
	previewImage();
});
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
	<li class="mydesk-tab-btn-selected">画像管理</li>
	<li><a href="publish?id={$form.id}">公開設定</a></li>
</ul>
<!-- #mydesk-tab-btn --></div>
<!-- #mydesk-tab --></div>

<form id="uploadform" name="uploadform" method="post" action="imageupload" enctype="multipart/form-data">
{include file="_parts/hidden.tpl"}
{if $form.save}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" class="icon-middle" /> 画像{if $form.filetot>0}{if $form.filetot==$form.filecnt}（{$form.filetot}枚）{else}（{$form.filecnt}/{$form.filetot}枚）{/if}{/if}を登録しました。</div>
{elseif $form.delete}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" class="icon-middle" /> 画像を削除しました。</div>
{/if}
{include file="_parts/mydesk_image.tpl" width="630"}
</form>

{if $form.image_list}
<div style="text-align:center;margin-top:25px;"><input class="btn-02" onclick="doDeleteImage();" value="選択した画像を削除する" type="button" /></div>
{/if}

<form id="mainform" name="mainform" method="post" action="imagedelete">
{include file="_parts/hidden.tpl"}
<div id="mydesk-image-list">
{foreach item=d from=$form.image_list name="image_list"}
{if $smarty.foreach.image_list.index>0 && $smarty.foreach.image_list.index % 5 == 0}<div class="clear"></div>{/if}
<div class="image-list-item">
<div class="image-list-item-img" id="image-list-{$d.publication_image_id}">
<label for="image-id-{$d.publication_image_id}">
<a class="preview-image" title="クリックで画像を選択">{$d.image_path|publication_img_tag:$userInfo.id:$form.id:100}</a>
</label>
</div>
<p class="image-list-item-date">{$d.createdate|datetime_f}</p>
<p class="image-list-item-title"><input type="checkbox" name="image_ids[]" class="image-ids" id="image-id-{$d.publication_image_id}" value="{$d.publication_image_id}" /><label for="image-id-{$d.publication_image_id}" class="image-list-item-title-label" title="{$d.image_title} ({$d.image_size|size_to_kb}KB)"> {$d.image_title}</label></p>
{*<table class="image-list-item-btn"><tbody><tr>
<td><a href="#" class="trash-btn" onclick="return trashImage({$d.publication_image_id});" title="削除する"><img src="/img/icon-trash.gif" width="16" height="16" alt="削除" style="vertical-align:middle;" /></a></td>
</tr></tbody></table>*}
<!-- .image-list-item --></div>
{foreachelse}
<p class="no-data" style="margin-top:30px;">この本の画像はアップロードされていません。</p>
{/foreach}
<!-- #image-list --></div>
</form>

{if $form.image_list}
<div style="text-align:center;margin-bottom:20px;" class="clear"><input class="btn-02" onclick="doDeleteImage();" value="選択した画像を削除する" type="button" /></div>
{/if}

<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/mydesk_side_publish.tpl" publication=$form.publication}

{include file="_parts/mydesk_side.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>

{literal}
<script type="text/javascript">
//<![CDATA[
$('label.image-list-item-title-label').tipsy({gravity:'n'});
//]]>
</script>
{/literal}
