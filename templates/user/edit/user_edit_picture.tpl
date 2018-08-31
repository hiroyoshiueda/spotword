{literal}
<script type="text/javascript">
//<![CDATA[
function doDeletePicture()
{
	if (confirm('このプロフィール画像を削除しますか？')) {
		$('#pictureform').attr('action', 'deletepicture');
		$('#pictureform').submit();
	}
	return false;
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="user-profile">
<form id="pictureform" method="post" action="uploadpicture" enctype="multipart/form-data">
{include file="_parts/hidden.tpl"}
<input type="hidden" name="MAX_FILE_SIZE" value="{$smarty.const.APP_CONST_PROFILE_IAMGE_MAX_SIZE}" />

<div style="margin-bottom:5px;"><img src="/img/icon_back.png" widht="16" height="16" alt="戻る" align="top" /> <a href="/user/edit/" title="登録情報へ戻る">戻る</a></div>
<h1>プロフィール画像</h1>
{if $form.errors}
<div class="errormsg"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
{if $form.save}
<div class="successmsg" style="font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> プロフィール画像が登録されました。</div>
{elseif $form.delete}
<div class="successmsg" style="font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> プロフィール画像が削除されました。</div>
{/if}
<p style="padding:2px;"><strong>現在のプロフィール画像</strong></p>
<table id="picture-tbl">
	<tbody>
		<tr>
			<td align="center"><div id="picture-mainimg"><img src="{$userInfo|@profileimg:'bigger'}" width="128" height="128" alt="現在のプロフィール画像" /></div>
				{if $userInfo.profile_path!=""}<p><a href="#" onclick="return doDeletePicture();">この画像を削除する</a></p>{/if}</td>
		</tr>
	</tbody>
</table>
<div class="infomsg" style="margin:15px 0;">
<ul>
	<li class="with_dot">アップロード可能な画像形式は{$smarty.const.APP_CONST_PROFILE_IMAGE_EXT_TXT}のみです。</li>
	<li class="with_dot">1回にアップロード可能な画像容量は{$smarty.const.APP_CONST_PROFILE_IAMGE_MAX_SIZE|size_to_kb}KBまでです。</li>
	<li class="with_dot">正方形の画像を推奨。長方形の場合は上部、中央寄りで切り抜かれます。</li>
</ul>
</div>
<div style="margin-bottom:15px;">
<ul id="upload-picture">
	<li><label>画像の選択</label>
		<input name="upload_file" type="file" size="30" />
		<span>→</span>
	</li>
	<li style="padding-top:16px;"><input value="アップロード" type="submit" /></li>
</ul>
<div class="clear"></div>
{$form.errors.upload_file|@errormsg}
</div>
<p id="picture-alertmsg">※著作権、肖像権の侵害になる写真、暴力的、卑猥な写真、その他一般ユーザーの方が不快に感じる写真の掲載は禁止しています。掲載はユーザー様ご自身の責任でお願い致します。</p>
</form>
<!-- #user-profile --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/mydesk_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
