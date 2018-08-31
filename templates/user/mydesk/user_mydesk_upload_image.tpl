{literal}
<script type="text/javascript">
function selectImage(imageId, imageTitle)
{
	if (_checkOpener()) {
		_selectImage(imageId, imageTitle);
	}
	window.close();
	return false;
}
function selectImageChecks()
{
	if (_checkOpener()) {
		$('input.image-ids').each(function(){
			if ($(this).attr('checked')) {
				_selectImage($(this).val(), $(this).next().text().trim());
			}
		});
	}
	window.close();
	return false;
}
function _selectImage(imageId, imageTitle)
{
	var imageSrc = $('#image-list-'+imageId+' img').attr('src');
	var imageTag = '<img src="'+imageSrc+'" alt="'+imageTitle.escapeHTML()+'"';
	var imageLayout = $('#image-layout-'+imageId).val();
	if (imageLayout==1) {
		imageTag += ' style="float:left;margin-right:10px;"';
	} else if (imageLayout==2) {
		imageTag += ' style="float:right;margin-left:10px;"';
	}
	imageTag += ' />';
	if (imageLayout>0) {
		imageTag += '（※ここに文章を入力できます）';
	}
	window.opener.tinyMCE.activeEditor.selection.setContent(imageTag);
}
function _checkOpener()
{
	if (window.opener.tinyMCE && window.opener.tinyMCE.activeEditor) {
		return true;
	} else {
		alert("この画像フォルダを再度開いて、画像を選択し直してください。");
		return false;
	}
}
function trashImage(imageId)
{
	if (confirm("この画像を削除しますか？\n※削除した画像は復元できません。\n※公開中の本の画像も削除されます。")) {
		$('#_image_id').val(imageId);
		$('#deleteform').submit();
	}
	return false;
}
$(function(){
	var w = 1070;
	var h = 800;
	var x = (screen.width  - w) / 2;
	var y = (screen.height - h) / 2;
	resizeTo(w,h);
	moveTo(x,y);
	window.focus();
	previewImage();
});
</script>
{/literal}
<div id="upload-image">
{if $form.save}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" class="icon-middle" /> 画像{if $form.filetot>0}{if $form.filetot==$form.filecnt}（{$form.filetot}枚）{else}（{$form.filecnt}/{$form.filetot}枚）{/if}{/if}を登録しました。</div>
{elseif $form.success}
<div class="successmsg" style="font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> 画像を削除しました。</div>
{/if}
<form id="uploadform" name="uploadform" method="post" action="{$base_url}" enctype="multipart/form-data">
{include file="_parts/hidden.tpl"}

{include file="_parts/mydesk_image.tpl"}

{*<div id="imgConfigArea" class="clearFix">
<h2>画像の設定</h2>

<div id="imgConfigForm" class="clearFix">

<div>
<h3>サイズ</h3>
<ul>
<li><input type="radio" id="imageSizeThumbnail" checked="checked" value="thumbnail" name="imageSizeType"/><label for="imageSizeThumbnail"><img height="16" border="0" width="16" alt="中央" src="http://stat.ameba.jp/blog/ucs/img/ucs_img_size_thumbnail.gif"/><span>縮小して表示(横幅最大220px)</span></label></li>
<li><input type="radio" id="imageSizeTypeOrg" value="original" name="imageSizeType"/><label for="imageSizeTypeOrg"><img height="16" border="0" width="16" alt="中央" src="http://stat.ameba.jp/blog/ucs/img/ucs_img_size_original.gif"/><span>オリジナルで表示</span></label></li>
</ul>
</div>

<div class="center">
<h3>配置</h3>
<ul>

<li><input type="radio" accesskey="n" id="imageAlignNone" checked="checked" value="none" name="imageAlign"/><label for="imageAlignNone"><span>指定しない</span><!-- [N] --></label></li><li>
<input type="radio" accesskey="c" id="imageAlignCenter" value="center" name="imageAlign"/><label for="imageAlignCenter"><img height="16" border="0" width="16" alt="中央" src="http://stat.ameba.jp/blog/ucs/img/ucs_img_center.gif"/><span>中央に配置</span><!-- [C] --></label></li>
<li><input type="radio" accesskey="l" id="imageAlignLeft" value="left" name="imageAlign"/><label for="imageAlignLeft"><img height="16" border="0" width="16" alt="左" src="http://stat.ameba.jp/blog/ucs/img/ucs_img_left.gif"/><span>左に配置</span><!-- [L] --></label></li>
<li><input type="radio" accesskey="r" id="imageAlignRight" value="right" name="imageAlign"/><label for="imageAlignRight"><img height="16" border="0" width="16" alt="右" src="http://stat.ameba.jp/blog/ucs/img/ucs_img_right.gif"/><span>右に配置</span><!-- [R] --></label></li>
</ul></div>

<div>
<h3>順番</h3>
<ul>
<li><input type="radio" id="imageSortNew" checked="checked" value="new" name="imageSort"/><label for="imageSortNew"><img height="16" border="0" width="35" alt="新しい順" src="http://stat.ameba.jp/blog/ucs/img/ucs_sortnew.gif"/><span>新しい順</span></label></li>
<li><input type="radio" id="imageSortOld" value="old" name="imageSort"/><label for="imageSortOld"><img height="16" border="0" width="35" alt="古い順" src="http://stat.ameba.jp/blog/ucs/img/ucs_sortold.gif"/><span>古い順</span></label></li>

</ul>
</div>

</div><!-- //imageConfig_form -->
</div><!-- //imageConfig_Area -->
*}
</form>
<div id="image-list">
<h2>画像を選ぶ</h2>
{*<div class="image-list-tool"><a href="#" onclick="return selectImageChecks();">チェックした画像を選択</a></div>*}
{foreach item=d from=$form.image_list name="image_list"}
{if $smarty.foreach.image_list.index>0 && $smarty.foreach.image_list.index % 5 == 0}<div class="clear"></div>{/if}
<div class="image-list-item">
<p class="image-list-item-title">{*<input type="checkbox" name="image_ids[]" class="image-ids" id="image-id-{$d.publication_image_id}" value="{$d.publication_image_id}" />*}<label for="image-id-{$d.publication_image_id}" title="{$d.image_title}"> {$d.image_title}</label></p>
<div class="image-list-item-img" id="image-list-{$d.publication_image_id}">
<a onclick="selectImage({$d.publication_image_id}, '{$d.image_title}');" class="preview-image" title="クリックで画像を選択">{$d.image_path|publication_img_tag:$userInfo.id:$form.id:140}</a>
</div>
<p class="image-list-item-date">{$d.createdate|datetime_f}</p>
<div>{tag type="select" id="image-layout-`$d.publication_image_id`" name="image_layout[]" options=$layoutOptions}</div>
<div>
<table class="image-list-item-btn"><tbody><tr>
<td><a href="#" class="select-btn" onclick="return selectImage({$d.publication_image_id}, '{$d.image_title}');">この画像を選択</a></td>
<td><a href="#" class="trash-btn" onclick="return trashImage({$d.publication_image_id});" title="削除する"><img src="/img/icon-trash.gif" width="16" height="16" alt="削除" style="vertical-align:middle;" /></a></td>
</tr></tbody></table>
</div>
</div>
{foreachelse}
<p class="no-data">この本の画像はアップロードされていません。</p>
{/foreach}
<div class="clear"></div>
{*<div class="image-list-tool"><a href="#" onclick="return selectImageChecks();">チェックした画像を選択</a></div>*}
<!-- #image-list --></div>
<form id="deleteform" name="deleteform" method="post" action="delete_image">
<input type="hidden" id="_image_id" name="image_id" value="" />
{include file="_parts/hidden.tpl"}
</form>
<!-- #upload-image --></div>
