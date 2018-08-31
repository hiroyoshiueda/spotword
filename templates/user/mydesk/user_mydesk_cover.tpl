{literal}
<script type="text/javascript">
function setCover(obj)
{
	window.opener.setCoverFile(obj);
	window.close();
	return false;
}
</script>
{/literal}
<div id="upload-cover">
<div id="upload-cover-form">
<form id="coverform" method="post" action="cover" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$smarty.const.APP_CONST_COVER_IAMGE_MAX_SIZE}" />
<div style="margin-bottom:10px;">
<p class="notice">※{$smarty.const.APP_CONST_COVER_IMAGE_EXT_TXT}のみ、1ファイル <strong>{$smarty.const.APP_CONST_COVER_IAMGE_MAX_SIZE|size_to_mb}MB以内</strong></p>
<p class="notice">※推奨サイズは、<strong>縦:{$smarty.const.APP_CONST_COVER_IAMGE_HEIGHT}px、横:{$smarty.const.APP_CONST_COVER_IAMGE_WIDTH}px</strong></p>
</div>
<input type="file" name="cover_file" tabindex="1" title="表紙画像の選択" style="width:300px;" size="60" />
{$form.errors.cover_file|@errormsg}
{*<div style="margin:10px 0px;"><input class="btn-02" value="選択した画像をアップロードする" tabindex="2" type="submit" /></div>*}
<div id="select-cover-upload-btn">
<input type="submit" name="upload_btn" tabindex="2" value="アップロードする" title="アップロードする" />
<!-- #select-cover-upload-btn --></div>
</form>
<!-- #upload-cover-form --></div>

{if $form.cover_file!=""}
<div id="upload-cover-list">
<h3 style="margin-top:20px;">アップロードされた画像</h3>
<div>
<a href="#" onclick="return setCover({$form.cover|@to_object});" class="select-btn">この画像を選択</a>
<a href="#" onclick="return setCover({$form.cover|@to_object});">
<img src="/tmp/{$form.cover_path}" alt="{$form.cover_file}" height="{$smarty.const.APP_CONST_COVER_IAMGE_HEIGHT}" width="{$smarty.const.APP_CONST_COVER_IAMGE_WIDTH}" /></a>
{*&nbsp;<img src="/tmp/{$form.cover_s_path}" align="top" alt="{$form.cover_s_file}" height="{$smarty.const.APP_CONST_COVER_IAMGE_S_HEIGHT}" width="{$smarty.const.APP_CONST_COVER_IAMGE_S_WIDTH}" />*}
</div>
<input type="hidden" id="cover_file" name="cover_file" value="{$form.cover_file}" />
<input type="hidden" id="cover_path" name="cover_path" value="{$form.cover_path}" />
<input type="hidden" id="cover_size" name="cover_size" value="{$form.cover_size}" />
<input type="hidden" id="cover_s_file" name="cover_s_file" value="{$form.cover_s_file}" />
<input type="hidden" id="cover_s_path" name="cover_s_path" value="{$form.cover_s_path}" />
<input type="hidden" id="cover_s_size" name="cover_s_size" value="{$form.cover_s_size}" />
<!-- #upload-cover-list --></div>
{/if}
<!-- #upload-cover --></div>
