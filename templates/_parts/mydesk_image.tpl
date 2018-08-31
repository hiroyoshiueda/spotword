<div id="indicate"{if $width!=""} style="width:{$width}px;"{/if}>
<div id="indicate-text">
<ul>
	<li>画像数：{$form.image_list|@length}&nbsp;枚</li>
	<li>使用容量：{$form.image_user_size|size_to_mb:2}MB／{$smarty.const.APP_CONST_PUBLICATION_IAMGE_TOTAL_MAX_SIZE|size_to_mb:1}MB（{$form.image_use_rate}%）<div id="indicate-meter">
<table><tbody>
<tr title="使用率：{$form.image_use_rate}%">
<td class="meter" width="{$form.image_use_rate}%">&nbsp;</td><td width="{100 - $form.image_use_rate}%"></td>
</tr>
</tbody></table>
<!-- #indicate-meter --></div></li>
</ul>
<!-- #indicate-text --></div>
<!-- #indicate --></div>

<div id="upload-image-form"{if $width!=""} style="width:{$width}px;"{/if}>
<h2>画像の追加</h2>
{if $form.errors}
<div class="errormsg" style="margin:20px 0px;"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
<div style="margin-bottom:10px;">
<p class="notice">
※{$smarty.const.APP_CONST_PUBLICATION_IMAGE_EXT_TXT}は、1ファイル&nbsp;{$smarty.const.APP_CONST_PUBLICATION_IAMGE_MAX_SIZE|size_to_mb}MB以内。{*　⇒<a href="https://prm.ameba.jp/index.do" title="画像アップロード容量が2MBが5MBになります" target="_blank">画像容量をアップする</a>*}
</p>
<p class="notice">
※画像を圧縮したZIP形式のファイルは、1ファイル&nbsp;10MB以内で、一度に50ファイルの画像まで。
</p>
<p class="notice">
※タイトル未入力時は、オリジナルのファイル名で保存されます。
</p>
<p class="notice">
※1ページ全体に画像を表示させたい場合の推奨サイズは、<strong>縦:1024px、横:724px</strong>
</p>
</div>

{if $form.image_use_rate<=100}

<div class="select-image">
<input type="file" name="image1_file"{if $width!=""} style="width:300px;" size="50"{else} style="width:450px;" size="70"{/if} tabindex="1" title="画像の選択" />
<div style="float:right;">
<label for="image1_title">タイトル：</label>
<input type="text" name="image1_title" class="select-image-title" maxlength="30" size="20" tabindex="2" title="30文字まで"/>
</div>
<div class="clear"></div>
{$form.errors.image1_file|@errormsg}
{$form.errors.image1_title|@errormsg}
<!-- #select-image --></div>

{*<p id="moreUpload"><a href="javascript:void(0);" onclick="changeDispaly('moreUploadImageForm', 'none');" tabindex="4">画像をもっと追加する</a></p>*}

<div id="select-image-upload-btn">
<input type="submit" name="upload_btn" tabindex="3" value="アップロードする" title="アップロードする" />
<!-- #select-image-upload-btn --></div>

{else}
<p style="color:#f00;">一つの本にアップロードできる画像容量{$smarty.const.APP_CONST_PUBLICATION_IAMGE_TOTAL_MAX_SIZE|size_to_mb:1}MBを超えた為、これ以上のアップロードはできません。</p>
{/if}

<div class="clear"></div>
<!-- #upload-image-form --></div>
